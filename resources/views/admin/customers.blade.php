@extends('layouts.admin')

@section('content')
<style>
    .customer-wrap .page-title { color: var(--text-title); transition: color 0.3s ease; }

    .customer-wrap .card {
        background: var(--bg-navbar);
        border: 1px solid var(--border-color) !important;
        border-radius: 15px; overflow: visible;
        transition: background 0.3s ease, border-color 0.3s ease;
    }

    .customer-wrap .tbl-head {
        background-color: var(--border-head);
        border-bottom: 2px solid var(--border-color);
        transition: background-color 0.3s ease, border-color 0.3s ease;
        position: sticky; top: 0; z-index: 10;
    }
    .customer-wrap .tbl-head th {
        color: var(--text-muted);
        font-size: 0.72rem; letter-spacing: 0.08em;
        text-transform: uppercase; font-weight: 700;
        background-color: var(--border-head) !important;
        --bs-table-bg: transparent !important;
    }

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
    .customer-wrap .table-hover tbody tr:hover > * { background-color: var(--notif-hover) !important; }

    .customer-wrap .table-outer {
        overflow-x: auto; overflow-y: auto; max-height: 600px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none; -ms-overflow-style: none;
    }
    .customer-wrap .table-outer::-webkit-scrollbar { display: none; }
    .customer-wrap .table-clip { border-radius: 15px 15px 0 0; overflow: hidden; }

    .customer-wrap .customer-name { color: var(--text-title); font-weight: 600; transition: color 0.3s ease; }
    .customer-wrap .customer-muted { color: var(--text-muted); font-size: 0.88rem; transition: color 0.3s ease; }
    .customer-wrap .booking-badge {
        background-color: #6f42c1; color: #fff;
        border-radius: 100px; font-size: 0.82rem;
        min-width: 36px; padding: 5px 12px;
        display: inline-block; font-weight: 700;
        box-shadow: 0 2px 8px rgba(111,66,193,0.35);
        transition: box-shadow 0.2s ease;
    }
    .customer-wrap .empty-state { color: var(--text-time); font-size: 0.88rem; transition: color 0.3s ease; }
    .customer-wrap .table-responsive { background: var(--bg-navbar); transition: background 0.3s ease; }

    /* ── Pagination ── */
    .pagination-nav {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px; padding: 14px 20px;
        border-top: 1px solid var(--border-item);
        background: var(--bg-navbar);
        border-radius: 0 0 15px 15px;
        transition: background 0.3s ease;
    }
    .pagination-info { font-size: 0.78rem; color: var(--text-muted); transition: color 0.3s ease; }
    .pagination-info span { color: var(--text-title); font-weight: 600; }
    .pagination-links { display: flex; align-items: center; gap: 4px; }
    .page-btn {
        min-width: 34px; height: 34px; border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-navbar); color: var(--text-primary);
        font-size: 0.82rem; font-weight: 600;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.15s ease; text-decoration: none;
    }
    .page-btn:hover { background: var(--notif-hover); border-color: #6f42c1; color: #6f42c1; }
    .page-btn.active { background: #6f42c1; border-color: #6f42c1; color: #fff; box-shadow: 0 3px 10px rgba(111,66,193,0.35); }
    .page-btn.disabled { opacity: 0.35; cursor: not-allowed; pointer-events: none; }
    .page-btn.nav-btn { padding: 0 12px; font-size: 0.78rem; }
    .page-dots { color: var(--text-muted); font-size: 0.82rem; padding: 0 2px; transition: color 0.3s ease; }
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

            @include('components.pagination', ['paginator' => $customers, 'label' => 'customers'])
        </div>
    </div>
</div>
@endsection