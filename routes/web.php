<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\NotificationController;
use App\Http\Controllers\UserGuideController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Root redirect
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->username === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('customer.landing');
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Guest routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/reset-password',  [PasswordResetController::class, 'showResetForm'])->name('password.request');
    Route::post('/reset-password', [PasswordResetController::class, 'updatePassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/settings',         [AuthController::class, 'showSettings'])->name('account.settings');
    Route::post('/settings/update', [AuthController::class, 'updateSettings'])->name('account.settings.update');
    Route::post('/logout',          [AuthController::class, 'logout'])->name('logout');

    // ── Notifications ──────────────────────────────────────────────────────
    Route::get('/notifications',              [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/feed',         [NotificationController::class, 'getNotificationsJson'])->name('notifications.feed');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/read-all',    [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read',   [NotificationController::class, 'markRead'])->name('notifications.read');

    // ── User Guide ─────────────────────────────────────────────────────────
    Route::get('/user-guide', [UserGuideController::class, 'customer'])->name('customer.user-guide');

    /*
    |----------------------------------------------------------------------
    | CUSTOMER PANEL
    |----------------------------------------------------------------------
    */
    Route::prefix('customer')->group(function () {
        Route::get('/home',                       [CustomerController::class, 'index'])->name('customer.landing');
        Route::get('/destinations',               [CustomerController::class, 'showDestinations'])->name('customer.destinations');
        Route::get('/destinations/{id}/packages', [CustomerController::class, 'viewPackages'])->name('customer.destination.packages');
        Route::get('/package/{id}/details',       [CustomerController::class, 'packageDetails'])->name('customer.package_details');
        Route::get('/checkout/{id}',              [CustomerController::class, 'checkout'])->name('customer.checkout');
        Route::post('/confirm-booking',           [CustomerController::class, 'confirmBooking'])->name('customer.confirm');
        Route::get('/my-bookings',                [CustomerController::class, 'myBookings'])->name('customer.bookings');
        Route::post('/bookings/{id}/cancel',      [CustomerController::class, 'cancelBooking'])->name('booking.cancel');
        Route::post('/feedback',                  [CustomerController::class, 'storeFeedback'])->name('feedback.store');
    });

    /*
    |----------------------------------------------------------------------
    | ADMIN PANEL
    |----------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware('admin')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Admin User Guide
        Route::get('/user-guide', [UserGuideController::class, 'admin'])->name('admin.user-guide');

        // ── Archive ────────────────────────────────────────────────────────
        Route::get('/archive', [ArchiveController::class, 'index'])->name('admin.archive');

        // Archived destinations
        Route::post('/archive/destinations/{id}/restore',
            [ArchiveController::class, 'restoreDestination'])->name('admin.archive.destinations.restore');

        Route::get('/archive/destinations/{id}/check-deletable',
            [ArchiveController::class, 'checkDestinationDeletable'])->name('admin.archive.destinations.checkDeletable');

        Route::delete('/archive/destinations/{id}/force-delete',
            [ArchiveController::class, 'forceDeleteDestination'])->name('admin.archive.destinations.force-delete');

        // Archived packages
        Route::post('/archive/packages/{id}/restore',
            [ArchiveController::class, 'restorePackage'])->name('admin.archive.packages.restore');

        Route::delete('/archive/packages/{id}/force-delete',
            [ArchiveController::class, 'forceDeletePackage'])->name('admin.archive.packages.force-delete');

        // ── Destinations ───────────────────────────────────────────────────
        Route::get('/destinations',                    [AdminController::class, 'destinations'])->name('destinations.index');
        Route::post('/destinations/store',             [AdminController::class, 'storeDestination'])->name('destinations.store');
        Route::get('/destinations/edit/{id}',          [AdminController::class, 'editDestination'])->name('destinations.edit');
        Route::post('/destinations/update/{id}',       [AdminController::class, 'updateDestination'])->name('destinations.update');
        Route::post('/destinations/toggle/{id}',       [AdminController::class, 'toggleDestination'])->name('destinations.toggle');
        Route::get('/destinations/{id}/check-archive', [AdminController::class, 'checkBeforeArchive'])->name('destinations.checkArchive');
        Route::post('/destinations/{id}/popular',      [AdminController::class, 'togglePopular'])->name('destinations.togglePopular');
        Route::delete('/destinations/{id}',            [AdminController::class, 'destroyDestination'])->name('destinations.delete');

        // Catch stray GET delete attempts and redirect cleanly
        Route::get('/destinations/delete/{id}', fn() => redirect()->route('destinations.index'));

        // ── Packages ───────────────────────────────────────────────────────
        Route::get('/packages',              [AdminController::class, 'packages'])->name('packages.index');
        Route::post('/packages/store',       [AdminController::class, 'storePackage'])->name('packages.store');
        Route::get('/packages/{id}/edit',    [AdminController::class, 'editPackage'])->name('packages.edit');
        Route::put('/packages/{id}',         [AdminController::class, 'updatePackage'])->name('packages.update');
        Route::delete('/packages/{id}',      [AdminController::class, 'destroyPackage'])->name('packages.destroy');
        Route::post('/packages/toggle/{id}', [AdminController::class, 'togglePackage'])->name('packages.toggle');
        Route::post('/packages/{id}/featured', [AdminController::class, 'toggleFeatured'])->name('packages.toggleFeatured');

        // ── Bookings ───────────────────────────────────────────────────────
        Route::get('/bookings',                      [AdminController::class, 'bookings'])->name('bookings.index');
        Route::post('/bookings/{id}/approve-cancel', [AdminController::class, 'approveCancellation'])->name('admin.bookings.approve-cancel');
        Route::post('/bookings/{id}/reject-cancel',  [AdminController::class, 'rejectCancellation'])->name('admin.bookings.reject-cancel');

        // ── Payments ───────────────────────────────────────────────────────
        Route::get('/payments',              [AdminController::class, 'payments'])->name('payments.index');
        Route::post('/payments/confirm/{id}',[AdminController::class, 'confirmPayment'])->name('payments.confirm');
        Route::post('/payments/{id}/reject', [AdminController::class, 'rejectPayment'])->name('payments.reject');

        // ── Customers ──────────────────────────────────────────────────────
        Route::get('/customers', [AdminController::class, 'customers'])->name('customers.index');
    });
});