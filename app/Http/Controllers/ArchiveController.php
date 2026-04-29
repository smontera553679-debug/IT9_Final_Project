<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Package;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{
    /* =========================================================================
        INDEX — show all archived destinations & packages
    ========================================================================= */

    public function index()
    {
        $archivedDestinations = Destination::onlyTrashed()->get();
        $archivedPackages     = Package::onlyTrashed()
            ->with(['destination' => fn($q) => $q->withTrashed()])
            ->get();

        return view('admin.archive', compact('archivedDestinations', 'archivedPackages'));
    }

    /* =========================================================================
        DESTINATIONS
    ========================================================================= */

    /**
     * Restore a soft-deleted destination.
     * All of its non-archived packages are automatically set to inactive
     * so the admin must manually review and activate them individually.
     */
    public function restoreDestination($id)
    {
        $destination = Destination::onlyTrashed()->findOrFail($id);
        $destination->restore();

        // Auto-deactivate all active (non-archived) packages under this destination
        $deactivatedCount = Package::withTrashed()
            ->where('destination_id', $destination->id)
            ->whereNull('deleted_at')
            ->update(['status' => 'inactive']);

        $message = "Destination \"{$destination->name}\" has been restored.";

        if ($deactivatedCount > 0) {
            $message .= " {$deactivatedCount} package" . ($deactivatedCount > 1 ? 's have' : ' has') . " been set to inactive — activate them from the Packages page when ready.";
        }

        return back()->with('success', $message);
    }

    /**
     * AJAX — check whether a destination can be permanently deleted.
     * Blocked if ANY booking (any status, ever) exists for any of its packages,
     * so booking history, reports, and transaction records are always preserved.
     */
    public function checkDestinationDeletable($id)
    {
        $destination  = Destination::onlyTrashed()->findOrFail($id);
        $bookingCount = $this->countAllDestinationBookings($destination);

        return response()->json([
            'can_delete'    => $bookingCount === 0,
            'booking_count' => $bookingCount,
        ]);
    }

    /**
     * Permanently delete a destination and ALL its packages (trashed or not).
     * Blocked if ANY booking ever existed for any related package.
     */
    public function forceDeleteDestination($id)
    {
        $destination  = Destination::onlyTrashed()->findOrFail($id);
        $bookingCount = $this->countAllDestinationBookings($destination);

        if ($bookingCount > 0) {
            return back()->with('error',
                "Cannot permanently delete \"{$destination->name}\" — it has {$bookingCount} booking record(s) that must be preserved."
            );
        }

        // Force-delete every related package (trashed or not) + their images
        Package::withTrashed()
            ->where('destination_id', $destination->id)
            ->get()
            ->each(function ($pkg) {
                if ($pkg->image) Storage::disk('public')->delete($pkg->image);
                $pkg->forceDelete();
            });

        if ($destination->image) {
            Storage::disk('public')->delete($destination->image);
        }

        $name = $destination->name;
        $destination->forceDelete();

        return back()->with('success', "Destination \"{$name}\" and all its packages have been permanently deleted.");
    }

    /**
     * Helper — count ALL bookings (any status) tied to a destination's packages.
     * Covers: pending, confirmed, cancelled, rejected, completed.
     */
    private function countAllDestinationBookings(Destination $destination): int
    {
        $packageIds = Package::withTrashed()
            ->where('destination_id', $destination->id)
            ->pluck('id');

        return Booking::whereIn('package_id', $packageIds)->count();
    }

    /* =========================================================================
        PACKAGES
    ========================================================================= */

    /**
     * Restore a soft-deleted package.
     * Blocked if the parent destination is still archived.
     * Package is always restored as 'inactive' — admin must manually activate it.
     */
    public function restorePackage($id)
    {
        $package     = Package::onlyTrashed()->findOrFail($id);
        $destination = Destination::withTrashed()->find($package->destination_id);

        if ($destination && $destination->trashed()) {
            return back()->with('error',
                "Cannot restore \"{$package->name}\" because its destination \"{$destination->name}\" is still archived. Restore the destination first."
            );
        }

        $package->restore();
        $package->update(['status' => 'inactive']);

        return back()->with('success', "Package \"{$package->name}\" has been restored as inactive. Activate it from the Packages page when ready.");
    }

    /**
     * Permanently delete a package.
     * Blocked if ANY booking (any status) ever existed for this package.
     * Booking history, reports, and transaction records must always be preserved.
     */
    public function forceDeletePackage($id)
    {
        $package = Package::onlyTrashed()->findOrFail($id);

        // Count ALL bookings regardless of status
        $bookingCount = Booking::where('package_id', $package->id)->count();

        if ($bookingCount > 0) {
            return back()->with('error',
                "Cannot permanently delete \"{$package->name}\" — it has {$bookingCount} booking record(s) that must be preserved."
            );
        }

        if ($package->image) {
            Storage::disk('public')->delete($package->image);
        }

        $name = $package->name;
        $package->forceDelete();

        return back()->with('success', "Package \"{$name}\" has been permanently deleted.");
    }
}