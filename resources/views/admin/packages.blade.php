@extends('layouts.admin')

@section('content')
<style>
    /* ══════════════════════════════════════
       PACKAGES — THEME-AWARE STYLES
    ══════════════════════════════════════ */

    .pkg-wrap .page-title { color: var(--text-title); transition: color 0.3s ease; }

    .pkg-wrap .card {
        background: var(--bg-navbar);
        border: 1px solid var(--border-color) !important;
        border-radius: 15px;
        overflow: visible;
        transition: background 0.3s ease, border-color 0.3s ease;
    }

    .pkg-wrap .tbl-head {
        background-color: var(--border-head);
        border-bottom: 2px solid var(--border-color);
        transition: background-color 0.3s ease, border-color 0.3s ease;
        position: sticky; top: 0; z-index: 10;
    }

    .pkg-wrap .tbl-head-cell {
        color: var(--text-muted);
        font-size: 0.72rem; letter-spacing: 0.08em;
        text-transform: uppercase; font-weight: 700;
    }

    .pkg-wrap .swipe-container {
        display: flex; overflow-x: auto; overflow-y: hidden;
        scroll-snap-type: x mandatory;
        scrollbar-width: none; -ms-overflow-style: none;
        background-color: var(--bg-navbar);
        transition: background-color 0.3s ease;
    }
    .pkg-wrap .swipe-container::-webkit-scrollbar { display: none; }

    .pkg-wrap .swipe-content {
        min-width: 100%; width: 100%; flex-shrink: 0;
        display: flex; align-items: center;
        padding: 0.85rem 0; scroll-snap-align: start;
        border-bottom: 1px solid var(--border-item);
        transition: border-color 0.3s ease;
    }

    .pkg-wrap .swipe-actions {
        display: flex; flex-shrink: 0; scroll-snap-align: end;
        align-items: center; padding: 0 0.75rem;
        background-color: var(--border-head);
        border-left: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-item);
        gap: 6px;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .pkg-wrap .action-btn {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 6px; color: white !important;
        text-decoration: none; border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        transition: transform 0.2s ease;
    }
    .pkg-wrap .action-btn:active { transform: scale(0.9); }

    .pkg-wrap .pkg-col {
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; font-family: inherit;
        overflow: hidden; text-align: center;
        color: var(--text-title); transition: color 0.3s ease;
    }

    .pkg-wrap .image-preview-box {
        width: 80px; height: 80px;
        background: var(--bg-body); border-radius: 8px;
        overflow: hidden; flex-shrink: 0;
        transition: background 0.3s ease;
    }

    .pkg-wrap .table-outer {
        overflow-x: auto; overflow-y: auto; max-height: 600px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none; -ms-overflow-style: none;
    }
    .pkg-wrap .table-outer::-webkit-scrollbar { display: none; }
    .pkg-wrap .table-clip { border-radius: 15px; overflow: hidden; }

    .itinerary-item, .list-item { animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
    .pkg-wrap .empty-state { color: var(--text-time); font-size: 0.88rem; transition: color 0.3s ease; }

    /* ══════════════════════════════════════
       CUSTOM TRANSPORT PICKER
    ══════════════════════════════════════ */
    .transport-wrapper { position: relative; flex-shrink: 0; }
    .transport-wrapper select { position: absolute; opacity: 0; pointer-events: none; width: 0; height: 0; }
    .transport-trigger {
        display: flex; align-items: center; justify-content: center;
        width: 46px; height: 38px;
        background: var(--bg-body) !important;
        border: 1px solid var(--border-color) !important;
        border-left: none !important; border-right: none !important;
        cursor: pointer; font-size: 1.15rem; line-height: 1;
        user-select: none; transition: background 0.15s ease;
    }
    .transport-trigger:hover { background: var(--notif-hover) !important; }

    .transport-dropdown {
        display: none; position: absolute; top: calc(100% + 4px); left: 0;
        z-index: 9999; background: var(--bg-dropdown, #fff);
        border: 1px solid var(--border-color); border-radius: 10px;
        box-shadow: 0 8px 28px rgba(0,0,0,0.18); overflow: hidden; min-width: 170px;
    }
    .transport-dropdown.open { display: block; }

    .transport-option {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 14px; cursor: pointer; font-size: 0.84rem;
        color: var(--text-primary); transition: background 0.12s ease; white-space: nowrap;
    }
    .transport-option:hover { background: var(--notif-hover); }
    .transport-option.selected { background: var(--border-head); font-weight: 600; }
    .transport-option .t-icon { font-size: 1.05rem; width: 22px; text-align: center; }
    .transport-option .t-label { color: var(--text-title); }

    /* ══════════════════════════════════════
       MODALS — THEME-AWARE
    ══════════════════════════════════════ */
    .pkg-modal .modal-content {
        background: var(--bg-dropdown) !important;
        border: 1px solid var(--border-light) !important;
        color: var(--text-primary) !important;
        transition: background 0.3s ease;
    }
    .pkg-modal .modal-header {
        background: var(--border-head) !important;
        border-bottom: 1px solid var(--border-color) !important;
        transition: background 0.3s ease, border-color 0.3s ease;
    }
    .pkg-modal .modal-footer {
        background: var(--border-head) !important;
        border-top: 1px solid var(--border-color) !important;
        transition: background 0.3s ease, border-color 0.3s ease;
    }
    .pkg-modal .modal-title, .pkg-modal h5, .pkg-modal label { color: var(--text-title) !important; }
    .pkg-modal .form-control, .pkg-modal .form-select {
        background: var(--bg-body) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
        transition: background 0.3s ease, border-color 0.3s ease, color 0.3s ease;
    }
    .pkg-modal .form-control::placeholder { color: var(--text-time) !important; }
    .pkg-modal .input-group-text {
        background: var(--border-head) !important;
        border-color: var(--border-color) !important;
        color: var(--text-muted) !important;
        transition: background 0.3s ease;
    }
    .pkg-modal .btn-light, .pkg-modal .btn-close {
        background: var(--btn-bg) !important;
        border-color: var(--btn-border) !important;
        color: var(--btn-color) !important;
    }
    .pkg-modal p.text-muted { color: var(--text-muted) !important; }
    .pkg-modal .image-preview-box {
        background: var(--bg-body) !important;
        border-color: var(--border-color) !important;
    }
    .pkg-modal .duration-display {
        background: var(--bg-body) !important;
        border-color: var(--border-color) !important;
        color: #6f42c1 !important;
    }

    /* ══════════════════════════════════════
       MOBILE: horizontal scroll
    ══════════════════════════════════════ */
    @media (max-width: 768px) {
        .pkg-wrap .table-outer { overflow-x: auto !important; -webkit-overflow-scrolling: touch !important; }
        .pkg-wrap .table-outer > div { min-width: 640px; }
        .pkg-wrap .tbl-head        { min-width: 640px; }
        .pkg-wrap .swipe-container { min-width: 640px; }
        .pkg-wrap .swipe-content   { min-width: 640px !important; }
    }

    /* ══════════════════════════════════════
       MODAL MOBILE RESPONSIVENESS
    ══════════════════════════════════════ */
    @media (max-width: 576px) {
        .pkg-modal .modal-dialog {
            margin: 0 !important; max-width: 100% !important;
            width: 100% !important; min-height: 100% !important;
            align-items: flex-start !important;
        }
        .pkg-modal .modal-content {
            border-radius: 0 !important; min-height: 100vh !important;
            display: flex; flex-direction: column;
        }
        .pkg-modal .modal-body {
            flex: 1 1 auto !important; overflow-y: auto !important;
            -webkit-overflow-scrolling: touch; padding: 12px 14px !important;
        }
        .pkg-modal .modal-header,
        .pkg-modal .modal-footer { flex-shrink: 0; padding: 12px 14px !important; }
        .pkg-modal .modal-header { flex-wrap: wrap; gap: 4px; }
        .pkg-modal .duration-display { font-size: 0.7rem; }
        .pkg-modal .col-6 { flex: 0 0 50% !important; max-width: 50% !important; }
        .pkg-modal .col-12.col-md-4 { flex: 0 0 100% !important; max-width: 100% !important; }
        .pkg-modal .input-group .input-group-text { font-size: 0.72rem; padding: 4px 7px; white-space: nowrap; }
        .pkg-modal .transport-trigger { width: 34px !important; font-size: 0.95rem !important; }
        .pkg-modal #add_imagePreview,
        .pkg-modal #edit_imagePreview { width: 52px !important; height: 52px !important; min-width: 52px; }
    }
</style>

<div class="pkg-wrap container-fluid py-3 py-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="fw-bold page-title">Tour Packages</h2>
        <button class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addPackageModal" style="border-radius:8px;">
            <i class="fa fa-plus me-2"></i> Add Package
        </button>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-clip">
                <div class="table-outer" id="tableOuter">
                    <div style="width:100%;">

                        {{-- FLEX HEADER --}}
                        <div class="tbl-head d-flex align-items-center py-3">
                            <div style="flex:1; min-width:70px;"  class="text-center tbl-head-cell">Favorite</div>
                            <div style="flex:2; min-width:130px;" class="text-center tbl-head-cell">Package</div>
                            <div style="flex:2; min-width:120px;" class="text-center tbl-head-cell">Destination</div>
                            <div style="flex:1.5; min-width:110px;" class="text-center tbl-head-cell">Price</div>
                            <div style="flex:1.5; min-width:100px;" class="text-center tbl-head-cell">Duration</div>
                            <div style="flex:1.5; min-width:100px;" class="text-center tbl-head-cell">Status</div>
                        </div>

                        {{-- ROWS --}}
                        @forelse($packages as $package)
                        <div class="swipe-container">
                            <div class="swipe-content">

                                {{-- FAVORITE --}}
                                <div class="pkg-col" style="flex:1; min-width:70px;">
                                    <form action="{{ route('packages.toggleFeatured', $package->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn p-0 border-0 shadow-none">
                                            <i class="{{ $package->is_featured ? 'fas fa-star text-warning' : 'far fa-star' }}"
                                               style="{{ $package->is_featured ? '' : 'color: var(--text-muted);' }} font-size:1.1rem;"></i>
                                        </button>
                                    </form>
                                </div>

                                {{-- PACKAGE NAME --}}
                                <div class="pkg-col fw-bold" style="flex:2; min-width:130px;">
                                    <span title="{{ $package->name }}">{{ $package->name }}</span>
                                </div>

                                {{-- DESTINATION --}}
                                <div class="pkg-col" style="flex:2; min-width:120px;">
                                    {{ $package->destination->name ?? 'N/A' }}
                                </div>

                                {{-- PRICE --}}
                                <div class="pkg-col" style="flex:1.5; min-width:110px;">
                                    {{ $package->currency }} {{ number_format($package->price_per_person, 0) }}
                                </div>

                                {{-- DURATION --}}
                                <div class="pkg-col" style="flex:1.5; min-width:100px;">
                                    {{ $package->duration_days }} D / {{ $package->duration_days > 0 ? $package->duration_days - 1 : 0 }} N
                                </div>

                                {{-- STATUS --}}
                                <div class="pkg-col" style="flex:1.5; min-width:100px;">
                                    <form action="{{ route('packages.toggle', $package->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn fw-bold text-white border-0 py-1 px-3 shadow-sm"
                                            style="background-color:{{ $package->status == 'active' ? '#00b300' : '#888' }}; border-radius:50px; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.03em; white-space:nowrap;">
                                            {{ $package->status }}
                                        </button>
                                    </form>
                                </div>

                            </div>

                            {{-- SWIPE ACTIONS --}}
                            <div class="swipe-actions">
                                {{-- Edit --}}
                                <button type="button" class="action-btn bg-primary edit-package-btn"
                                    data-id="{{ $package->id }}" title="Edit">
                                    <i class="fa fa-edit" style="font-size:0.8rem;"></i>
                                </button>

                                {{-- Archive --}}
                                <button type="button" class="action-btn bg-warning btn-archive-trigger"
                                    data-url="{{ route('packages.destroy', $package->id) }}"
                                    data-name="{{ $package->name }}"
                                    title="Archive">
                                    <i class="fas fa-box-archive" style="font-size:0.8rem;"></i>
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5 empty-state">
                            <i class="fas fa-box-open mb-2 d-block" style="font-size:2rem; opacity:0.3;"></i>
                            No tour packages found.
                        </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ Add Package Modal ══ --}}
<div class="modal fade pkg-modal" id="addPackageModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form action="{{ route('packages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="fw-bold mb-0">Create New Package</h5>
                    <span class="ms-2 badge border duration-display">1 Day / 0 Nights</span>
                    <button class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-5"><label class="small fw-bold">Package Name</label><input type="text" name="name" class="form-control" placeholder="e.g. Boracay Getaway" required></div>
                        <div class="col-6 col-md-3"><label class="small fw-bold">Language</label><input type="text" name="language" class="form-control" value="English"></div>
                        <div class="col-6 col-md-2"><label class="small fw-bold">Rating (1-5)</label><input type="number" name="rating" class="form-control" min="1" max="5" value="5"></div>
                        <div class="col-6 col-md-2"><label class="small fw-bold">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                        <div class="col-6 col-md-3"><label class="small fw-bold">Destination</label><select name="destination_id" class="form-select" required>@foreach($destinations as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div>
                        <div class="col-6 col-md-3"><label class="small fw-bold">Price per Person</label><input type="number" name="price_per_person" class="form-control" required></div>
                        <div class="col-6 col-md-2"><label class="small fw-bold">Currency</label><input type="text" name="currency" class="form-control" value="PHP"></div>
                        <div class="col-6 col-md-2"><label class="small fw-bold">Max Group</label><input type="number" name="max_group_size" class="form-control" value="10"></div>
                        <div class="col-12"><label class="small fw-bold">Description</label><textarea name="description" class="form-control" rows="2" required></textarea></div>

                        {{-- ITINERARY --}}
                        <div class="col-12 col-md-4">
                            <label class="fw-bold small text-primary">ITINERARY</label>
                            <div class="itinerary_container">
                                <div class="input-group mb-2 itinerary-item">
                                    <span class="input-group-text small">Day 1</span>
                                    <div class="transport-wrapper" data-selected="van">
                                        <select name="transport[]"></select>
                                        <div class="transport-trigger" title="Pick transport">🚐</div>
                                        <div class="transport-dropdown"></div>
                                    </div>
                                    <input type="text" name="itinerary[]" class="form-control" placeholder="Activity description" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary add-day-btn mt-1"><i class="fa fa-plus"></i> Add Day</button>
                        </div>

                        {{-- INCLUSIONS --}}
                        <div class="col-12 col-md-4">
                            <label class="fw-bold small text-success">INCLUSIONS</label>
                            <div class="inclusions_container">
                                <div class="input-group mb-2 list-item">
                                    <input type="text" name="inclusions[]" class="form-control" placeholder="e.g. Free Breakfast">
                                    <button type="button" class="btn btn-outline-danger remove-item"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-success add-inclusion-btn mt-1"><i class="fa fa-plus"></i> Add</button>
                        </div>

                        {{-- EXCLUSIONS --}}
                        <div class="col-12 col-md-4">
                            <label class="fw-bold small text-danger">EXCLUSIONS</label>
                            <div class="exclusions_container">
                                <div class="input-group mb-2 list-item">
                                    <input type="text" name="exclusions[]" class="form-control" placeholder="e.g. Airfare">
                                    <button type="button" class="btn btn-outline-danger remove-item"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger add-exclusion-btn mt-1"><i class="fa fa-plus"></i> Add</button>
                        </div>

                        <div class="col-12 mt-2">
                            <label class="small fw-bold">Package Image</label>
                            <div class="d-flex align-items-center gap-3">
                                <div id="add_imagePreview" class="image-preview-box border d-flex align-items-center justify-content-center">
                                    <i class="fa fa-image" style="color:var(--text-muted);"></i>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('add_image_input').click()">Upload File</button>
                                <input type="file" name="image" id="add_image_input" class="d-none" accept="image/*" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary px-5">Save Package</button></div>
            </div>
        </form>
    </div>
</div>

{{-- ══ Edit Package Modal ══ --}}
<div class="modal fade pkg-modal" id="editPackageModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form id="editPackageForm" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="fw-bold mb-0">Edit Package</h5>
                    <span class="ms-2 badge border duration-display"></span>
                    <button class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4"><label class="small fw-bold">Name</label><input type="text" id="edit_name" name="name" class="form-control" required></div>
                        <div class="col-6 col-md-2"><label class="small fw-bold">Language</label><input type="text" id="edit_language" name="language" class="form-control"></div>
                        <div class="col-6 col-md-2"><label class="small fw-bold">Rating</label><input type="number" id="edit_rating" name="rating" class="form-control" min="1" max="5"></div>
                        <div class="col-6 col-md-2"><label class="small fw-bold">Status</label><select id="edit_status" name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                        <div class="col-6 col-md-2"><label class="small fw-bold">Max Group</label><input type="number" id="edit_max_group_size" name="max_group_size" class="form-control"></div>
                        <div class="col-6 col-md-3"><label class="small fw-bold">Destination</label><select id="edit_destination_id" name="destination_id" class="form-select" required>@foreach($destinations as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div>
                        <div class="col-6 col-md-3"><label class="small fw-bold">Price/Person</label><input type="number" id="edit_price_per_person" name="price_per_person" class="form-control" required></div>
                        <div class="col-6 col-md-3"><label class="small fw-bold">Currency</label><input type="text" id="edit_currency" name="currency" class="form-control"></div>
                        <div class="col-12"><label class="small fw-bold">Description</label><textarea id="edit_description" name="description" class="form-control" rows="2" required></textarea></div>

                        {{-- ITINERARY --}}
                        <div class="col-12 col-md-4">
                            <label class="fw-bold small text-primary">ITINERARY</label>
                            <p class="text-muted" style="font-size:0.72rem;margin-bottom:6px;">Tap the icon to pick a transport mode per day.</p>
                            <div id="edit_itinerary_container"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary add-day-btn mt-1"><i class="fa fa-plus"></i> Add Day</button>
                        </div>

                        {{-- INCLUSIONS --}}
                        <div class="col-12 col-md-4">
                            <label class="fw-bold small text-success">INCLUSIONS</label>
                            <div id="edit_inclusions_container"></div>
                            <button type="button" class="btn btn-sm btn-outline-success add-inclusion-btn mt-1"><i class="fa fa-plus"></i> Add</button>
                        </div>

                        {{-- EXCLUSIONS --}}
                        <div class="col-12 col-md-4">
                            <label class="fw-bold small text-danger">EXCLUSIONS</label>
                            <div id="edit_exclusions_container"></div>
                            <button type="button" class="btn btn-sm btn-outline-danger add-exclusion-btn mt-1"><i class="fa fa-plus"></i> Add</button>
                        </div>

                        <div class="col-12 mt-2">
                            <label class="small fw-bold">Image <span class="text-muted fw-normal">(leave blank to keep current)</span></label>
                            <div class="d-flex align-items-center gap-3">
                                <div id="edit_imagePreview" class="image-preview-box border d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:64px; height:64px; border-radius:8px; overflow:hidden;"></div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary d-block mb-1" onclick="document.getElementById('edit_image_input').click()">
                                        <i class="fa fa-upload me-1"></i> Change Image
                                    </button>
                                    <span id="edit_image_filename" class="text-muted" style="font-size:0.75rem;">No new file chosen</span>
                                </div>
                                <input type="file" name="image" id="edit_image_input" class="d-none" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary px-5">Update Package</button></div>
            </div>
        </form>
    </div>
</div>

{{-- ══ Archive Confirm Modal ══ --}}
<div class="modal fade pkg-modal" id="archiveConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
            <div class="modal-body p-4 text-center">
                <div class="text-warning mb-3"><i class="fas fa-box-archive fa-3x"></i></div>
                <h5 class="fw-bold">Archive Package?</h5>
                <p class="text-muted small mb-3">
                    Are you sure you want to archive <span id="archive_pkg_name" class="fw-bold"></span>?
                    It will be hidden from customers and moved to the Archive.
                    Existing bookings will remain valid and unaffected.
                </p>
                <form id="archiveForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning px-4 shadow-sm">Yes, Archive</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const TRANSPORT_OPTS = [
        { value: 'van',    emoji: '🚐', label: 'Van'        },
        { value: 'flight', emoji: '✈️',  label: 'Airplane'   },
        { value: 'bus',    emoji: '🚌', label: 'Bus'        },
        { value: 'boat',   emoji: '⛵', label: 'Boat'       },
        { value: 'train',  emoji: '🚂', label: 'Train'      },
        { value: 'walk',   emoji: '🚶', label: 'Walking'    },
        { value: 'car',    emoji: '🚗', label: 'Car'        },
        { value: 'bike',   emoji: '🏍️', label: 'Motorcycle' },
    ];

    function getOpt(value) {
        return TRANSPORT_OPTS.find(o => o.value === value) || TRANSPORT_OPTS[0];
    }

    function dropdownOptionsHTML(selectedValue) {
        return TRANSPORT_OPTS.map(o => `
            <div class="transport-option${o.value === selectedValue ? ' selected' : ''}" data-value="${o.value}">
                <span class="t-icon">${o.emoji}</span>
                <span class="t-label">${o.label}</span>
            </div>`).join('');
    }

    function initWrapper(wrapper) {
        const val       = wrapper.dataset.selected || 'van';
        const opt       = getOpt(val);
        const trigger   = wrapper.querySelector('.transport-trigger');
        const dropdown  = wrapper.querySelector('.transport-dropdown');
        const hiddenSel = wrapper.querySelector('select');
        trigger.textContent      = opt.emoji;
        dropdown.innerHTML       = dropdownOptionsHTML(val);
        hiddenSel.innerHTML      = `<option value="${val}" selected>${val}</option>`;
        wrapper.dataset.selected = val;
    }

    document.querySelectorAll('.transport-wrapper').forEach(initWrapper);

    /* ── Global click handler ─────────────────────────────────────────── */
    document.addEventListener('click', function(e) {

        /* Transport trigger */
        if (e.target.closest('.transport-trigger')) {
            e.stopPropagation();
            const wrapper  = e.target.closest('.transport-wrapper');
            const dropdown = wrapper.querySelector('.transport-dropdown');
            const isOpen   = dropdown.classList.contains('open');
            closeAllDropdowns();
            if (!isOpen) dropdown.classList.add('open');
            return;
        }

        /* Transport option selected */
        if (e.target.closest('.transport-option')) {
            e.stopPropagation();
            const option    = e.target.closest('.transport-option');
            const dropdown  = option.closest('.transport-dropdown');
            const wrapper   = dropdown.closest('.transport-wrapper');
            const value     = option.dataset.value;
            const opt       = getOpt(value);
            const hiddenSel = wrapper.querySelector('select');
            wrapper.querySelector('.transport-trigger').textContent = opt.emoji;
            hiddenSel.innerHTML      = `<option value="${value}" selected>${value}</option>`;
            wrapper.dataset.selected = value;
            dropdown.querySelectorAll('.transport-option').forEach(o =>
                o.classList.toggle('selected', o.dataset.value === value));
            dropdown.classList.remove('open');
            return;
        }

        closeAllDropdowns();

        /* Add Day */
        if (e.target.closest('.add-day-btn')) {
            const modal     = e.target.closest('.modal');
            const container = modal.querySelector('.itinerary_container') || modal.querySelector('#edit_itinerary_container');
            const div       = document.createElement('div');
            div.className   = 'input-group mb-2 itinerary-item';
            div.innerHTML   = `
                <span class="input-group-text small">Day</span>
                <div class="transport-wrapper" data-selected="van">
                    <select name="transport[]"></select>
                    <div class="transport-trigger" title="Pick transport">🚐</div>
                    <div class="transport-dropdown"></div>
                </div>
                <input type="text" name="itinerary[]" class="form-control" placeholder="Activity description" required>
                <button type="button" class="btn btn-danger remove-day"><i class="fa fa-times"></i></button>`;
            container.appendChild(div);
            initWrapper(div.querySelector('.transport-wrapper'));
            updateItineraryUI(container);
            return;
        }

        /* Add Inclusion / Exclusion */
        if (e.target.closest('.add-inclusion-btn, .add-exclusion-btn')) {
            const isInc    = !!e.target.closest('.add-inclusion-btn');
            const modal    = e.target.closest('.modal');
            const sel      = isInc
                ? (modal.id === 'addPackageModal' ? '.inclusions_container' : '#edit_inclusions_container')
                : (modal.id === 'addPackageModal' ? '.exclusions_container' : '#edit_exclusions_container');
            const nameAttr = isInc ? 'inclusions[]' : 'exclusions[]';
            const ph       = isInc ? 'e.g. Free Breakfast' : 'e.g. Airfare';
            modal.querySelector(sel).insertAdjacentHTML('beforeend', `
                <div class="input-group mb-2 list-item">
                    <input type="text" name="${nameAttr}" class="form-control" placeholder="${ph}">
                    <button type="button" class="btn btn-danger remove-item"><i class="fa fa-times"></i></button>
                </div>`);
            return;
        }

        /* Remove Day */
        if (e.target.closest('.remove-day')) {
            const item      = e.target.closest('.itinerary-item');
            const container = item.parentElement;
            item.remove();
            updateItineraryUI(container);
            return;
        }

        /* Remove Inclusion / Exclusion */
        if (e.target.closest('.remove-item')) {
            e.target.closest('.list-item').remove();
            return;
        }

        /* Edit package button */
        if (e.target.closest('.edit-package-btn')) {
            const id = e.target.closest('.edit-package-btn').dataset.id;
            document.getElementById('editPackageForm').action = `/admin/packages/${id}`;

            fetch(`/admin/packages/${id}/edit`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('edit_name').value             = data.name;
                    document.getElementById('edit_language').value         = data.language || 'English';
                    document.getElementById('edit_rating').value           = Math.round(data.rating) || 5;
                    document.getElementById('edit_max_group_size').value   = data.max_group_size || 10;
                    document.getElementById('edit_status').value           = data.status;
                    document.getElementById('edit_description').value      = data.description;
                    document.getElementById('edit_destination_id').value   = data.destination_id;
                    document.getElementById('edit_price_per_person').value = data.price_per_person;
                    document.getElementById('edit_currency').value         = data.currency || 'PHP';

                    let itin = [], transports = [];
                    try { itin       = typeof data.itinerary === 'string' ? JSON.parse(data.itinerary) : (data.itinerary || []); } catch(_) {}
                    try { transports = typeof data.transport  === 'string' ? JSON.parse(data.transport)  : (data.transport  || []); } catch(_) {}

                    const itinContainer = document.getElementById('edit_itinerary_container');
                    itinContainer.innerHTML = '';
                    itin.forEach((val, i) => {
                        const t   = transports[i] || 'van';
                        const opt = getOpt(t);
                        const div = document.createElement('div');
                        div.className = 'input-group mb-2 itinerary-item';
                        div.innerHTML = `
                            <span class="input-group-text small">Day ${i + 1}</span>
                            <div class="transport-wrapper" data-selected="${t}">
                                <select name="transport[]"></select>
                                <div class="transport-trigger" title="Pick transport">${opt.emoji}</div>
                                <div class="transport-dropdown"></div>
                            </div>
                            <input type="text" name="itinerary[]" value="${val.replace(/"/g,'&quot;')}" class="form-control" required>
                            <button type="button" class="btn btn-danger remove-day"><i class="fa fa-times"></i></button>`;
                        itinContainer.appendChild(div);
                        initWrapper(div.querySelector('.transport-wrapper'));
                    });
                    updateItineraryUI(itinContainer);

                    const buildList = (containerId, raw, nameAttr) => {
                        const c = document.getElementById(containerId);
                        c.innerHTML = '';
                        let arr = [];
                        try { arr = typeof raw === 'string' ? JSON.parse(raw) : (raw || []); } catch(_) {}
                        arr.forEach(v => {
                            c.insertAdjacentHTML('beforeend', `
                                <div class="input-group mb-2 list-item">
                                    <input type="text" name="${nameAttr}" value="${v.replace(/"/g,'&quot;')}" class="form-control">
                                    <button type="button" class="btn btn-danger remove-item"><i class="fa fa-times"></i></button>
                                </div>`);
                        });
                    };
                    buildList('edit_inclusions_container', data.inclusions, 'inclusions[]');
                    buildList('edit_exclusions_container', data.exclusions, 'exclusions[]');

                    document.getElementById('edit_imagePreview').innerHTML = data.image
                        ? `<img src="/storage/${data.image}" style="width:100%;height:100%;object-fit:cover;">`
                        : `<i class="fa fa-image" style="color:var(--text-muted);"></i>`;

                    new bootstrap.Modal(document.getElementById('editPackageModal')).show();
                });
            return;
        }

        /* Archive trigger */
        if (e.target.closest('.btn-archive-trigger')) {
            const btn = e.target.closest('.btn-archive-trigger');

            document.getElementById('archive_pkg_name').innerText = btn.dataset.name;
            document.getElementById('archiveForm').action         = btn.dataset.url;

            new bootstrap.Modal(document.getElementById('archiveConfirmModal')).show();
        }
    });

    function closeAllDropdowns() {
        document.querySelectorAll('.transport-dropdown.open').forEach(d => d.classList.remove('open'));
    }

    function updateItineraryUI(container) {
        container.querySelectorAll('.itinerary-item').forEach((item, i) => {
            const lbl = item.querySelector('.input-group-text');
            if (lbl) lbl.innerText = `Day ${i + 1}`;
        });
        const count = container.querySelectorAll('.itinerary-item').length;
        const modal = container.closest('.modal');
        if (modal) {
            const badge = modal.querySelector('.duration-display');
            if (badge) badge.innerText = `${count} Day${count !== 1 ? 's' : ''} / ${Math.max(0, count - 1)} Night${count - 1 !== 1 ? 's' : ''}`;
        }
    }

    const setupPreview = (inputId, previewId, filenameId) => {
        const el = document.getElementById(inputId);
        if (!el) return;
        el.addEventListener('change', function() {
            if (!this.files || !this.files[0]) return;
            const file = this.files[0];
            if (filenameId) {
                const span = document.getElementById(filenameId);
                if (span) span.textContent = file.name;
            }
            const r = new FileReader();
            r.onload = ev => {
                document.getElementById(previewId).innerHTML =
                    `<img src="${ev.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
            };
            r.readAsDataURL(file);
        });
    };
    setupPreview('add_image_input', 'add_imagePreview', null);
    setupPreview('edit_image_input', 'edit_imagePreview', 'edit_image_filename');
});
</script>
@endsection