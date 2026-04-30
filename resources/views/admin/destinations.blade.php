@extends('layouts.admin')

@section('content')
<style>
    .dest-wrap .page-title {
        color: var(--text-title);
        transition: color 0.3s ease;
    }

    .dest-wrap .card {
        background: var(--bg-navbar);
        border: 1px solid var(--border-color) !important;
        border-radius: 15px;
        overflow: visible;
        transition: background 0.3s ease, border-color 0.3s ease;
    }

    .dest-wrap .tbl-head-cell {
        color: var(--text-muted);
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-weight: 700;
    }

    .dest-wrap .tbl-head {
        background-color: var(--border-head);
        border-bottom: 2px solid var(--border-color);
        transition: background-color 0.3s ease, border-color 0.3s ease;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .dest-wrap .tbl-head th {
        color: var(--text-muted);
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-weight: 700;
        background-color: var(--border-head) !important;
        --bs-table-bg: transparent !important;
    }

    .dest-wrap .table > :not(caption) > * > *,
    .dest-wrap .table tbody tr td {
        background-color: var(--bg-navbar) !important;
        color: var(--text-primary) !important;
        border-color: var(--border-item) !important;
        --bs-table-bg: var(--bg-navbar) !important;
        --bs-table-color: var(--text-primary) !important;
        transition: background-color 0.15s ease, color 0.3s ease, border-color 0.3s ease;
    }

    .dest-wrap .table-hover tbody tr:hover > * {
        background-color: var(--notif-hover) !important;
    }

    .dest-wrap .swipe-container {
        display: flex;
        overflow-x: auto;
        overflow-y: hidden;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -ms-overflow-style: none;
        background-color: var(--bg-navbar);
        transition: background-color 0.3s ease;
    }
    .dest-wrap .swipe-container::-webkit-scrollbar { display: none; }

    .dest-wrap .swipe-content {
        min-width: 100%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        padding: 1rem 0;
        scroll-snap-align: start;
        border-bottom: 1px solid var(--border-item);
        transition: border-color 0.3s ease;
    }

    .dest-wrap .swipe-actions {
        display: flex;
        flex-shrink: 0;
        scroll-snap-align: end;
        align-items: center;
        padding: 0 1rem;
        background-color: var(--border-head);
        border-left: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-item);
        gap: 6px;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .dest-wrap .action-btn {
        width: 40px; height: 40px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 4px; color: white !important;
        text-decoration: none; border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        transition: transform 0.2s ease;
        cursor: pointer;
    }
    .dest-wrap .action-btn:active { transform: scale(0.9); }

    .dest-wrap .table-outer {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 600px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .dest-wrap .table-outer::-webkit-scrollbar { display: none; }

    .dest-wrap .table-clip {
        border-radius: 15px;
        overflow: hidden;
    }

    .dest-wrap .cell-text {
        color: var(--text-title);
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .dest-wrap .empty-state {
        color: var(--text-time);
        font-size: 0.88rem;
        transition: color 0.3s ease;
    }

    .dest-wrap .table-scroll-strip {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        height: 12px;
        margin-top: 8px;
        border-radius: 10px;
        scrollbar-width: thin;
        scrollbar-color: var(--text-muted) var(--border-head);
        cursor: grab;
    }
    .dest-wrap .table-scroll-strip:active { cursor: grabbing; }
    .dest-wrap .table-scroll-strip::-webkit-scrollbar { height: 12px; }
    .dest-wrap .table-scroll-strip::-webkit-scrollbar-track {
        background: var(--border-head);
        border-radius: 10px;
    }
    .dest-wrap .table-scroll-strip::-webkit-scrollbar-thumb {
        background: var(--text-muted);
        border-radius: 10px;
    }
    .dest-wrap .table-scroll-strip-inner { height: 1px; }

    .dest-wrap-modal .modal-content {
        background: var(--bg-dropdown) !important;
        border: 1px solid var(--border-light) !important;
        color: var(--text-primary) !important;
        transition: background 0.3s ease, border-color 0.3s ease;
    }
    .dest-wrap-modal .modal-title,
    .dest-wrap-modal .form-label,
    .dest-wrap-modal h5 {
        color: var(--text-title) !important;
    }
    .dest-wrap-modal .form-control,
    .dest-wrap-modal .form-select {
        background: var(--bg-body) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    .dest-wrap-modal .form-control::placeholder { color: var(--text-time) !important; }
    .dest-wrap-modal .btn-light {
        background: var(--btn-bg) !important;
        border-color: var(--btn-border) !important;
        color: var(--btn-color) !important;
    }
    .dest-wrap-modal p.text-muted { color: var(--text-muted) !important; }

    .archive-warning-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 0.82rem;
        color: #92400e;
        margin: 12px 0 0;
        display: flex;
        gap: 8px;
        align-items: flex-start;
        line-height: 1.5;
    }
    [data-theme="dark"] .archive-warning-box {
        background: rgba(251,191,36,0.1);
        border-color: rgba(251,191,36,0.3);
        color: #fcd34d;
    }
    .archive-warning-box i { margin-top: 2px; flex-shrink: 0; }

    /* ── MOBILE: full-width horizontal scroll + swipe-to-reveal actions ── */
    @media (max-width: 768px) {

        .dest-wrap .table-outer {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }

        .dest-wrap .table-outer > div {
            min-width: 440px;
        }

        .dest-wrap .tbl-head {
            min-width: 440px;
        }

        .dest-wrap .swipe-container {
            min-width: 440px;
        }

        .dest-wrap .swipe-content {
            min-width: 440px !important;
        }

        /* Show scroll strip on mobile */
        .dest-wrap .table-scroll-strip {
            display: block !important;
        }
    }
</style>

<div class="dest-wrap container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold page-title">Destinations</h2>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addDestModal">
            <i class="fa fa-plus me-2"></i>Add Destination
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger shadow-sm alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <div class="table-clip">
                <div class="table-outer" id="tableOuter">
                    <div style="width:100%;">

                        {{-- FLEX HEADER --}}
                        <div class="tbl-head d-flex align-items-center py-3">
                            <div style="flex:1; min-width:80px;" class="text-center tbl-head-cell">Popular</div>
                            <div style="flex:2; min-width:140px;" class="text-center tbl-head-cell">Country</div>
                            <div style="flex:1.5; min-width:100px;" class="text-center tbl-head-cell">Status</div>
                        </div>

                        {{-- ROWS --}}
                        @forelse($destinations as $dest)
                        <div class="swipe-container">
                            <div class="swipe-content">

                                {{-- Popular --}}
                                <div style="flex:1; min-width:80px;" class="text-center">
                                    <form action="{{ route('destinations.togglePopular', $dest->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn p-0 border-0 shadow-none">
                                            <i class="{{ $dest->is_popular ? 'fas fa-star text-warning' : 'far fa-star' }}"
                                               style="font-size:1.1rem; {{ $dest->is_popular ? '' : 'color:var(--text-muted);' }}"></i>
                                        </button>
                                    </form>
                                </div>

                                {{-- Country --}}
                                <div style="flex:2; min-width:140px;" class="text-center cell-text">
                                    {{ $dest->country }}
                                </div>

                                {{-- Status --}}
                                <div style="flex:1.5; min-width:100px;" class="text-center">
                                    <form action="{{ route('destinations.toggle', $dest->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn fw-bold text-white btn-sm px-3 shadow-sm"
                                            style="background-color:{{ $dest->status == 'active' ? '#00b300' : '#888' }}; border-radius:50px; font-size:0.75rem;">
                                            {{ strtoupper($dest->status) }}
                                        </button>
                                    </form>
                                </div>

                            </div>

                            <div class="swipe-actions">
                                <button type="button" class="action-btn bg-primary btn-edit"
                                    data-url="{{ route('destinations.update', $dest->id) }}"
                                    data-name="{{ $dest->name }}"
                                    data-title="{{ $dest->title }}"
                                    data-country="{{ $dest->country }}"
                                    data-description="{{ $dest->description }}">
                                    <i class="fas fa-edit" style="font-size:0.8rem;"></i>
                                </button>
                                <button type="button" class="action-btn bg-warning btn-archive-trigger"
                                    data-check-url="{{ route('destinations.checkArchive', $dest->id) }}"
                                    data-url="{{ route('destinations.delete', $dest->id) }}"
                                    data-name="{{ $dest->name }}">
                                    <i class="fas fa-box-archive" style="font-size:0.8rem;"></i>
                                </button>
                            </div>
                        </div>
                        @empty
                            <div class="text-center py-5 empty-state">
                                <i class="fas fa-map-marked-alt mb-2 d-block" style="font-size:2rem; opacity:0.3;"></i>
                                No destinations found.
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-scroll-strip" id="tableScrollStrip">
        <div class="table-scroll-strip-inner" id="tableScrollStripInner"></div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade dest-wrap-modal" id="addDestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('destinations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Create New Destination</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Country</label>
                        <input type="text" name="country" class="form-control border-0" placeholder="e.g. France" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Title</label>
                        <input type="text" name="title" class="form-control border-0" placeholder="e.g. The City of Love">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Description</label>
                        <textarea name="description" class="form-control border-0" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Image</label>
                        <input type="file" name="image" class="form-control border-0" accept="image/*" required>
                    </div>
                    <input type="hidden" name="status" value="active">
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade dest-wrap-modal" id="editDestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Edit Destination</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Country</label>
                        <input type="text" name="country" id="edit_country" class="form-control border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Title</label>
                        <input type="text" name="title" id="edit_title" class="form-control border-0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Description</label>
                        <textarea name="description" id="edit_description" class="form-control border-0" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Image (Blank to keep current)</label>
                        <input type="file" name="image" class="form-control border-0" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">Update Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Archive Confirm Modal --}}
<div class="modal fade dest-wrap-modal" id="archiveConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
            <div class="modal-body p-4 text-center">
                <div class="text-warning mb-3"><i class="fas fa-box-archive fa-3x"></i></div>
                <h5 class="fw-bold">Archive Destination?</h5>

                <p class="text-muted small mb-0" id="archiveDefaultMsg">
                    Are you sure you want to archive
                    <span id="archive_dest_name" class="fw-bold"></span>?
                </p>

                <div id="archiveWarningBox" class="archive-warning-box text-start d-none">
                    <i class="fas fa-triangle-exclamation"></i>
                    <span>
                        This destination has active packages.
                        Archiving it will make those packages <strong>unavailable</strong>.
                        Existing bookings will not be affected. Continue?
                    </span>
                </div>

                <form id="archiveForm" action="" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-warning px-4 shadow-sm">Yes, Archive</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Edit modal ── */
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('edit_title').value       = this.dataset.title || '';
            document.getElementById('edit_country').value     = this.dataset.country;
            document.getElementById('edit_description').value = this.dataset.description;
            document.getElementById('editForm').action        = this.dataset.url;
            new bootstrap.Modal(document.getElementById('editDestModal')).show();
        });
    });

    /* ── Archive modal — with AJAX pre-check ── */
    document.querySelectorAll('.btn-archive-trigger').forEach(button => {
        button.addEventListener('click', async function () {
            const name       = this.dataset.name;
            const archiveUrl = this.dataset.url;
            const checkUrl   = this.dataset.checkUrl;

            const form = document.getElementById('archiveForm');

            document.getElementById('archive_dest_name').innerText = name;
            document.getElementById('archiveWarningBox').classList.add('d-none');
            document.getElementById('archiveDefaultMsg').classList.remove('d-none');

            form.setAttribute('action', archiveUrl);
            form.querySelector('input[name="_method"]').value = 'DELETE';

            const modal = new bootstrap.Modal(document.getElementById('archiveConfirmModal'));
            modal.show();

            try {
                const res  = await fetch(checkUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                if (data.has_active_packages) {
                    document.getElementById('archiveDefaultMsg').classList.add('d-none');
                    document.getElementById('archiveWarningBox').classList.remove('d-none');
                }
            } catch (e) {
                console.warn('Archive check failed, proceeding with default message.', e);
            }
        });
    });

    /* ── Scroll strip synced to tableOuter (desktop only) ── */
    const outer      = document.getElementById('tableOuter');
    const strip      = document.getElementById('tableScrollStrip');
    const stripInner = document.getElementById('tableScrollStripInner');

    function syncStripWidth() {
        stripInner.style.width = outer.scrollWidth + 'px';
    }
    syncStripWidth();
    new ResizeObserver(syncStripWidth).observe(outer);

    let fromOuter = false;
    let fromStrip = false;

    outer.addEventListener('scroll', () => {
        if (fromStrip) return;
        fromOuter = true;
        strip.scrollLeft = outer.scrollLeft;
        fromOuter = false;
    });

    strip.addEventListener('scroll', () => {
        if (fromOuter) return;
        fromStrip = true;
        outer.scrollLeft = strip.scrollLeft;
        fromStrip = false;
    });

});
</script>
@endsection