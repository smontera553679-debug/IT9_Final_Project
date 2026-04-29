<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Package;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /* =========================================================================
        PRIVATE HELPERS
    ========================================================================= */

    private function notify($userId, $type, $title, $message, $link = null)
    {
        Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
        ]);
    }

    private function notifyAllAdmins($type, $title, $message, $link = null)
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $this->notify($admin->id, $type, $title, $message, $link);
        }
    }

    /* =========================================================================
        DASHBOARD
    ========================================================================= */

    public function dashboard()
    {
        $totalBookings = Booking::count();
        $revenue       = Payment::where('status', 'confirmed')->sum('amount');
        $activeTours   = Package::where('status', 'active')->count();
        $totalRatings  = Booking::whereNotNull('feedback_rating')->count();
        $overallRating = round(Booking::whereNotNull('feedback_rating')->avg('feedback_rating') ?? 0, 1);

        /* ── Monthly Revenue ── */
        $monthlyRevenue = Payment::where('status', 'confirmed')
            ->selectRaw('DATE_FORMAT(created_at, "%b") as month, MONTH(created_at) as month_num, SUM(amount) as total')
            ->groupBy('month', 'month_num')
            ->orderBy('month_num', 'asc')
            ->get();

        $months      = $monthlyRevenue->pluck('month')->toArray();
        $revenueData = $monthlyRevenue->pluck('total')->map(fn($v) => (float) $v)->toArray();

        /* ── Weekly Revenue (last 7 days) ── */
        $weeklyRevenue = Payment::where('status', 'confirmed')
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->selectRaw('DATE_FORMAT(created_at, "%a") as day, DAYOFWEEK(created_at) as day_num, SUM(amount) as total')
            ->groupBy('day', 'day_num')
            ->orderBy('day_num', 'asc')
            ->get();

        $weeklyLabels = $weeklyRevenue->pluck('day')->toArray();
        $weeklyData   = $weeklyRevenue->pluck('total')->map(fn($v) => (float) $v)->toArray();

        /* ── Yearly Revenue (last 5 years) ── */
        $yearlyRevenue = Payment::where('status', 'confirmed')
            ->where('created_at', '>=', Carbon::now()->subYears(4)->startOfYear())
            ->selectRaw('YEAR(created_at) as year, SUM(amount) as total')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        $yearlyLabels = $yearlyRevenue->pluck('year')->map(fn($y) => (string) $y)->toArray();
        $yearlyData   = $yearlyRevenue->pluck('total')->map(fn($v) => (float) $v)->toArray();

        /* ── Fallbacks ── */
        if (empty($months))       { $months       = ['No Data']; $revenueData = [0]; }
        if (empty($weeklyLabels)) { $weeklyLabels = ['No Data']; $weeklyData  = [0]; }
        if (empty($yearlyLabels)) { $yearlyLabels = ['No Data']; $yearlyData  = [0]; }

        /* ── Popular Destinations ── */
        $destinations = Destination::withCount('bookings')->get();
        $destNames    = $destinations->pluck('name')->toArray();
        $destCounts   = $destinations->pluck('bookings_count')->toArray();

        if (empty($destNames)) { $destNames = ['No Data']; $destCounts = [0]; }

        return view('admin.dashboard', compact(
            'totalBookings', 'revenue', 'activeTours', 'totalRatings', 'overallRating',
            'months', 'revenueData',
            'weeklyLabels', 'weeklyData',
            'yearlyLabels', 'yearlyData',
            'destNames', 'destCounts'
        ));
    }

    /* =========================================================================
        DESTINATIONS
    ========================================================================= */

    public function destinations()
    {
        $destinations = Destination::all();
        return view('admin.destinations', compact('destinations'));
    }

    public function storeDestination(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'country'     => 'required|string|max:255',
            'title'       => 'nullable|string|max:255',
            'category'    => 'required',
            'status'      => 'required|in:active,inactive',
            'description' => 'required',
            'image'       => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('destinations', 'public');
        }

        Destination::create($data);
        return back()->with('success', 'Destination Added Successfully!');
    }

    public function editDestination($id)
    {
        $destination = Destination::findOrFail($id);
        return response()->json($destination);
    }

    public function updateDestination(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required',
            'country'     => 'required',
            'title'       => 'nullable|string|max:255',
            'category'    => 'required',
            'description' => 'required',
            'image'       => 'nullable|image|max:4096',
        ]);

        $dest              = Destination::findOrFail($id);
        $dest->name        = $request->name;
        $dest->country     = $request->country;
        $dest->title       = $request->title;
        $dest->category    = $request->category;
        $dest->description = $request->description;

        if ($request->hasFile('image')) {
            if ($dest->image) Storage::disk('public')->delete($dest->image);
            $dest->image = $request->file('image')->store('destinations', 'public');
        }

        $dest->save();
        return back()->with('success', 'Destination updated successfully!');
    }

    /**
     * AJAX check — called before showing the archive confirm dialog.
     * Returns how many active packages belong to this destination.
     */
    public function checkBeforeArchive(Destination $destination)
    {
        $activePackages = $destination->packages()
            ->where('status', 'active')
            ->count();

        return response()->json([
            'has_active_packages' => $activePackages > 0,
            'active_count'        => $activePackages,
        ]);
    }

    /**
     * Soft-delete (archive) a destination.
     * All related packages are soft-deleted and set to inactive too.
     */
    public function destroyDestination(Request $request, $id)
    {
        $destination = Destination::findOrFail($id);

        // Archive & deactivate all related packages
        $destination->packages()->each(function ($package) {
            $package->update(['status' => 'inactive']);
            $package->delete(); // SoftDeletes → sets deleted_at
        });

        $destination->delete(); // SoftDeletes → sets deleted_at

        return back()->with('success', 'Destination archived. All related packages have been archived and made inactive.');
    }

    /* =========================================================================
        PACKAGES
    ========================================================================= */

    public function packages()
    {
        // withTrashed on destination so the name still shows on the package row
        // even if the destination was archived after the package was created.
        $packages     = Package::with(['destination' => fn($q) => $q->withTrashed()])->get();
        $destinations = Destination::where('status', 'active')->get();

        return view('admin.packages', compact('packages', 'destinations'));
    }

    public function storePackage(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'destination_id'   => 'required|exists:destinations,id',
            'price_per_person' => 'required|numeric',
            'status'           => 'required',
            'description'      => 'required',
            'itinerary'        => 'required|array|min:1',
            'transport'        => 'required|array|min:1',
            'inclusions'       => 'nullable|array',
            'exclusions'       => 'nullable|array',
            'max_group_size'   => 'required|integer',
            'language'         => 'required',
            'currency'         => 'required',
            'rating'           => 'required|integer|min:1|max:5',
            'image'            => 'required|image|max:4096',
        ]);

        $data['duration_days'] = count($request->itinerary);
        $data['itinerary']     = json_encode($request->itinerary);
        $data['transport']     = json_encode($request->transport);
        $data['inclusions']    = json_encode($request->input('inclusions', []));
        $data['exclusions']    = json_encode($request->input('exclusions', []));

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        Package::create($data);
        return back()->with('success', 'Package created successfully!');
    }

    public function editPackage($id)
    {
        return response()->json(Package::findOrFail($id));
    }

    public function updatePackage(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $data = $request->validate([
            'name'             => 'required',
            'destination_id'   => 'required',
            'price_per_person' => 'required|numeric',
            'status'           => 'required',
            'description'      => 'required',
            'itinerary'        => 'required|array|min:1',
            'transport'        => 'required|array|min:1',
            'inclusions'       => 'nullable|array',
            'exclusions'       => 'nullable|array',
            'max_group_size'   => 'required',
            'language'         => 'required',
            'currency'         => 'required',
            'rating'           => 'required',
            'image'            => 'nullable|image|max:4096',
        ]);

        $data['duration_days'] = count($request->itinerary);
        $data['itinerary']     = json_encode($request->itinerary);
        $data['transport']     = json_encode($request->transport);
        $data['inclusions']    = json_encode($request->input('inclusions', []));
        $data['exclusions']    = json_encode($request->input('exclusions', []));

        if ($request->hasFile('image')) {
            if ($package->image) Storage::disk('public')->delete($package->image);
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        $package->update($data);
        return back()->with('success', 'Package updated successfully!');
    }

    /**
     * Soft-delete (archive) a package.
     * Archiving is ALWAYS allowed — existing bookings remain valid and untouched.
     * Only force-delete (permanent) is blocked when bookings exist.
     */
    public function destroyPackage($id)
    {
        $package = Package::findOrFail($id);

        $package->update(['status' => 'inactive']);
        $package->delete(); // SoftDeletes → sets deleted_at

        return back()->with('success', "Package \"{$package->name}\" has been archived. Existing bookings are unaffected.");
    }

    /* =========================================================================
        TOGGLES & STATUS
    ========================================================================= */

    public function toggleDestination($id)
    {
        $dest         = Destination::findOrFail($id);
        $dest->status = $dest->status === 'active' ? 'inactive' : 'active';
        $dest->save();
        return back();
    }

    public function togglePackage($id)
    {
        $package = Package::findOrFail($id);

        // Prevent activating a package whose destination is archived
        if ($package->status === 'inactive') {
            $destination = Destination::withTrashed()->find($package->destination_id);
            if ($destination && $destination->trashed()) {
                return back()->with('error', "Cannot activate \"{$package->name}\" because its destination \"{$destination->name}\" is archived. Restore the destination first.");
            }
        }

        $package->status = $package->status === 'active' ? 'inactive' : 'active';
        $package->save();
        return back();
    }

    public function toggleFeatured($id)
    {
        $package              = Package::findOrFail($id);
        $package->is_featured = !$package->is_featured;
        $package->save();

        // Only notify customers when featuring (turning ON)
        if ($package->is_featured) {
            $customers = User::where('role', 'customer')->get();
            foreach ($customers as $customer) {
                $this->notify(
                    $customer->id,
                    'package',
                    '🌟 Featured Package Available!',
                    "{$package->name} is now a featured tour package. Don't miss it!",
                    route('customer.landing') . '#packages'
                );
            }
        }

        return back()->with('success', 'Featured updated!');
    }

    public function togglePopular($id)
    {
        $destination             = Destination::findOrFail($id);
        $destination->is_popular = !$destination->is_popular;
        $destination->save();

        // Only notify customers when marking as popular (turning ON)
        if ($destination->is_popular) {
            $customers = User::where('role', 'customer')->get();
            foreach ($customers as $customer) {
                $this->notify(
                    $customer->id,
                    'destination',
                    '⭐ New Popular Destination!',
                    "{$destination->name} has been highlighted as a popular destination. Check it out!",
                    route('customer.landing') . '#destinations'
                );
            }
        }

        return back()->with('success', 'Popularity updated!');
    }

    /* =========================================================================
        BOOKINGS & PAYMENTS
    ========================================================================= */

    public function bookings()
    {
        $bookings = Booking::with(['user', 'package', 'payment'])->latest()->get();
        return view('admin.bookings', compact('bookings'));
    }

    public function payments()
    {
        $payments = Payment::with(['booking.user', 'booking.package'])->latest()->get();
        return view('admin.payments', compact('payments'));
    }

    public function confirmPayment($id)
    {
        $payment = Payment::findOrFail($id);

        DB::transaction(function () use ($payment) {
            $payment->update(['status' => 'confirmed']);

            if ($payment->booking) {
                $payment->booking->update(['status' => 'confirmed']);

                $customerId  = $payment->booking->user_id;
                $bookingLink = route('customer.bookings');

                $this->notify($customerId, 'payment_verified',  'Payment Verified ✅',  'Your payment has been confirmed.',               $bookingLink);
                $this->notify($customerId, 'booking_approved',  'Booking Approved 🎉',  'Your booking has been confirmed by the admin.',  $bookingLink);
            }
        });

        return back()->with('success', 'Payment confirmed!');
    }

    public function rejectPayment(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string',
        ]);

        $payment = Payment::findOrFail($id);

        DB::transaction(function () use ($payment, $request) {
            $payment->update([
                'status'        => 'rejected',
                'reject_reason' => $request->reject_reason,
            ]);

            if ($payment->booking_id) {
                DB::table('bookings')
                    ->where('id', $payment->booking_id)
                    ->update(['status' => 'rejected']);

                Log::info("Booking ID {$payment->booking_id} status successfully forced to 'rejected'.");

                $booking = Booking::find($payment->booking_id);
                $this->notify(
                    $booking->user_id,
                    'payment_rejected',
                    'Payment Rejected ❌',
                    'Your payment was rejected. Reason: ' . $request->reject_reason,
                    route('customer.bookings')
                );
            }
        });

        return back()->with('success', 'Payment and Booking record rejected successfully.');
    }

    public function approveCancellation($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'cancelled']);

        $this->notify(
            $booking->user_id,
            'cancellation_approved',
            'Cancellation Approved',
            'Your booking has been cancelled successfully.',
            route('customer.bookings')
        );

        return back()->with('success', 'Cancellation approved.');
    }

    public function rejectCancellation($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'confirmed']);

        $this->notify(
            $booking->user_id,
            'cancellation_rejected',
            'Cancellation Rejected',
            'Your cancellation request was denied. Your booking remains active.',
            route('customer.bookings')
        );

        return back()->with('success', 'Cancellation rejected.');
    }

    /* =========================================================================
        CUSTOMERS
    ========================================================================= */

    public function customers()
    {
        $customers = User::where('role', 'customer')
            ->where('username', '!=', 'admin')
            ->withCount('bookings')
            ->get();

        return view('admin.customers', compact('customers'));
    }
}