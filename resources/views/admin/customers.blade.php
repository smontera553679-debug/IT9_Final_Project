@extends('layouts.admin')

@section('content')
<style>
    /* ══════════════════════════════════════
       CUSTOMER TABLE — THEME-AWARE STYLES
    ══════════════════════════════════════ */

    .customer-wrap .page-title {
        color: var(--text-title);
        transition: color 0.3s ease;
    }

    .customer-wrap .card {
        background: var(--bg-navbar);
        border: 1px solid var(--border-color) !important;
        border-radius: 15px;
        overflow: visible;
        transition: background 0.3s ease, border-color 0.3s ease;
    }

    /* ── Table head ── */
    .customer-wrap .tbl-head {
        background-color: var(--border-head);
        border-bottom: 2px solid var(--border-color);
        transition: background-color 0.3s ease, border-color 0.3s ease;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .customer-wrap .tbl-head th {
        color: var(--text-muted);
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-weight: 700;
        background-color: var(--border-head) !important;
        --bs-table-bg: transparent !important;
    }

    /* ── Table body rows ── */
    .customer-wrap .table > :not(caption) > * > *,
    .customer-wrap .table tbody tr td {
        background-color: var(--bg-navbar) !important;
        color: var(--text-primary) !important;
        border-color: var(--border-item) !important;
        --bs-table-bg: var(--bg-navbar) !important;
        --bs-table-color: var(--text-primary) !important;
        --bs-table-hover-bg: var(--notif-hover) !important;
        transition: background-color 0.15s ease, color 0.3s ease, border-color 0.3s ease;
    }

    .customer-wrap .table-hover tbody tr:hover > * {
        background-color: var(--notif-hover) !important;
    }

    /* ── Outer table wrapper ── */
    .customer-wrap .table-outer {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 600px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .customer-wrap .table-outer::-webkit-scrollbar { display: none; }

    /* ── Inner clip wrapper ── */
    .customer-wrap .table-clip {
        border-radius: 15px;
        overflow: hidden;
    }

    /* ── Customer name ── */
    .customer-wrap .customer-name {
        color: var(--text-title);
        font-weight: 600;
        transition: color 0.3s ease;
    }

    /* ── Email / Phone muted ── */
    .customer-wrap .customer-muted {
        color: var(--text-muted);
        font-size: 0.88rem;
        transition: color 0.3s ease;
    }

    /* ── Booking badge ── */
    .customer-wrap .booking-badge {
        background-color: #6f42c1;
        color: #fff;
        border-radius: 100px;
        font-size: 0.82rem;
        min-width: 36px;
        padding: 5px 12px;
        display: inline-block;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(111, 66, 193, 0.35);
        transition: box-shadow 0.2s ease;
    }

    /* ── Empty state ── */
    .customer-wrap .empty-state {
        color: var(--text-time);
        font-size: 0.88rem;
        transition: color 0.3s ease;
    }

    /* ── Responsive table wrapper ── */
    .customer-wrap .table-responsive {
        background: var(--bg-navbar);
        transition: background 0.3s ease;
    }
</style>

<div class="customer-wrap container-fluid py-3 py-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold fs-4 page-title">Customer List</h2>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-clip">
                <div class="table-outer">
                    <table class="table table-hover align-middle mb-0" style="min-width:600px;">
                        <thead class="tbl-head">
                            <tr>
                                <th class="ps-3 ps-md-4 py-3">Customer</th>
                                <th class="py-3">Email</th>
                                <th class="py-3 text-center" style="white-space:nowrap;">Phone</th>
                                <th class="py-3 text-center" style="white-space:nowrap;">Bookings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td class="ps-3 ps-md-4">
                                    <span class="customer-name">{{ $customer->fullname }}</span>
                                </td>
                                <td>
                                    <span class="customer-muted">{{ $customer->email }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="customer-muted">{{ $customer->phone_number ?? '—' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="booking-badge">{{ $customer->bookings_count }}</span>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 empty-state">
                                        <i class="fas fa-users mb-2 d-block" style="font-size:2rem; opacity:0.3;"></i>
                                        No customers found.
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