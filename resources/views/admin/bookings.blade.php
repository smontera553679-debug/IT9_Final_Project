@extends('layouts.admin')

@section('content')
<style>
    /* ══════════════════════════════════════
       BOOKINGS TABLE — THEME-AWARE STYLES
    ══════════════════════════════════════ */

    .bookings-wrap .page-title {
        color: var(--text-title);
        transition: color 0.3s ease;
    }

    .bookings-wrap .card {
        background: var(--bg-navbar);
        border: 1px solid var(--border-color) !important;
        border-radius: 15px;
        overflow: visible;
        transition: background 0.3s ease, border-color 0.3s ease;
    }

    .bookings-wrap .table-responsive {
        background: var(--bg-navbar);
        transition: background 0.3s ease;
    }

    /* ── Table head ── */
    .bookings-wrap .tbl-head {
        background-color: var(--border-head);
        border-bottom: 2px solid var(--border-color);
        transition: background-color 0.3s ease, border-color 0.3s ease;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .bookings-wrap .tbl-head th {
        color: var(--text-muted);
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-weight: 700;
        background-color: var(--border-head) !important;
        --bs-table-bg: transparent !important;
    }

    /* ── Table body rows ── */
    .bookings-wrap .table > :not(caption) > * > *,
    .bookings-wrap .table tbody tr td {
        background-color: var(--bg-navbar) !important;
        color: var(--text-primary) !important;
        border-color: var(--border-item) !important;
        --bs-table-bg: var(--bg-navbar) !important;
        --bs-table-color: var(--text-primary) !important;
        --bs-table-hover-bg: var(--notif-hover) !important;
        transition: background-color 0.15s ease, color 0.3s ease, border-color 0.3s ease;
    }

    .bookings-wrap .table-hover tbody tr:hover > * {
        background-color: var(--notif-hover) !important;
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
        border-radius: 15px;
        overflow: hidden;
    }

    /* ── Booking ID accent ── */
    .bookings-wrap .booking-id {
        color: #a78bfa;
        font-weight: 700;
    }

    /* ── Muted date/text ── */
    .bookings-wrap .col-muted {
        color: var(--text-muted);
        transition: color 0.3s ease;
    }

    /* ── Empty state ── */
    .bookings-wrap .empty-state {
        color: var(--text-time);
        font-size: 0.88rem;
        transition: color 0.3s ease;
    }
</style>

<div class="bookings-wrap container-fluid py-3 py-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold fs-4 page-title">Bookings Management</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-clip">
                <div class="table-outer">
                    <table class="table table-hover align-middle mb-0" style="min-width:600px;">
                        <thead class="tbl-head">
                            <tr>
                                <th class="ps-3 ps-md-4 py-3" style="white-space:nowrap;">Booking ID</th>
                                <th class="py-3">Customer Name</th>
                                <th class="py-3" style="white-space:nowrap;">Tour Package</th>
                                <th class="py-3" style="white-space:nowrap;">Travel Date</th>
                                <th class="py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td class="ps-3 ps-md-4 booking-id" style="white-space:nowrap;">
                                    BKD-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="fw-bold" style="white-space:nowrap; color: var(--text-title);">
                                    {{ $booking->user->fullname ?? 'N/A' }}
                                </td>
                                <td style="white-space:nowrap; color: var(--text-primary);">
                                    {{ $booking->package->name ?? 'N/A' }}
                                </td>
                                <td class="col-muted" style="white-space:nowrap;">
                                    {{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}
                                </td>
                                <td class="text-center">
                                    @php $status = strtolower($booking->status); @endphp
                                    @if($status == 'confirmed')
                                        <span class="badge rounded-pill px-3 py-2" style="background-color:#16a34a;">Confirmed</span>
                                    @elseif($status == 'rejected')
                                        <span class="badge rounded-pill px-3 py-2" style="background-color:#dc2626;">Rejected</span>
                                    @elseif($status == 'cancelled')
                                        <span class="badge rounded-pill px-3 py-2" style="background-color:#4b5563;">Cancelled</span>
                                    @elseif($status == 'awaiting_cancellation')
                                        <span class="badge rounded-pill px-3 py-2" style="background-color:#0891b2;">Awaiting Cancel</span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2 text-dark" style="background-color:#d97706;">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 empty-state">
                                        <i class="fas fa-calendar-times mb-2 d-block" style="font-size:2rem; opacity:0.3;"></i>
                                        No bookings found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection