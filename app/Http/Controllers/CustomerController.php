<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Package;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class CustomerController extends Controller
{
    private function notify($userId, $type, $title, $message, $link = null) {
        Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
        ]);
    }

    private function notifyAllAdmins($type, $title, $message, $link = null) {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $this->notify($admin->id, $type, $title, $message, $link);
        }
    }

    public function index() {
        $popular = Destination::where('status', 'active')
                               ->where('is_popular', true)
                               ->get();

        $featured = Package::where('status', 'active')
                            ->where('is_featured', true)
                            ->take(6)
                            ->get();

        return view('customer.home', compact('popular', 'featured'));
    }

    public function showDestinations() {
        $destinations = Destination::all();
        return view('customer.destinations', compact('destinations'));
    }

    public function viewPackages($destId) {
        $packages = Package::where('destination_id', $destId)->get();
        return view('customer.packages_list', compact('packages'));
    }

    public function packageDetails($id) {
        $package = Package::with('destination')->findOrFail($id);
        return view('customer.package_details', compact('package'));
    }

    public function checkout($id) {
        $package = Package::findOrFail($id);
        $tax     = $package->price_per_person * 0.10;
        $total   = $package->price_per_person + $tax;
        return view('customer.checkout', compact('package', 'tax', 'total'));
    }

    public function confirmBooking(Request $request) {
        // 1. Find the package first to check capacity
        $package        = Package::findOrFail($request->package_id);
        $totalTravelers = (int)$request->num_adults + (int)$request->num_children;

        // 2. Server-side check for capacity
        if ($totalTravelers > $package->max_group_size) {
            if ($request->ajax()) {
                return response()->json([
                    'errors' => ['capacity' => ["The maximum group size for this package is {$package->max_group_size} travelers."]]
                ], 422);
            }
            return back()
                ->withInput()
                ->withErrors(['capacity' => "The maximum group size for this package is {$package->max_group_size} travelers."]);
        }

        // 3. Standard Validation
        $request->validate([
            'package_id'    => 'required|exists:packages,id',
            'customer_name' => 'required|string|max:255',
            'travel_date'   => 'required|date|after_or_equal:today',
            'method'        => 'required|in:GCash,Credit / Debit Card',
            'proof_file'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'total_price'   => 'required|numeric',
            'num_adults'    => 'required|integer|min:1',
            'num_children'  => 'required|integer|min:0',
        ]);

        // 4. Create the Booking
        $booking = Booking::create([
            'user_id'       => auth()->id(),
            'package_id'    => $request->package_id,
            'customer_name' => $request->customer_name,
            'num_adults'    => $request->num_adults,
            'num_children'  => $request->num_children,
            'travel_date'   => $request->travel_date,
            'status'        => 'pending'
        ]);

        // 5. Handle Payment and File Upload
        if ($request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('payments', 'public');

            Payment::create([
                'booking_id' => $booking->id,
                'method'     => $request->method,
                'proof_file' => $path,
                'amount'     => $request->total_price,
                'status'     => 'pending'
            ]);
        }

        $customerId  = auth()->id();
        $bookingLink = route('customer.bookings');
        $adminLink   = route('bookings.index');

        // Notify customer: booking submitted
        $this->notify(
            $customerId,
            'booking_submitted',
            'Booking Submitted 📌',
            'Your booking has been successfully submitted.',
            $bookingLink
        );

        // Notify customer: payment received
        $this->notify(
            $customerId,
            'payment_submitted',
            'Payment Received 💳',
            'Your payment proof has been received and is under review.',
            $bookingLink
        );

        // Notify all admins: new booking
        $this->notifyAllAdmins(
            'new_booking',
            'New Booking Received 📌',
            'A new booking has been submitted by ' . auth()->user()->name . '.',
            $adminLink
        );

        // CHANGED: return JSON with booking_id for AJAX, redirect for normal submit
        if ($request->ajax()) {
            return response()->json([
                'success'    => true,
                'booking_id' => $booking->id,
            ]);
        }

        return redirect()->route('customer.bookings')->with('success', 'Booking submitted successfully!');
    }

    public function cancelBooking(Request $request, $id) {
        // 1. Find the booking and ensure it belongs to the logged-in customer
        $booking = Booking::where('id', $id)
                          ->where('user_id', auth()->id())
                          ->firstOrFail();

        // 2. Prevent duplicate cancellation requests
        if (in_array($booking->status, ['cancelled', 'awaiting_cancellation'])) {
            return redirect()->back()->with('error', 'A cancellation request is already in progress or completed.');
        }

        // 3. Validate the incoming reason
        $request->validate([
            'reason'        => 'required|string',
            'custom_reason' => 'required_if:reason,Other|nullable|string|max:500',
        ]);

        // 4. Update the booking status
        $booking->update([
            'status'                     => 'awaiting_cancellation',
            'cancellation_reason'        => $request->reason,
            'cancellation_custom_reason' => ($request->reason === 'Other') ? $request->custom_reason : null,
        ]);

        $bookingLink = route('customer.bookings');
        $adminLink   = route('bookings.index');

        // Notify customer: cancellation request sent
        $this->notify(
            auth()->id(),
            'cancellation_requested',
            'Cancellation Request Sent ❌',
            'Your cancellation request is under review.',
            $bookingLink
        );

        // Notify all admins: cancellation request
        $this->notifyAllAdmins(
            'cancellation_request',
            'Booking Cancellation Request 📌',
            'A customer (' . auth()->user()->name . ') has requested to cancel their booking.',
            $adminLink
        );

        return redirect()->back()->with('success', 'Your cancellation request has been submitted and is awaiting approval.');
    }

    public function myBookings() {
        $bookings = Booking::where('user_id', Auth::id())
                           ->with('package', 'payment')
                           ->latest()
                           ->get();
        return view('customer.my_bookings', compact('bookings'));
    }

    public function storeFeedback(Request $request) {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        $booking = Booking::where('id', $request->booking_id)
                          ->where('user_id', auth()->id())
                          ->firstOrFail();

        $booking->update([
            'feedback_rating'  => $request->rating,
            'feedback_comment' => $request->comment,
        ]);

        // CHANGED: return JSON so the JS can check data.success
        return response()->json(['success' => true]);
    }
}