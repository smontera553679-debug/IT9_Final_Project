@extends('layouts.admin')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap');

    .arch-wrap {
        font-family: 'DM Sans', sans-serif;
        min-height: 100vh;
        padding: 2rem 1.5rem 3rem;
        background: var(--bg-body, #f4f5f9);
    }

    .arch-section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1rem;
    }

    .arch-section-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .arch-section-icon.red   { background: rgba(239,68,68,0.12);  color: #ef4444; }
    .arch-section-icon.amber { background: rgba(245,158,11,0.12); color: #f59e0b; }

    .arch-section-title {
        font-family: 'Syne', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-title, #111827);
        margin: 0;
        letter-spacing: -0.01em;
    }

    .arch-section-count {
        margin-left: auto;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 100px;
        background: var(--border-head, #f1f5f9);
        color: var(--text-muted, #6b7280);
        border: 1px solid var(--border-color, #e5e7eb);
    }

    .arch-card {
        background: var(--bg-navbar, #fff);
        border: 1px solid var(--border-color, #e5e7eb);
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 2.5rem;
        box-shadow: 0 1px 12px rgba(0,0,0,0.05);
        transition: background 0.3s ease, border-color 0.3s ease;
    }

    .arch-tbl-head {
        display: flex;
        align-items: center;
        padding: 0.65rem 1.25rem;
        background: var(--border-head, #f8fafc);
        border-bottom: 1px solid var(--border-color, #e5e7eb);
        position: sticky;
        top: 0;
        z-index: 10;
        transition: background 0.3s ease;
    }

    .arch-tbl-head-cell {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted, #9ca3af);
        text-align: center;
        flex-shrink: 0;
    }

    .arch-table-outer {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 460px;
        scrollbar-width: none;
        -ms-overflow-style: none;
        -webkit-overflow-scrolling: touch;
    }
    .arch-table-outer::-webkit-scrollbar { display: none; }
    .arch-table-inner { min-width: 570px; width: 100%; }

    .arch-swipe-container {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -ms-overflow-style: none;
        background: var(--bg-navbar, #fff);
        transition: background 0.3s ease;
    }
    .arch-swipe-container::-webkit-scrollbar { display: none; }

    .arch-swipe-content {
        min-width: 100%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        padding: 0.9rem 1.25rem;
        scroll-snap-align: start;
        border-bottom: 1px solid var(--border-item, #f1f5f9);
        transition: background 0.18s ease, border-color 0.3s ease;
        gap: 0;
    }
    .arch-swipe-content:hover {
        background: var(--border-head, #f8fafc);
    }

    .arch-swipe-actions {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        padding: 0 1rem;
        gap: 8px;
        background: var(--border-head, #f8fafc);
        border-left: 1px solid var(--border-color, #e5e7eb);
        border-bottom: 1px solid var(--border-item, #f1f5f9);
        scroll-snap-align: end;
        transition: background 0.3s ease;
    }

    .arch-row-cell {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 400;
        color: #111827;
        text-align: center;
        overflow: hidden;
        flex-shrink: 0;
        transition: color 0.3s ease;
    }

    .arch-row-cell.name-cell {
        justify-content: flex-start;
        font-weight: 400;
    }

    .arch-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.68rem;
        font-weight: 400;
        padding: 3px 9px;
        border-radius: 100px;
    }
    .arch-badge.danger        { background: rgba(239,68,68,0.1);   color: #ef4444; }
    .arch-badge.muted         { background: var(--border-head, #f1f5f9); color: #111827; border: 1px solid var(--border-color,#e5e7eb); }
    .arch-badge.warning       { background: rgba(245,158,11,0.1);  color: #d97706; }
    .arch-badge.dest-archived { background: rgba(99,102,241,0.1);  color: #6366f1; }

    .arch-action-btn {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        border: none;
        cursor: pointer;
        transition: transform 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
        color: white;
        flex-shrink: 0;
    }
    .arch-action-btn:hover  { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.18); }
    .arch-action-btn:active { transform: scale(0.92); }
    .arch-action-btn.restore { background: linear-gradient(135deg, #10b981, #059669); }
    .arch-action-btn.delete  { background: linear-gradient(135deg, #ef4444, #dc2626); }

    .arch-time-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.72rem;
        color: #111827;
        font-weight: 400;
    }
    .arch-time-chip i { font-size: 0.65rem; opacity: 0.6; }

    .arch-empty {
        padding: 3.5rem 1rem;
        text-align: center;
    }
    .arch-empty-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: var(--border-head, #f1f5f9);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.4rem;
        color: var(--text-muted, #9ca3af);
    }
    .arch-empty p {
        font-size: 0.85rem;
        font-weight: 400;
        color: var(--text-muted, #9ca3af);
        margin: 0;
    }

    .arch-modal .modal-content {
        background: var(--bg-dropdown, #fff) !important;
        border: 1px solid var(--border-light, #e5e7eb) !important;
        border-radius: 20px !important;
        overflow: hidden;
    }
    .arch-modal .modal-body { padding: 2rem 1.75rem; }
    .arch-modal .modal-icon-wrap {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        font-size: 1.5rem;
    }
    .arch-modal .modal-icon-wrap.danger  { background: rgba(239,68,68,0.1);  color: #ef4444; }
    .arch-modal .modal-icon-wrap.warning { background: rgba(245,158,11,0.1); color: #f59e0b; }
    .arch-modal h5 {
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        color: var(--text-title, #111827) !important;
        font-size: 1.05rem;
        letter-spacing: -0.01em;
        margin-bottom: 0.5rem;
    }
    .arch-modal p {
        font-size: 0.83rem;
        font-weight: 400;
        color: var(--text-muted, #6b7280) !important;
        line-height: 1.6;
    }
    .arch-modal .btn-arch-cancel {
        background: var(--border-head, #f1f5f9);
        color: var(--text-title, #374151);
        border: 1px solid var(--border-color, #e5e7eb);
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1.4rem;
        border-radius: 10px;
        transition: background 0.18s;
    }
    .arch-modal .btn-arch-cancel:hover { background: var(--border-color, #e5e7eb); }
    .arch-modal .btn-arch-delete {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border: none;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1.4rem;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(239,68,68,0.3);
        transition: opacity 0.18s, transform 0.18s;
    }
    .arch-modal .btn-arch-delete:hover { opacity: 0.9; transform: translateY(-1px); }
    .arch-modal .btn-arch-ok {
        background: var(--border-head, #f1f5f9);
        color: var(--text-title, #374151);
        border: 1px solid var(--border-color, #e5e7eb);
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1.8rem;
        border-radius: 10px;
    }

    @media (max-width: 768px) {
        .arch-wrap { padding: 1.25rem 1rem 2rem; }
        .arch-table-inner,
        .arch-tbl-head,
        .arch-swipe-content { min-width: 570px; }
    }

    [data-theme="dark"] .arch-swipe-content:hover { background: rgba(255,255,255,0.03); }
</style>

<div class="arch-wrap">

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show rounded-3 mb-4" style="font-size:0.85rem;">
            <i class="fas fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show rounded-3 mb-4" style="font-size:0.85rem;">
            <i class="fas fa-circle-xmark me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ARCHIVED DESTINATIONS --}}
    <div class="arch-section-header">
        <div class="arch-section-icon red"><i class="fas fa-map-marker-alt"></i></div>
        <h5 class="arch-section-title">Archived Destinations</h5>
        <span class="arch-section-count">{{ $archivedDestinations->count() }} item{{ $archivedDestinations->count() != 1 ? 's' : '' }}</span>
    </div>

    <div class="arch-card">
        <div class="arch-table-outer">
            <div class="arch-table-inner">

                <div class="arch-tbl-head">
                    <div class="arch-tbl-head-cell" style="flex:1.8; min-width:130px;">Country</div>
                    <div class="arch-tbl-head-cell" style="flex:1.2; min-width:100px;">Packages</div>
                    <div class="arch-tbl-head-cell" style="flex:1.5; min-width:130px;">Archived</div>
                </div>

                @forelse($archivedDestinations as $dest)
                @php
                    $pkgTotal = \App\Models\Package::withTrashed()->where('destination_id', $dest->id)->count();
                @endphp
                <div class="arch-swipe-container">
                    <div class="arch-swipe-content">

                        <div class="arch-row-cell" style="flex:1.8; min-width:130px;">
                            <span style="font-size:0.84rem; font-weight:400; color:#111827;">{{ $dest->country }}</span>
                        </div>

                        <div class="arch-row-cell" style="flex:1.2; min-width:100px;">
                            <span class="arch-badge {{ $pkgTotal > 0 ? 'warning' : 'muted' }}">
                                {{ $pkgTotal }}
                            </span>
                        </div>

                        <div class="arch-row-cell" style="flex:1.5; min-width:130px;">
                            <span class="arch-time-chip">
                                <i class="fas fa-clock"></i>
                                {{ $dest->deleted_at->diffForHumans() }}
                            </span>
                        </div>

                    </div>
                    <div class="arch-swipe-actions">

                        <form action="{{ route('admin.archive.destinations.restore', $dest->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="arch-action-btn restore" title="Restore">
                                <i class="fa fa-rotate-left"></i>
                            </button>
                        </form>

                        <button type="button"
                            class="arch-action-btn delete btn-dest-force-delete"
                            data-name="{{ $dest->name }}"
                            data-check-url="{{ route('admin.archive.destinations.checkDeletable', $dest->id) }}"
                            data-delete-url="{{ route('admin.archive.destinations.force-delete', $dest->id) }}"
                            title="Delete Permanently">
                            <i class="fa fa-trash"></i>
                        </button>

                    </div>
                </div>
                @empty
                <div class="arch-empty">
                    <div class="arch-empty-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <p>No archived destinations</p>
                </div>
                @endforelse

            </div>
        </div>
    </div>

    {{-- ARCHIVED PACKAGES --}}
    <div class="arch-section-header">
        <div class="arch-section-icon amber"><i class="fas fa-box-archive"></i></div>
        <h5 class="arch-section-title">Archived Packages</h5>
        <span class="arch-section-count">{{ $archivedPackages->count() }} item{{ $archivedPackages->count() != 1 ? 's' : '' }}</span>
    </div>

    <div class="arch-card">
        <div class="arch-table-outer">
            <div class="arch-table-inner">

                <div class="arch-tbl-head">
                    <div class="arch-tbl-head-cell" style="flex:2.2; min-width:160px; text-align:left;">Package</div>
                    <div class="arch-tbl-head-cell" style="flex:1.8; min-width:120px;">Destination</div>
                    <div class="arch-tbl-head-cell" style="flex:1.2; min-width:90px;">Price</div>
                    <div class="arch-tbl-head-cell" style="flex:1.5; min-width:130px;">Archived</div>
                </div>

                @forelse($archivedPackages as $package)
                @php
                    $pkgBookingCount = \App\Models\Booking::where('package_id', $package->id)->count();

                    $price = $package->price_per_person;
                    if      ($price >= 1_000_000_000_000) $priceFormatted = number_format($price / 1_000_000_000_000, 1) . 'T';
                    elseif  ($price >= 1_000_000_000)     $priceFormatted = number_format($price / 1_000_000_000, 1)     . 'B';
                    elseif  ($price >= 1_000_000)         $priceFormatted = number_format($price / 1_000_000, 1)         . 'M';
                    elseif  ($price >= 1_000)             $priceFormatted = number_format($price / 1_000, 1)             . 'K';
                    else                                  $priceFormatted = number_format($price, 0);
                @endphp
                <div class="arch-swipe-container">
                    <div class="arch-swipe-content">

                        <div class="arch-row-cell name-cell" style="flex:2.2; min-width:160px;">
                            <div style="overflow:hidden;">
                                <div style="font-size:0.82rem; font-weight:400; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:140px;">
                                    {{ $package->name }}
                                </div>
                            </div>
                        </div>

                        <div class="arch-row-cell" style="flex:1.8; min-width:120px; flex-direction:column; gap:3px;">
                            @if($package->destination)
                                <span style="font-size:0.78rem; font-weight:400; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:110px; display:block; text-align:center;">
                                    {{ $package->destination->name }}
                                </span>
                                @if($package->destination->trashed())
                                    <span class="arch-badge dest-archived" style="font-size:0.6rem;">
                                        <i class="fas fa-archive" style="font-size:0.55rem;"></i> archived
                                    </span>
                                @endif
                            @else
                                <span style="font-size:0.78rem; font-weight:400; color:#111827;">N/A</span>
                            @endif
                        </div>

                        <div class="arch-row-cell" style="flex:1.2; min-width:90px;">
                            <span style="font-size:0.82rem; font-weight:400; color:#111827;">
                                {{ $package->currency }} {{ $priceFormatted }}
                            </span>
                        </div>

                        <div class="arch-row-cell" style="flex:1.5; min-width:130px;">
                            <span class="arch-time-chip">
                                <i class="fas fa-clock"></i>
                                {{ $package->deleted_at->diffForHumans() }}
                            </span>
                        </div>

                    </div>
                    <div class="arch-swipe-actions">

                        <form action="{{ route('admin.archive.packages.restore', $package->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="arch-action-btn restore"
                                title="Restore Package"
                                @if($package->destination && $package->destination->trashed())
                                    onclick="event.preventDefault(); alert('Cannot restore — its destination is still archived. Restore the destination first.');"
                                @endif>
                                <i class="fa fa-rotate-left"></i>
                            </button>
                        </form>

                        <button type="button"
                            class="arch-action-btn delete btn-pkg-force-delete"
                            data-name="{{ $package->name }}"
                            data-delete-url="{{ route('admin.archive.packages.force-delete', $package->id) }}"
                            data-booking-count="{{ $pkgBookingCount }}"
                            title="Delete Permanently">
                            <i class="fa fa-trash"></i>
                        </button>

                    </div>
                </div>
                @empty
                <div class="arch-empty">
                    <div class="arch-empty-icon"><i class="fas fa-box-open"></i></div>
                    <p>No archived packages</p>
                </div>
                @endforelse

            </div>
        </div>
    </div>

</div>

{{-- DESTINATION — Force Delete Modal --}}
<div class="modal fade arch-modal" id="destForceDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center">

                <div id="dest_fd_loading">
                    <div class="spinner-border text-danger mb-3" role="status" style="width:2rem;height:2rem;"></div>
                    <p style="font-size:0.82rem; font-weight:400; color:var(--text-muted);">Checking booking records…</p>
                </div>

                <div id="dest_fd_confirm" class="d-none">
                    <div class="modal-icon-wrap danger mx-auto"><i class="fas fa-trash"></i></div>
                    <h5>Delete Permanently?</h5>
                    <p>This will permanently remove <strong id="dest_fd_name"></strong> and all its packages. This cannot be undone.</p>
                    <form id="destForceDeleteForm" method="POST">
                        @csrf @method('DELETE')
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button type="button" class="btn btn-arch-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-arch-delete">Yes, Delete</button>
                        </div>
                    </form>
                </div>

                <div id="dest_fd_blocked" class="d-none">
                    <div class="modal-icon-wrap warning mx-auto"><i class="fas fa-ban"></i></div>
                    <h5>Cannot Delete</h5>
                    <p>
                        <strong id="dest_fd_name_blocked"></strong> has
                        <strong id="dest_fd_booking_count" class="text-danger"></strong>
                        booking record(s). Booking history must be preserved.
                    </p>
                    <button type="button" class="btn btn-arch-ok mt-2" data-bs-dismiss="modal">Got It</button>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- PACKAGE — Force Delete Modal --}}
<div class="modal fade arch-modal" id="pkgForceDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center">

                <div id="pkg_fd_confirm">
                    <div class="modal-icon-wrap danger mx-auto"><i class="fas fa-trash"></i></div>
                    <h5>Delete Permanently?</h5>
                    <p>You are about to permanently delete <strong id="pkg_fd_name"></strong>. This <strong>cannot be undone</strong>.</p>
                    <form id="pkgForceDeleteForm" method="POST">
                        @csrf @method('DELETE')
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button type="button" class="btn btn-arch-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-arch-delete">Yes, Delete</button>
                        </div>
                    </form>
                </div>

                <div id="pkg_fd_blocked" class="d-none">
                    <div class="modal-icon-wrap warning mx-auto"><i class="fas fa-ban"></i></div>
                    <h5>Cannot Delete</h5>
                    <p>
                        <strong id="pkg_fd_name_blocked"></strong> has
                        <strong id="pkg_fd_booking_count" class="text-danger"></strong>
                        booking record(s). Booking history must be preserved.
                    </p>
                    <button type="button" class="btn btn-arch-ok mt-2" data-bs-dismiss="modal">Got It</button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function (e) {

        if (e.target.closest('.btn-dest-force-delete')) {
            const btn       = e.target.closest('.btn-dest-force-delete');
            const name      = btn.dataset.name;
            const checkUrl  = btn.dataset.checkUrl;
            const deleteUrl = btn.dataset.deleteUrl;

            document.getElementById('dest_fd_loading').classList.remove('d-none');
            document.getElementById('dest_fd_confirm').classList.add('d-none');
            document.getElementById('dest_fd_blocked').classList.add('d-none');

            const modal = new bootstrap.Modal(document.getElementById('destForceDeleteModal'));
            modal.show();

            fetch(checkUrl)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('dest_fd_loading').classList.add('d-none');
                    if (data.can_delete) {
                        document.getElementById('dest_fd_name').innerText     = name;
                        document.getElementById('destForceDeleteForm').action  = deleteUrl;
                        document.getElementById('dest_fd_confirm').classList.remove('d-none');
                    } else {
                        document.getElementById('dest_fd_name_blocked').innerText  = name;
                        document.getElementById('dest_fd_booking_count').innerText = data.booking_count;
                        document.getElementById('dest_fd_blocked').classList.remove('d-none');
                    }
                })
                .catch(() => {
                    document.getElementById('dest_fd_loading').classList.add('d-none');
                    document.getElementById('dest_fd_name_blocked').innerText  = name;
                    document.getElementById('dest_fd_booking_count').innerText = '(unknown)';
                    document.getElementById('dest_fd_blocked').classList.remove('d-none');
                });
            return;
        }

        if (e.target.closest('.btn-pkg-force-delete')) {
            const btn          = e.target.closest('.btn-pkg-force-delete');
            const name         = btn.dataset.name;
            const deleteUrl    = btn.dataset.deleteUrl;
            const bookingCount = parseInt(btn.dataset.bookingCount || '0', 10);

            document.getElementById('pkg_fd_confirm').classList.add('d-none');
            document.getElementById('pkg_fd_blocked').classList.add('d-none');

            if (bookingCount > 0) {
                document.getElementById('pkg_fd_name_blocked').innerText  = name;
                document.getElementById('pkg_fd_booking_count').innerText = bookingCount;
                document.getElementById('pkg_fd_blocked').classList.remove('d-none');
            } else {
                document.getElementById('pkg_fd_name').innerText     = name;
                document.getElementById('pkgForceDeleteForm').action  = deleteUrl;
                document.getElementById('pkg_fd_confirm').classList.remove('d-none');
            }

            new bootstrap.Modal(document.getElementById('pkgForceDeleteModal')).show();
        }
    });
});
</script>
@endsection