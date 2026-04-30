@extends('layouts.customer')

@section('content')
<style>
    .bookings-wrap {
        background-color: var(--bg-body);
        min-height: 100vh;
        transition: background-color 0.3s ease;
    }

    .bookings-wrap h2 {
        color: var(--text-title);
    }

    /* ── Table card ── */
    .bookings-wrap .card {
        background-color: var(--bg-dropdown) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 14px !important;
        overflow: visible !important;
    }

    /* ── Nuclear override: kill every Bootstrap table background ── */
    .bookings-wrap .table,
    .bookings-wrap .table > :not(caption) > * > *,
    .bookings-wrap .table > thead,
    .bookings-wrap .table > tbody,
    .bookings-wrap .table > tfoot,
    .bookings-wrap .table > thead > tr,
    .bookings-wrap .table > tbody > tr,
    .bookings-wrap .table > thead > tr > th,
    .bookings-wrap .table > thead > tr > td,
    .bookings-wrap .table > tbody > tr > th,
    .bookings-wrap .table > tbody > tr > td,
    .bookings-wrap .table-hover > tbody > tr:hover > *,
    .bookings-wrap .table-striped > tbody > tr:nth-of-type(odd) > *,
    .bookings-wrap .table-light,
    .bookings-wrap .table-light th,
    .bookings-wrap .table-light td {
        background-color: var(--bg-dropdown) !important;
        color: var(--text-primary) !important;
        border-color: var(--border-color) !important;
        --bs-table-bg: var(--bg-dropdown) !important;
        --bs-table-striped-bg: var(--bg-dropdown) !important;
        --bs-table-hover-bg: var(--notif-hover) !important;
        --bs-table-color: var(--text-primary) !important;
        --bs-table-striped-color: var(--text-primary) !important;
        --bs-table-hover-color: var(--text-primary) !important;
        --bs-table-accent-bg: transparent !important;
    }

    /* thead specifically */
    .bookings-wrap .table thead tr,
    .bookings-wrap .table thead th {
        background-color: var(--bg-body) !important;
        color: var(--text-muted) !important;
        border-color: var(--border-color) !important;
        font-size: 0.75rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-weight: 700;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    /* tbody rows */
    .bookings-wrap .table tbody tr {
        background-color: var(--bg-dropdown) !important;
        border-color: var(--border-color) !important;
        transition: background-color 0.15s;
    }

    .bookings-wrap .table tbody tr:hover,
    .bookings-wrap .table tbody tr:hover > * {
        background-color: var(--notif-hover) !important;
    }

    .bookings-wrap .table td,
    .bookings-wrap .table th {
        color: var(--text-primary) !important;
        border-color: var(--border-color) !important;
        vertical-align: middle;
    }

    .bookings-wrap .text-muted {
        color: var(--text-muted) !important;
    }

    /* ── Outer table wrapper ── */
    .bookings-wrap .table-outer {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 600px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .bookings-wrap .table-outer::-webkit-scrollbar { display: none; }

    /* ── Inner clip wrapper ── */
    .bookings-wrap .table-clip {
        border-radius: 14px;
        overflow: hidden;
    }

    /* ── Buttons ── */
    .bookings-wrap .btn-outline-primary {
        border-color: #6f42c1 !important;
        color: #6f42c1 !important;
        background: transparent !important;
    }
    .bookings-wrap .btn-outline-primary:hover {
        background-color: rgba(111,66,193,0.1) !important;
    }

    .bookings-wrap .btn-outline-danger {
        border-color: #ef4444 !important;
        color: #ef4444 !important;
        background: transparent !important;
    }
    .bookings-wrap .btn-outline-danger:hover {
        background-color: rgba(239,68,68,0.1) !important;
    }

    .bookings-wrap .text-success { color: #22c55e !important; }

    /* ── Alert ── */
    .bookings-wrap .alert-success {
        background-color: var(--notif-unread) !important;
        border-color: #2d5a2d !important;
        color: #6fcf97 !important;
        border-radius: 10px !important;
    }
    .bookings-wrap .btn-close { filter: var(--text-title) == '#e8eaf0' ? invert(1) : none; }

    /* ── table-responsive container ── */
    .bookings-wrap .table-responsive {
        background-color: var(--bg-dropdown) !important;
    }

    /* ══════════════════════
       VIEW MODAL
    ══════════════════════ */
    .modal-content {
        background-color: var(--bg-dropdown) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }

    .modal-summary-strip {
        background-color: var(--bg-body) !important;
        border-bottom: 1px solid var(--border-color) !important;
    }
    .modal-summary-strip p { color: var(--text-muted) !important; }
    .modal-summary-strip .fw-bold { color: var(--text-title) !important; }

    .modal-inner-body {
        background-color: var(--bg-dropdown) !important;
        color: var(--text-primary) !important;
    }
    .modal-inner-body hr {
        border-color: var(--border-color) !important;
        opacity: 1 !important;
    }
    .modal-inner-body .text-muted { color: var(--text-muted) !important; }
    .modal-inner-body .fw-bold    { color: var(--text-title) !important; }
    .modal-inner-body p           { color: var(--text-primary) !important; }

    .modal-inner-body [style*="border-left:1px solid #f0f0f0"],
    .modal-inner-body [style*="border-left: 1px solid #f0f0f0"] {
        border-left-color: var(--border-color) !important;
    }

    .modal-pkg-info-card {
        background-color: var(--bg-body) !important;
        border: 1px solid var(--border-color) !important;
    }
    .modal-pkg-info-card .pkg-name-label { color: #7c3aed !important; }

    .modal-info-tile {
        border-color: var(--border-color) !important;
        background-color: var(--bg-body) !important;
    }
    .modal-info-tile p { color: var(--text-muted) !important; }

    .modal-traveler-box {
        background-color: var(--bg-body) !important;
        border: 1px solid var(--border-color) !important;
    }

    .modal-pricing-box {
        background-color: var(--bg-body) !important;
        border: 1px solid var(--border-color) !important;
    }
    .modal-pricing-box .text-dark { color: var(--text-primary) !important; }
    .modal-pricing-box [style*="border-top:2px solid"] {
        border-top-color: var(--border-color) !important;
    }

    .modal-payment-history {
        background-color: var(--bg-body) !important;
        border: 1px solid var(--border-color) !important;
    }
    .modal-payment-history [style*="border-bottom:1px solid"] {
        border-bottom-color: var(--border-color) !important;
    }

    .modal-itinerary-row {
        border: 1px solid var(--border-color) !important;
    }
    .modal-itinerary-day-text {
        background-color: var(--bg-body) !important;
        color: var(--text-primary) !important;
    }

    .modal-footer-dark {
        background-color: var(--bg-body) !important;
        border-top: 1px solid var(--border-color) !important;
    }

    .cancel-modal-content {
        background-color: var(--bg-dropdown) !important;
        color: var(--text-primary) !important;
        border: 1px solid var(--border-color) !important;
    }
    .cancel-modal-content .modal-header { border-color: var(--border-color) !important; }
    .cancel-modal-content h5 { color: var(--text-title) !important; }
    .cancel-modal-content .form-label { color: var(--text-muted) !important; }
    .cancel-modal-content .form-select,
    .cancel-modal-content .form-control {
        background-color: var(--bg-body) !important;
        border-color: var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    .cancel-modal-content .form-select:focus,
    .cancel-modal-content .form-control:focus {
        border-color: #a855f7 !important;
        box-shadow: 0 0 0 3px rgba(168,85,247,0.15) !important;
    }
    .cancel-modal-content .btn-close { filter: invert(0.5); }
    [data-theme="dark"] .cancel-modal-content .btn-close { filter: invert(1); }
</style>

<div class="bookings-wrap py-4 py-md-5">
<div class="container">
    <div class="mb-4">
        <h2 class="fw-bold" style="font-size:clamp(1.3rem,4vw,1.8rem);">My Booking History</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius:14px;">
        <div class="card-body p-0">
            <div class="table-clip">
                <div class="table-outer">
                    <table class="table table-hover align-middle mb-0" style="min-width:650px; table-layout:fixed; width:100%;">
                        <thead>
                            <tr>
                                <th class="py-3 text-center" style="width:70px;">ID</th>
                                <th class="py-3 text-center">Package</th>
                                <th class="py-3 text-center" style="width:150px;">Travel Date</th>
                                <th class="py-3 text-center" style="width:130px;">Status</th>
                                <th class="py-3 text-center" style="width:180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            @php
                                $badge = [
                                    'pending'               => 'bg-warning text-dark',
                                    'awaiting_cancellation' => 'bg-info text-white',
                                    'cancelled'             => 'bg-secondary',
                                    'confirmed'             => 'bg-success',
                                    'rejected'              => 'bg-danger'
                                ][$booking->status] ?? 'bg-primary';
                                $label = str_replace('_', ' ', ucfirst($booking->status));

                                $pkg        = $booking->package;
                                $itinerary  = is_string($pkg->itinerary)  ? json_decode($pkg->itinerary, true)  : ($pkg->itinerary  ?? []);
                                $inclusions = is_string($pkg->inclusions) ? (json_decode($pkg->inclusions, true) ?? explode(',', $pkg->inclusions)) : ($pkg->inclusions ?? []);
                                $exclusions = is_string($pkg->exclusions) ? (json_decode($pkg->exclusions, true) ?? explode(',', $pkg->exclusions)) : ($pkg->exclusions ?? []);

                                $numAdults   = $booking->num_adults   ?? $booking->adults   ?? null;
                                $numChildren = $booking->num_children ?? $booking->children ?? null;
                                $pax         = $booking->pax ?? $booking->guests ?? $booking->number_of_travelers ?? (($numAdults ?? 1) + ($numChildren ?? 0));

                                $pkgPrice    = $pkg->price_per_person ?? $pkg->price ?? 0;
                                $childPrice  = $pkgPrice * 0.5;
                                $subtotal    = $booking->subtotal ?? ($numAdults !== null
                                                ? (($numAdults * $pkgPrice) + (($numChildren ?? 0) * $childPrice))
                                                : $pkgPrice * $pax);
                                $discount    = $booking->discount   ?? 0;
                                $tax         = $booking->tax        ?? ($subtotal * 0.10);
                                $total       = $booking->total_price ?? ($subtotal - $discount + $tax);
                                $amountPaid  = $booking->amount_paid ?? 0;

                                $travelStart = \Carbon\Carbon::parse($booking->travel_date);
                                $travelEnd   = isset($booking->return_date) ? \Carbon\Carbon::parse($booking->return_date) : $travelStart->copy()->addDays(($pkg->duration_days ?? 1) - 1);

                                $bookingUser    = $booking->user ?? auth()->user();
                                $customerName   = $booking->customer_name ?? $bookingUser->fullname ?? $bookingUser->name;
                                $customerEmail  = $bookingUser->email ?? auth()->user()->email;
                                $customerPhone  = $booking->contact_number ?? $bookingUser->phone_number ?? $bookingUser->phone ?? null;

                                $pkgImage    = $pkg->image ?? $pkg->photo ?? $pkg->thumbnail ?? null;
                                $pkgImageUrl = $pkgImage ? asset('storage/' . $pkgImage) : null;

                                $rating      = $pkg->rating ?? $pkg->average_rating ?? null;
                                $ratingCount = $pkg->reviews_count ?? $pkg->ratings_count ?? null;

                                $payStatus = $booking->payment_status ?? ($amountPaid >= $total ? 'Paid' : ($amountPaid > 0 ? 'Partial' : 'Unpaid'));
                                $payBadge  = $payStatus === 'Paid' || $payStatus === 'confirmed'
                                                ? 'bg-success'
                                                : ($payStatus === 'Partial' || $payStatus === 'partial'
                                                    ? 'bg-warning text-dark'
                                                    : 'bg-danger');
                                $payLabel  = ucfirst($payStatus);

                                $transportRaw  = is_string($pkg->transport) ? json_decode($pkg->transport, true) : ($pkg->transport ?? []);
                                $transport     = strtolower(is_array($transportRaw) ? implode(' ', $transportRaw) : ($transportRaw ?? ''));
                                $transportDisplay = is_array($transportRaw)
                                    ? implode(', ', array_map('ucfirst', $transportRaw))
                                    : ucfirst($transport);

                                $transportIcon = 'fa-van-shuttle';
                                if (str_contains($transport, 'airplane') || str_contains($transport, 'plane') || str_contains($transport, 'air') || str_contains($transport, 'flight')) {
                                    $transportIcon = 'fa-plane';
                                } elseif (str_contains($transport, 'bus') || str_contains($transport, 'coach')) {
                                    $transportIcon = 'fa-bus';
                                } elseif (str_contains($transport, 'boat') || str_contains($transport, 'ship') || str_contains($transport, 'ferry') || str_contains($transport, 'cruise')) {
                                    $transportIcon = 'fa-ship';
                                } elseif (str_contains($transport, 'train') || str_contains($transport, 'rail')) {
                                    $transportIcon = 'fa-train';
                                } elseif (str_contains($transport, 'car') || str_contains($transport, 'suv') || str_contains($transport, 'vehicle')) {
                                    $transportIcon = 'fa-car';
                                } elseif (str_contains($transport, 'motorcycle') || str_contains($transport, 'bike')) {
                                    $transportIcon = 'fa-motorcycle';
                                } elseif (str_contains($transport, 'walk') || str_contains($transport, 'hiking')) {
                                    $transportIcon = 'fa-person-hiking';
                                }

                                $pkgCategory = $pkg->category ?? null;
                                $categoryIcons = [
                                    'Beach'      => 'fa-umbrella-beach',
                                    'Mountain'   => 'fa-mountain',
                                    'City'       => 'fa-city',
                                    'Historical' => 'fa-landmark',
                                ];
                                $pkgCategoryIcon = $categoryIcons[$pkgCategory] ?? 'fa-tag';
                            @endphp
                            <tr>
                                <td class="fw-bold text-center" style="white-space:nowrap; width:70px;">#{{ $booking->id }}</td>
                                <td class="text-center" style="min-width:120px;">
                                    <span style="font-size:clamp(0.8rem,2vw,0.95rem);">{{ $pkg->name }}</span>
                                    <div class="d-md-none text-muted mt-1" style="font-size:0.75rem;">
                                        {{ $travelStart->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="text-muted text-center" style="white-space:nowrap; width:150px;">
                                    {{ $travelStart->format('M d, Y') }}
                                </td>
                                <td class="text-center" style="width:130px;">
                                    <span class="badge rounded-pill {{ $badge }} px-2 px-md-3" style="font-size:0.7rem;">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="text-center" style="white-space:nowrap; width:180px;">
                                    <button type="button"
                                            class="btn btn-outline-primary btn-sm rounded-pill px-2 px-md-3 me-1"
                                            style="font-size:0.78rem;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewModal{{ $booking->id }}">
                                        <i class="fa-solid fa-eye me-1" style="font-size:0.7rem;"></i>View
                                    </button>

                                    @if($booking->status == 'pending')
                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm rounded-pill px-2 px-md-3"
                                                style="font-size:0.78rem;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#cancelModal{{ $booking->id }}">
                                            Cancel
                                        </button>
                                    @elseif($booking->status == 'confirmed')
                                        <span class="text-success small fw-bold">Secured ✓</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- ══════════════════════ VIEW MODAL ══════════════════════ --}}
                            <div class="modal fade" id="viewModal{{ $booking->id }}" tabindex="-1"
                                 aria-labelledby="viewModalLabel{{ $booking->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
                                     style="max-width:680px;">
                                    <div class="modal-content border-0" style="border-radius:18px; overflow:hidden;">

                                        <div class="modal-header border-0 px-4 pt-4 pb-3"
                                             style="background:linear-gradient(135deg,#4c1d95 0%,#7c3aed 100%);">
                                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                                <div style="width:42px;height:42px;border-radius:50%;background:rgba(255,255,255,0.18);
                                                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                    <i class="fa-solid fa-suitcase-rolling text-white" style="font-size:1rem;"></i>
                                                </div>
                                                <div>
                                                    <p class="mb-0 text-white-50" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;">
                                                        Booking #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                                                    </p>
                                                    <h5 class="mb-0 text-white fw-bold" style="font-size:1rem;"
                                                        id="viewModalLabel{{ $booking->id }}">
                                                        {{ $pkg->name }}
                                                    </h5>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white ms-3"
                                                    data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body p-0">

                                            {{-- 1. Booking Summary --}}
                                            <div class="modal-summary-strip px-4 py-3">
                                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                                                    <div class="text-center">
                                                        <p class="mb-1" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.1em;">Booking ID</p>
                                                        <p class="mb-0 fw-bold" style="font-size:0.82rem;">#{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                    </div>
                                                    <div class="text-center">
                                                        <p class="mb-1" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.1em;">Booked On</p>
                                                        <p class="mb-0 fw-bold" style="font-size:0.82rem;">{{ \Carbon\Carbon::parse($booking->created_at)->format('M d, Y') }}</p>
                                                    </div>
                                                    <div class="text-center">
                                                        <p class="mb-1" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.1em;">Booking Status</p>
                                                        <span class="badge rounded-pill {{ $badge }} px-2" style="font-size:0.68rem;">{{ $label }}</span>
                                                    </div>
                                                    <div class="text-center">
                                                        <p class="mb-1" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.1em;">Payment Status</p>
                                                        <span class="badge rounded-pill {{ $payBadge }} px-2" style="font-size:0.68rem;">{{ $payLabel }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-inner-body px-4 pt-4 pb-2">

                                                {{-- 2. Customer Details --}}
                                                <div class="mb-4">
                                                    <div class="d-flex align-items-center gap-2 mb-3">
                                                        <div style="width:24px;height:24px;border-radius:6px;background:rgba(111,66,193,0.15);
                                                                    display:flex;align-items:center;justify-content:center;">
                                                            <i class="fa-solid fa-user" style="color:#7c3aed;font-size:0.65rem;"></i>
                                                        </div>
                                                        <span style="font-size:0.68rem;font-weight:700;letter-spacing:0.15em;
                                                                     text-transform:uppercase;color:#7c3aed;">Customer Details</span>
                                                    </div>
                                                    <div class="d-flex gap-0" style="font-size:0.85rem;">
                                                        <div style="flex:1;min-width:0;padding-right:12px;">
                                                            <p class="mb-0 text-muted" style="font-size:0.72rem;">Customer Name</p>
                                                            <p class="mb-0 fw-bold text-truncate">{{ $customerName }}</p>
                                                        </div>
                                                        <div style="flex:2;min-width:0;padding-right:12px;border-left:1px solid var(--border-color);padding-left:12px;">
                                                            <p class="mb-0 text-muted" style="font-size:0.72rem;">Email</p>
                                                            <p class="mb-0 text-truncate" style="font-size:0.8rem;">{{ $customerEmail }}</p>
                                                        </div>
                                                        <div style="flex:1;min-width:0;border-left:1px solid var(--border-color);padding-left:12px;">
                                                            <p class="mb-0 text-muted" style="font-size:0.72rem;">Phone Number</p>
                                                            <p class="mb-0" style="font-size:0.8rem;">{{ $customerPhone ?? '—' }}</p>
                                                        </div>
                                                    </div>
                                                    @if($booking->address ?? null)
                                                    <div class="mt-2">
                                                        <p class="mb-0 text-muted" style="font-size:0.72rem;">Address</p>
                                                        <p class="mb-0">{{ $booking->address }}</p>
                                                    </div>
                                                    @endif
                                                </div>

                                                <hr>

                                                {{-- 3. Tour Package Details --}}
                                                <div class="mb-4">
                                                    <div class="d-flex align-items-center gap-2 mb-3">
                                                        <div style="width:24px;height:24px;border-radius:6px;background:rgba(230,126,34,0.15);
                                                                    display:flex;align-items:center;justify-content:center;">
                                                            <i class="fa-solid fa-map-location-dot" style="color:#e67e22;font-size:0.65rem;"></i>
                                                        </div>
                                                        <span style="font-size:0.68rem;font-weight:700;letter-spacing:0.15em;
                                                                     text-transform:uppercase;color:#e67e22;">Tour Package Details</span>
                                                    </div>

                                                    <div class="modal-pkg-info-card rounded-3 mb-3 overflow-hidden">
                                                        @if($pkgImageUrl)
                                                        <img src="{{ $pkgImageUrl }}" alt="{{ $pkg->name }}"
                                                             style="width:100%;height:180px;object-fit:cover;display:block;">
                                                        @else
                                                        <div style="width:100%;height:120px;background:linear-gradient(135deg,rgba(111,66,193,0.2),rgba(124,58,237,0.1));
                                                                    display:flex;align-items:center;justify-content:center;">
                                                            <i class="fa-solid fa-image text-muted" style="font-size:2rem;opacity:0.4;"></i>
                                                        </div>
                                                        @endif
                                                        <div class="p-3">
                                                            <p class="fw-bold mb-1 pkg-name-label" style="font-size:0.95rem;">{{ $pkg->name }}</p>
                                                            @if($pkgCategory)
                                                            <p class="mb-1" style="font-size:0.78rem;">
                                                                <i class="fa-solid {{ $pkgCategoryIcon }} me-1" style="color:#b89a5a;"></i>
                                                                <span style="color:var(--text-muted);">{{ $pkgCategory }}</span>
                                                            </p>
                                                            @endif
                                                            @if($pkg->destination ?? $pkg->location ?? null)
                                                            <p class="mb-1 text-muted" style="font-size:0.78rem;">
                                                                <i class="fa-solid fa-location-dot me-1" style="color:#e67e22;"></i>
                                                                {{ is_object($pkg->destination) ? ($pkg->destination->country ?? $pkg->destination) : ($pkg->destination ?? $pkg->location) }}
                                                            </p>
                                                            @endif
                                                            @if($rating !== null)
                                                            <div class="d-flex align-items-center gap-1 mb-2">
                                                                @php $fullStars = floor($rating); $halfStar = ($rating - $fullStars) >= 0.5; @endphp
                                                                @for($s = 1; $s <= 5; $s++)
                                                                    @if($s <= $fullStars)
                                                                        <i class="fa-solid fa-star" style="color:#f59e0b;font-size:0.72rem;"></i>
                                                                    @elseif($s == $fullStars + 1 && $halfStar)
                                                                        <i class="fa-solid fa-star-half-stroke" style="color:#f59e0b;font-size:0.72rem;"></i>
                                                                    @else
                                                                        <i class="fa-regular fa-star" style="color:#d1d5db;font-size:0.72rem;"></i>
                                                                    @endif
                                                                @endfor
                                                                <span class="fw-bold" style="font-size:0.75rem;">{{ number_format($rating, 1) }}</span>
                                                                @if($ratingCount)
                                                                <span class="text-muted" style="font-size:0.72rem;">({{ $ratingCount }} reviews)</span>
                                                                @endif
                                                            </div>
                                                            @endif
                                                            @if($pkg->description)
                                                            <p class="mb-0 text-muted" style="font-size:0.8rem;line-height:1.5;">
                                                                {{ \Str::limit($pkg->description, 160) }}
                                                            </p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="row g-2">
                                                        @php
                                                            $tiles = [
                                                                ['icon'=>'fa-clock',        'color'=>'#7c3aed', 'value'=>($pkg->duration_days??'—').'D / '.(($pkg->duration_days-1)??'—').'N', 'label'=>'Duration'],
                                                                ['icon'=>'fa-calendar-days','color'=>'#16a34a', 'value'=>$travelStart->format('M d').' – '.$travelEnd->format('M d, Y'),         'label'=>'Travel Dates','nowrap'=>true],
                                                                ['icon'=>'fa-users',        'color'=>'#e67e22', 'value'=>(string)$pax,                                                            'label'=>'Travelers'],
                                                                ['icon'=>$transportIcon,    'color'=>'#a855f7', 'value'=>$transportDisplay,                                                       'label'=>'Transport'],
                                                            ];
                                                            if ($pkgCategory) {
                                                                $tiles[] = ['icon'=>$pkgCategoryIcon, 'color'=>'#b89a5a', 'value'=>$pkgCategory, 'label'=>'Category'];
                                                            }
                                                        @endphp
                                                        @foreach($tiles as $tile)
                                                        <div class="col-6 col-md-3">
                                                            <div class="modal-info-tile" style="border-radius:12px;padding:12px 8px;
                                                                        display:flex;flex-direction:column;align-items:center;
                                                                        justify-content:center;text-align:center;height:100%;min-height:90px;gap:4px;
                                                                        border:1px solid var(--border-color);background:var(--bg-body);">
                                                                <i class="fa-solid {{ $tile['icon'] }}" style="color:{{ $tile['color'] }};font-size:1rem;flex-shrink:0;"></i>
                                                                <p class="mb-0 fw-bold" style="font-size:{{ isset($tile['nowrap'])?'0.72rem':'0.8rem' }};line-height:1.3;white-space:{{ isset($tile['nowrap'])?'nowrap':'normal' }};">
                                                                    {{ $tile['value'] }}
                                                                </p>
                                                                <p class="mb-0 text-muted" style="font-size:0.65rem;">{{ $tile['label'] }}</p>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>

                                                    @if($numAdults !== null)
                                                    <div class="modal-traveler-box mt-3 p-3 rounded-3">
                                                        <p class="mb-2 fw-bold" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.08em;">
                                                            Traveler Breakdown
                                                        </p>
                                                        <div class="d-flex gap-3 flex-wrap" style="font-size:0.83rem;">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <i class="fa-solid fa-person" style="color:#7c3aed;font-size:0.85rem;"></i>
                                                                <span>Adults: <strong>{{ $numAdults }}</strong></span>
                                                                <span class="text-muted" style="font-size:0.75rem;">(₱{{ number_format($pkgPrice, 2) }} each)</span>
                                                            </div>
                                                            @if(($numChildren ?? 0) > 0)
                                                            <div class="d-flex align-items-center gap-2">
                                                                <i class="fa-solid fa-child" style="color:#e67e22;font-size:0.85rem;"></i>
                                                                <span>Children: <strong>{{ $numChildren }}</strong></span>
                                                                <span class="text-muted" style="font-size:0.75rem;">(₱{{ number_format($childPrice, 2) }} each)</span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>

                                                <hr>

                                                {{-- 4. Inclusions & Exclusions --}}
                                                @if(count($inclusions) > 0 || count($exclusions) > 0)
                                                <div class="mb-4">
                                                    <div class="d-flex align-items-center gap-2 mb-3">
                                                        <div style="width:24px;height:24px;border-radius:6px;background:rgba(34,197,94,0.15);
                                                                    display:flex;align-items:center;justify-content:center;">
                                                            <i class="fa-solid fa-circle-check" style="color:#16a34a;font-size:0.65rem;"></i>
                                                        </div>
                                                        <span style="font-size:0.68rem;font-weight:700;letter-spacing:0.15em;
                                                                     text-transform:uppercase;color:#16a34a;">Inclusions & Exclusions</span>
                                                    </div>
                                                    <div class="row g-3">
                                                        @if(count($inclusions) > 0)
                                                        <div class="col-md-6">
                                                            <p class="mb-2 fw-bold" style="font-size:0.75rem;color:#16a34a;">
                                                                <i class="fa-solid fa-check-circle me-1"></i> What's Included
                                                            </p>
                                                            @foreach($inclusions as $inc)
                                                            <div class="d-flex align-items-start gap-2 mb-1" style="font-size:0.82rem;">
                                                                <i class="fa-solid fa-check text-success mt-1" style="font-size:0.65rem;flex-shrink:0;"></i>
                                                                <span>{{ trim($inc) }}</span>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        @endif
                                                        @if(count($exclusions) > 0)
                                                        <div class="col-md-6">
                                                            <p class="mb-2 fw-bold" style="font-size:0.75rem;color:#dc2626;">
                                                                <i class="fa-solid fa-times-circle me-1"></i> Not Included
                                                            </p>
                                                            @foreach($exclusions as $exc)
                                                            <div class="d-flex align-items-start gap-2 mb-1" style="font-size:0.82rem;">
                                                                <i class="fa-solid fa-xmark text-danger mt-1" style="font-size:0.65rem;flex-shrink:0;"></i>
                                                                <span>{{ trim($exc) }}</span>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr>
                                                @endif

                                                {{-- 5. Pricing Breakdown --}}
                                                <div class="mb-4">
                                                    <div class="d-flex align-items-center gap-2 mb-3">
                                                        <div style="width:24px;height:24px;border-radius:6px;background:rgba(202,138,4,0.15);
                                                                    display:flex;align-items:center;justify-content:center;">
                                                            <i class="fa-solid fa-peso-sign" style="color:#ca8a04;font-size:0.65rem;"></i>
                                                        </div>
                                                        <span style="font-size:0.68rem;font-weight:700;letter-spacing:0.15em;
                                                                     text-transform:uppercase;color:#ca8a04;">Pricing Breakdown</span>
                                                    </div>
                                                    <div class="modal-pricing-box p-3 rounded-3">
                                                        @if($numAdults !== null)
                                                            <div class="d-flex justify-content-between mb-2" style="font-size:0.85rem;">
                                                                <span class="text-muted">Adults <span style="color:var(--text-primary);">(₱{{ number_format($pkgPrice, 2) }} × {{ $numAdults }})</span></span>
                                                                <span>₱{{ number_format($pkgPrice * $numAdults, 2) }}</span>
                                                            </div>
                                                            @if(($numChildren ?? 0) > 0)
                                                            <div class="d-flex justify-content-between mb-2" style="font-size:0.85rem;">
                                                                <span class="text-muted">Children <span style="color:var(--text-primary);">(₱{{ number_format($childPrice, 2) }} × {{ $numChildren }})</span></span>
                                                                <span>₱{{ number_format($childPrice * $numChildren, 2) }}</span>
                                                            </div>
                                                            @endif
                                                        @else
                                                            <div class="d-flex justify-content-between mb-2" style="font-size:0.85rem;">
                                                                <span class="text-muted">Package price <span style="color:var(--text-primary);">(₱{{ number_format($pkgPrice, 2) }} × {{ $pax }} pax)</span></span>
                                                                <span>₱{{ number_format($subtotal, 2) }}</span>
                                                            </div>
                                                        @endif
                                                        @if($discount > 0)
                                                        <div class="d-flex justify-content-between mb-2 text-success" style="font-size:0.85rem;">
                                                            <span>Discount</span>
                                                            <span>– ₱{{ number_format($discount, 2) }}</span>
                                                        </div>
                                                        @endif
                                                        <div class="d-flex justify-content-between mb-2" style="font-size:0.85rem;">
                                                            <span class="text-muted">Taxes (10%)</span>
                                                            <span>₱{{ number_format($tax, 2) }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between pt-2 mt-1 fw-bold"
                                                             style="border-top:2px solid var(--border-color);font-size:0.95rem;">
                                                            <span>Total</span>
                                                            <span style="color:#a855f7;">₱{{ number_format($total, 2) }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                {{-- 6. Payment Details --}}
                                                <div class="mb-4">
                                                    <div class="d-flex align-items-center gap-2 mb-3">
                                                        <div style="width:24px;height:24px;border-radius:6px;background:rgba(37,99,235,0.15);
                                                                    display:flex;align-items:center;justify-content:center;">
                                                            <i class="fa-solid fa-credit-card" style="color:#2563eb;font-size:0.65rem;"></i>
                                                        </div>
                                                        <span style="font-size:0.68rem;font-weight:700;letter-spacing:0.15em;
                                                                     text-transform:uppercase;color:#2563eb;">Payment Details</span>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <p class="mb-0 text-muted" style="font-size:0.7rem;">Method</p>
                                                            <p class="mb-0 fw-bold" style="font-size:0.83rem;">
                                                                {{ $booking->payment->method ?? $booking->payment_method ?? '—' }}
                                                            </p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-0 text-muted" style="font-size:0.7rem;">Payment Status</p>
                                                            <span class="badge rounded-pill {{ $payBadge }} px-2" style="font-size:0.72rem;">{{ $payLabel }}</span>
                                                        </div>
                                                    </div>
                                                    @if($booking->payments && count($booking->payments) > 0)
                                                    <div class="modal-payment-history p-3 rounded-3">
                                                        <p class="mb-2 fw-bold" style="font-size:0.72rem;color:#2563eb;">Payment History</p>
                                                        @foreach($booking->payments as $payment)
                                                        <div class="d-flex justify-content-between align-items-center py-1"
                                                             style="font-size:0.8rem;border-bottom:1px solid var(--border-color);">
                                                            <span class="text-muted">{{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y') }}</span>
                                                            <span>{{ $payment->method ?? '—' }}</span>
                                                            <span class="fw-bold text-success">₱{{ number_format($payment->amount, 2) }}</span>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    @endif
                                                </div>

                                                {{-- 7. Itinerary --}}
                                                @if(count($itinerary) > 0)
                                                <hr>
                                                <div class="mb-3">
                                                    <div class="d-flex align-items-center gap-2 mb-3">
                                                        <div style="width:24px;height:24px;border-radius:6px;background:rgba(147,51,234,0.15);
                                                                    display:flex;align-items:center;justify-content:center;">
                                                            <i class="fa-solid fa-route" style="color:#9333ea;font-size:0.65rem;"></i>
                                                        </div>
                                                        <span style="font-size:0.68rem;font-weight:700;letter-spacing:0.15em;
                                                                     text-transform:uppercase;color:#9333ea;">Day-by-Day Itinerary</span>
                                                    </div>
                                                    @foreach($itinerary as $dayIndex => $dayDesc)
                                                    @php $dayNum = $dayIndex + 1; $dayDate = $travelStart->copy()->addDays($dayIndex); @endphp
                                                    <div class="modal-itinerary-row d-flex gap-0 mb-2 rounded-3 overflow-hidden">
                                                        <div class="d-flex flex-column align-items-center justify-content-center px-3 py-2 flex-shrink-0"
                                                             style="background:#7c3aed;min-width:64px;text-align:center;">
                                                            <span class="text-white fw-bold" style="font-size:1rem;line-height:1;">{{ $dayNum }}</span>
                                                            <span class="text-white" style="font-size:0.58rem;opacity:0.75;letter-spacing:0.08em;text-transform:uppercase;">Day</span>
                                                            <span class="text-white mt-1" style="font-size:0.6rem;opacity:0.8;">{{ $dayDate->format('M d') }}</span>
                                                        </div>
                                                        <div class="modal-itinerary-day-text px-3 py-2 flex-grow-1" style="font-size:0.83rem;line-height:1.55;">
                                                            {{ $dayDesc }}
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="modal-footer-dark modal-footer border-0 px-4 pb-4 pt-2">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4 w-100"
                                                    data-bs-dismiss="modal">Close</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- ══════════════════════ END VIEW MODAL ══════════════════════ --}}

                            {{-- Cancel Modal --}}
                            @if($booking->status == 'pending')
                            <div class="modal fade text-start" id="cancelModal{{ $booking->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
                                    <div class="modal-content cancel-modal-content" style="border-radius:14px;">
                                        <form action="{{ route('booking.cancel', $booking->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold" style="font-size:1rem;">
                                                    Cancel Booking #{{ $booking->id }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body pt-3">
                                                <label class="form-label fw-bold small">Why do you want to cancel?</label>
                                                <select name="reason" class="form-select" required
                                                        onchange="toggleOtherField(this, {{ $booking->id }})">
                                                    <option value="" disabled selected>Choose a reason...</option>
                                                    <option value="Change in destination preference">Change in destination preference</option>
                                                    <option value="Flight cancellation/change">Flight cancellation/change</option>
                                                    <option value="Found a cheaper option">Found a cheaper option</option>
                                                    <option value="Change of plans">Change of plans</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <div id="otherField{{ $booking->id }}" class="d-none mt-3">
                                                    <textarea name="custom_reason" class="form-control" rows="3"
                                                              placeholder="Please specify..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="submit" class="btn btn-danger rounded-pill px-4 w-100">
                                                    Submit Request
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No bookings found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
function toggleOtherField(select, id) {
    const field = document.getElementById('otherField' + id);
    if (field) {
        field.classList.toggle('d-none', select.value !== 'Other');
        field.querySelector('textarea').required = (select.value === 'Other');
    }
}
</script>
@endsection