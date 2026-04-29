@extends('layouts.admin')

@section('content')

<style>
    .guide-hero {
        background: linear-gradient(135deg, #6f42c1 0%, #007bff 100%);
        border-radius: 20px;
        padding: 48px 40px;
        color: #fff;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    .guide-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.07);
        border-radius: 50%;
    }
    .guide-hero::after {
        content: '';
        position: absolute;
        bottom: -60px; right: 80px;
        width: 160px; height: 160px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .guide-hero h1 { font-size: 1.9rem; font-weight: 700; margin-bottom: 8px; }
    .guide-hero p  { font-size: 1rem; opacity: 0.85; margin: 0; }

    .guide-nav {
        display: flex; flex-wrap: wrap; gap: 10px;
        margin-bottom: 32px;
    }
    .guide-nav a {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 18px;
        background: var(--bg-navbar, #fff);
        border: 1px solid var(--border-color, #eee);
        border-radius: 50px;
        font-size: 0.82rem; font-weight: 600;
        color: var(--text-primary, #000);
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s, color 0.2s;
    }
    .guide-nav a:hover, .guide-nav a.active {
        background: #f5f0ff;
        border-color: #d8cbff;
        color: #6f42c1;
    }
    .guide-nav a i { font-size: 0.8rem; }

    .guide-section {
        background: var(--bg-navbar, #fff);
        border: 1px solid var(--border-color, #eee);
        border-radius: 16px;
        margin-bottom: 24px;
        overflow: hidden;
    }
    .guide-section-header {
        display: flex; align-items: center; gap: 14px;
        padding: 22px 28px;
        border-bottom: 1px solid var(--border-color, #eee);
        cursor: pointer;
        user-select: none;
    }
    .guide-section-header:hover { background: var(--bg-body, #f8f9fa); }
    .section-icon {
        width: 42px; height: 42px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .guide-section-header h2 {
        font-size: 1rem; font-weight: 700;
        color: var(--text-title, #1a1a2e); margin: 0; flex: 1;
    }
    .guide-section-header p {
        font-size: 0.78rem; color: var(--text-muted, #64748b);
        margin: 2px 0 0;
    }
    .section-chevron {
        color: var(--text-muted, #64748b);
        transition: transform 0.25s ease;
        font-size: 0.85rem;
    }
    .guide-section.open .section-chevron { transform: rotate(180deg); }

    .guide-section-body {
        display: none;
        padding: 28px;
    }
    .guide-section.open .guide-section-body { display: block; }

    .step-list { list-style: none; padding: 0; margin: 0; }
    .step-list li {
        display: flex; gap: 16px;
        padding: 14px 0;
        border-bottom: 1px solid var(--border-color, #eee);
    }
    .step-list li:last-child { border-bottom: none; padding-bottom: 0; }
    .step-num {
        width: 28px; height: 28px; border-radius: 50%;
        background: linear-gradient(135deg, #6f42c1, #007bff);
        color: #fff; font-size: 0.72rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; margin-top: 1px;
    }
    .step-content strong {
        display: block; font-size: 0.88rem;
        color: var(--text-title, #1a1a2e); margin-bottom: 3px;
    }
    .step-content span {
        font-size: 0.8rem; color: var(--text-muted, #64748b); line-height: 1.55;
    }

    .tip-box {
        display: flex; gap: 12px; align-items: flex-start;
        background: #f5f0ff; border: 1px solid #e0d4ff;
        border-radius: 12px; padding: 14px 16px;
        margin-top: 20px;
    }
    [data-theme="dark"] .tip-box { background: #1e1a35; border-color: #3a2f6e; }
    .tip-box i { color: #6f42c1; margin-top: 2px; flex-shrink: 0; }
    .tip-box p { font-size: 0.8rem; color: var(--text-muted, #64748b); margin: 0; line-height: 1.6; }

    .warning-box {
        display: flex; gap: 12px; align-items: flex-start;
        background: #fff7ed; border: 1px solid #fed7aa;
        border-radius: 12px; padding: 14px 16px;
        margin-top: 12px;
    }
    [data-theme="dark"] .warning-box { background: #2a1f0e; border-color: #7c3d00; }
    .warning-box i { color: #f97316; margin-top: 2px; flex-shrink: 0; }
    .warning-box p { font-size: 0.8rem; color: var(--text-muted, #64748b); margin: 0; line-height: 1.6; }

    .danger-box {
        display: flex; gap: 12px; align-items: flex-start;
        background: #fef2f2; border: 1px solid #fecaca;
        border-radius: 12px; padding: 14px 16px;
        margin-top: 12px;
    }
    [data-theme="dark"] .danger-box { background: #2a0e0e; border-color: #7c0000; }
    .danger-box i { color: #ef4444; margin-top: 2px; flex-shrink: 0; }
    .danger-box p { font-size: 0.8rem; color: var(--text-muted, #64748b); margin: 0; line-height: 1.6; }

    /* Inline action button replicas */
    .btn-demo {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 8px;
        font-size: 0.78rem; vertical-align: middle;
        margin: 0 2px; flex-shrink: 0;
    }
    .btn-demo-blue   { background: #3b82f6; color: #fff; }
    .btn-demo-yellow { background: #f59e0b; color: #fff; }
    .btn-demo-green  { background: #22c55e; color: #fff; }
    .btn-demo-red    { background: #ef4444; color: #fff; }

    .flow-row {
        display: flex; align-items: center; gap: 8px;
        flex-wrap: wrap; margin-top: 10px;
    }
    .flow-step {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--bg-body, #f8f9fa);
        border: 1px solid var(--border-color, #eee);
        border-radius: 8px; padding: 6px 12px;
        font-size: 0.78rem; font-weight: 600;
        color: var(--text-title, #1a1a2e);
    }
    .flow-step i { font-size: 0.72rem; }
    .flow-arrow { color: var(--text-muted, #64748b); font-size: 0.8rem; }

    .badge-pill {
        display: inline-block;
        padding: 2px 10px; border-radius: 100px;
        font-size: 0.7rem; font-weight: 700;
    }
    .badge-blue   { background: #eff6ff; color: #3b82f6; }
    .badge-green  { background: #f0fdf4; color: #22c55e; }
    .badge-orange { background: #fff7ed; color: #f97316; }
    .badge-red    { background: #fef2f2; color: #ef4444; }
    .badge-purple { background: #f5f0ff; color: #6f42c1; }
    .badge-gray   { background: #f1f5f9; color: #64748b; }

    .shortcut-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 12px; margin-top: 4px;
    }
    .shortcut-card {
        background: var(--bg-body, #f8f9fa);
        border: 1px solid var(--border-color, #eee);
        border-radius: 12px; padding: 16px;
        display: flex; align-items: center; gap: 12px;
    }
    .shortcut-card i {
        width: 36px; height: 36px; border-radius: 10px;
        background: #f5f0ff; color: #6f42c1;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; flex-shrink: 0;
    }
    .shortcut-card strong { font-size: 0.82rem; color: var(--text-title, #1a1a2e); display: block; }
    .shortcut-card span   { font-size: 0.73rem; color: var(--text-muted, #64748b); }

    .sub-label {
        font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em;
        text-transform: uppercase; color: #6f42c1;
        margin: 20px 0 10px; display: flex; align-items: center; gap: 8px;
    }
    .sub-label::after {
        content: ''; flex: 1; height: 1px;
        background: var(--border-color, #eee); max-width: 60px;
    }
</style>

{{-- Hero --}}
<div class="guide-hero">
    <h1><i class="fas fa-book-open me-2"></i> Admin User Guide</h1>
    <p>Everything you need to manage ByteTrip — destinations, packages, bookings, payments, and customers.</p>
</div>

{{-- Quick-jump nav --}}
<div class="guide-nav">
    <a href="#dashboard"    onclick="openSection('dashboard')"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="#destinations" onclick="openSection('destinations')"><i class="fas fa-map-marked-alt"></i> Destinations</a>
    <a href="#packages"     onclick="openSection('packages')"><i class="fas fa-box-open"></i> Packages</a>
    <a href="#bookings"     onclick="openSection('bookings')"><i class="fas fa-calendar-check"></i> Bookings</a>
    <a href="#payments"     onclick="openSection('payments')"><i class="fas fa-credit-card"></i> Payments</a>
    <a href="#customers"    onclick="openSection('customers')"><i class="fas fa-users"></i> Customers</a>
    <a href="#archive"      onclick="openSection('archive')"><i class="fas fa-archive"></i> Archive</a>
    <a href="#settings"     onclick="openSection('settings')"><i class="fas fa-cog"></i> Settings</a>
</div>

{{-- 1. Dashboard --}}
<div class="guide-section open" id="dashboard">
    <div class="guide-section-header" onclick="toggleSection('dashboard')">
        <div class="section-icon" style="background:#eff6ff;color:#3b82f6;"><i class="fas fa-tachometer-alt"></i></div>
        <div>
            <h2>Dashboard</h2>
            <p>Overview of your system at a glance</p>
        </div>
        <i class="fas fa-chevron-down section-chevron"></i>
    </div>
    <div class="guide-section-body">
        <ul class="step-list">
            <li>
                <div class="step-num">1</div>
                <div class="step-content">
                    <strong>Summary Cards</strong>
                    <span>The top row shows total destinations, packages, bookings, and revenue. These update in real time as records change.</span>
                </div>
            </li>
            <li>
                <div class="step-num">2</div>
                <div class="step-content">
                    <strong>Recent Bookings</strong>
                    <span>A quick-view table of the latest customer bookings. Click any row to navigate directly to that booking's detail.</span>
                </div>
            </li>
            <li>
                <div class="step-num">3</div>
                <div class="step-content">
                    <strong>Pending Actions</strong>
                    <span>Highlighted items that require your attention — unverified payments, pending cancellation requests, and unapproved bookings.</span>
                </div>
            </li>
        </ul>
        <div class="tip-box mt-3">
            <i class="fas fa-lightbulb"></i>
            <p>The dashboard refreshes automatically every 30 seconds. You don't need to reload the page to see new activity.</p>
        </div>
    </div>
</div>

{{-- 2. Destinations --}}
<div class="guide-section" id="destinations">
    <div class="guide-section-header" onclick="toggleSection('destinations')">
        <div class="section-icon" style="background:#f0fdf4;color:#22c55e;"><i class="fas fa-map-marked-alt"></i></div>
        <div>
            <h2>Destinations</h2>
            <p>Create and manage travel destinations</p>
        </div>
        <i class="fas fa-chevron-down section-chevron"></i>
    </div>
    <div class="guide-section-body">
        <ul class="step-list">
            <li>
                <div class="step-num">1</div>
                <div class="step-content">
                    <strong>Adding a Destination</strong>
                    <span>Click <strong>Add Destination</strong>, fill in the name, description, location, and upload a cover image. Click Save when done.</span>
                </div>
            </li>
            <li>
                <div class="step-num">2</div>
                <div class="step-content">
                    <strong>Editing a Destination</strong>
                    <span>Each row has two action buttons. Click the
                        <span class="btn-demo btn-demo-blue"><i class="fas fa-pen-to-square"></i></span>
                        <strong>blue edit button</strong> to update the destination's details or replace its image.
                    </span>
                </div>
            </li>
            <li>
                <div class="step-num">3</div>
                <div class="step-content">
                    <strong>Enable / Disable</strong>
                    <span>When a destination is disabled, it will be marked as unavailable and customers will no longer be able to view it.</span>
                </div>
            </li>
            <li>
                <div class="step-num">4</div>
                <div class="step-content">
                    <strong>Mark as Popular</strong>
                    <span>Click the <i class="fas fa-star"></i> star icon to feature a destination on the customer homepage under "Popular Destinations".</span>
                </div>
            </li>
            <li>
                <div class="step-num">5</div>
                <div class="step-content">
                    <strong>Archiving a Destination</strong>
                    <span>Click the
                        <span class="btn-demo btn-demo-yellow"><i class="fas fa-box-archive"></i></span>
                        <strong>yellow archive button</strong> next to any destination. This does <strong>not</strong> permanently delete it — it moves the destination to the <strong>Archive</strong> where it can be restored or permanently removed later.
                    </span>
                </div>
            </li>
        </ul>

        <div class="warning-box">
            <i class="fas fa-exclamation-triangle"></i>
            <p>Archiving a destination hides it from customers immediately. All packages linked to that destination are also hidden. Existing bookings are not affected.</p>
        </div>

        <div class="sub-label">Archive Flow</div>
        <div class="flow-row">
            <div class="flow-step"><i class="fas fa-map-marked-alt"></i> Active Destination</div>
            <i class="fas fa-arrow-right flow-arrow"></i>
            <div class="flow-step">
                <span class="btn-demo btn-demo-yellow" style="width:22px;height:22px;font-size:0.65rem;border-radius:6px;"><i class="fas fa-box-archive"></i></span>
                Click Archive
            </div>
            <i class="fas fa-arrow-right flow-arrow"></i>
            <div class="flow-step"><i class="fas fa-archive"></i> Moved to Archive</div>
            <i class="fas fa-arrow-right flow-arrow"></i>
            <div class="flow-step">
                <span class="btn-demo btn-demo-green" style="width:22px;height:22px;font-size:0.65rem;border-radius:6px;"><i class="fas fa-rotate-left"></i></span>
                Restore &nbsp;<em>or</em>
            </div>
            <i class="fas fa-arrow-right flow-arrow"></i>
            <div class="flow-step" style="border-color:#fecaca;color:#ef4444;">
                <span class="btn-demo btn-demo-red" style="width:22px;height:22px;font-size:0.65rem;border-radius:6px;"><i class="fas fa-trash"></i></span>
                Permanently Delete
            </div>
        </div>
    </div>
</div>

{{-- 3. Packages --}}
<div class="guide-section" id="packages">
    <div class="guide-section-header" onclick="toggleSection('packages')">
        <div class="section-icon" style="background:#fff7ed;color:#f97316;"><i class="fas fa-box-open"></i></div>
        <div>
            <h2>Packages</h2>
            <p>Create tour packages linked to destinations</p>
        </div>
        <i class="fas fa-chevron-down section-chevron"></i>
    </div>
    <div class="guide-section-body">
        <ul class="step-list">
            <li>
                <div class="step-num">1</div>
                <div class="step-content">
                    <strong>Creating a Package</strong>
                    <span>Click <strong>Add Package</strong> and fill in the package name, destination, price, duration, inclusions, and at least one image. Save when complete.</span>
                </div>
            </li>
            <li>
                <div class="step-num">2</div>
                <div class="step-content">
                    <strong>Editing a Package</strong>
                    <span>Click the
                        <span class="btn-demo btn-demo-blue"><i class="fas fa-pen-to-square"></i></span>
                        <strong>blue edit button</strong> on a package row to update any of its details. Price changes will apply to new bookings only.
                    </span>
                </div>
            </li>
            <li>
                <div class="step-num">3</div>
                <div class="step-content">
                    <strong>Enable / Disable</strong>
                    <span>When a package is disabled, it will be marked as unavailable and customers will no longer be able to view it. All existing bookings for that package remain unaffected.</span>
                </div>
            </li>
            <li>
                <div class="step-num">4</div>
                <div class="step-content">
                    <strong>Mark as Featured</strong>
                    <span>Featured packages are shown prominently on the customer homepage. Use this for promotions or seasonal offers.</span>
                </div>
            </li>
            <li>
                <div class="step-num">5</div>
                <div class="step-content">
                    <strong>Archiving a Package</strong>
                    <span>Click the
                        <span class="btn-demo btn-demo-yellow"><i class="fas fa-box-archive"></i></span>
                        <strong>yellow archive button</strong> on a package row. The package is moved to the <strong>Archive</strong> and hidden from customers. It can be restored or permanently deleted from the Archive page.
                    </span>
                </div>
            </li>
        </ul>

        <div class="warning-box">
            <i class="fas fa-exclamation-triangle"></i>
            <p>Archiving a package hides it from customers immediately. Any existing bookings tied to that package are not affected and will continue normally.</p>
        </div>

        <div class="sub-label">Archive Flow</div>
        <div class="flow-row">
            <div class="flow-step"><i class="fas fa-box-open"></i> Active Package</div>
            <i class="fas fa-arrow-right flow-arrow"></i>
            <div class="flow-step">
                <span class="btn-demo btn-demo-yellow" style="width:22px;height:22px;font-size:0.65rem;border-radius:6px;"><i class="fas fa-box-archive"></i></span>
                Click Archive
            </div>
            <i class="fas fa-arrow-right flow-arrow"></i>
            <div class="flow-step"><i class="fas fa-archive"></i> Moved to Archive</div>
            <i class="fas fa-arrow-right flow-arrow"></i>
            <div class="flow-step">
                <span class="btn-demo btn-demo-green" style="width:22px;height:22px;font-size:0.65rem;border-radius:6px;"><i class="fas fa-rotate-left"></i></span>
                Restore &nbsp;<em>or</em>
            </div>
            <i class="fas fa-arrow-right flow-arrow"></i>
            <div class="flow-step" style="border-color:#fecaca;color:#ef4444;">
                <span class="btn-demo btn-demo-red" style="width:22px;height:22px;font-size:0.65rem;border-radius:6px;"><i class="fas fa-trash"></i></span>
                Permanently Delete
            </div>
        </div>
    </div>
</div>

{{-- 4. Bookings --}}
<div class="guide-section" id="bookings">
    <div class="guide-section-header" onclick="toggleSection('bookings')">
        <div class="section-icon" style="background:#f5f0ff;color:#6f42c1;"><i class="fas fa-calendar-check"></i></div>
        <div>
            <h2>Bookings</h2>
            <p>View and manage customer booking requests</p>
        </div>
        <i class="fas fa-chevron-down section-chevron"></i>
    </div>
    <div class="guide-section-body">
        <ul class="step-list">
            <li>
                <div class="step-num">1</div>
                <div class="step-content">
                    <strong>Booking Statuses</strong>
                    <span>
                        <span class="badge-pill badge-blue">Pending</span> — awaiting payment upload &nbsp;
                        <span class="badge-pill badge-orange">For Review</span> — payment submitted &nbsp;
                        <span class="badge-pill badge-green">Confirmed</span> — payment verified &nbsp;
                        <span class="badge-pill badge-red">Cancelled</span> — booking cancelled
                    </span>
                </div>
            </li>
            <li>
                <div class="step-num">2</div>
                <div class="step-content">
                    <strong>Cancellation Requests</strong>
                    <span>When a customer requests a cancellation, you can <strong>Approve</strong> or <strong>Reject</strong> it. Approving marks the booking as Cancelled and notifies the customer automatically.</span>
                </div>
            </li>
            <li>
                <div class="step-num">3</div>
                <div class="step-content">
                    <strong>Filtering Bookings</strong>
                    <span>Use the status filter tabs to quickly find bookings by state. You can also search by customer name or booking reference.</span>
                </div>
            </li>
        </ul>
        <div class="tip-box">
            <i class="fas fa-lightbulb"></i>
            <p>Customers receive an automatic notification whenever their booking or cancellation status changes. No manual follow-up is needed.</p>
        </div>
    </div>
</div>

{{-- 5. Payments --}}
<div class="guide-section" id="payments">
    <div class="guide-section-header" onclick="toggleSection('payments')">
        <div class="section-icon" style="background:#f0fdf4;color:#22c55e;"><i class="fas fa-credit-card"></i></div>
        <div>
            <h2>Payments</h2>
            <p>Verify or reject submitted payment proofs</p>
        </div>
        <i class="fas fa-chevron-down section-chevron"></i>
    </div>
    <div class="guide-section-body">
        <ul class="step-list">
            <li>
                <div class="step-num">1</div>
                <div class="step-content">
                    <strong>Reviewing a Payment</strong>
                    <span>When a customer uploads a payment proof, it appears here with a <span class="badge-pill badge-orange">For Review</span> status. Click to view the uploaded receipt image.</span>
                </div>
            </li>
            <li>
                <div class="step-num">2</div>
                <div class="step-content">
                    <strong>Confirming a Payment</strong>
                    <span>After validating the proof, click <strong>Confirm</strong>. The booking status automatically updates to <span class="badge-pill badge-green">Confirmed</span> and the customer is notified.</span>
                </div>
            </li>
            <li>
                <div class="step-num">3</div>
                <div class="step-content">
                    <strong>Rejecting a Payment</strong>
                    <span>If the proof is invalid or unclear, click <strong>Reject</strong>. The customer is notified and can re-upload a corrected proof.</span>
                </div>
            </li>
        </ul>
        <div class="tip-box">
            <i class="fas fa-lightbulb"></i>
            <p>Always verify the amount and reference number in the payment proof against the booking total before confirming.</p>
        </div>
    </div>
</div>

{{-- 6. Customers --}}
<div class="guide-section" id="customers">
    <div class="guide-section-header" onclick="toggleSection('customers')">
        <div class="section-icon" style="background:#fef2f2;color:#ef4444;"><i class="fas fa-users"></i></div>
        <div>
            <h2>Customers</h2>
            <p>Browse and review registered customer accounts</p>
        </div>
        <i class="fas fa-chevron-down section-chevron"></i>
    </div>
    <div class="guide-section-body">
        <ul class="step-list">
            <li>
                <div class="step-num">1</div>
                <div class="step-content">
                    <strong>Customer List</strong>
                    <span>View all registered customers including their name, email, join date, and total number of bookings made.</span>
                </div>
            </li>
            <li>
                <div class="step-num">2</div>
                <div class="step-content">
                    <strong>Searching Customers</strong>
                    <span>Use the search bar to find a specific customer by name or email address quickly.</span>
                </div>
            </li>
            <li>
                <div class="step-num">3</div>
                <div class="step-content">
                    <strong>Viewing Booking History</strong>
                    <span>Click on a customer row to see their full booking history, payment records, and account details.</span>
                </div>
            </li>
        </ul>
    </div>
</div>

{{-- 7. Archive --}}
<div class="guide-section" id="archive">
    <div class="guide-section-header" onclick="toggleSection('archive')">
        <div class="section-icon" style="background:#f1f5f9;color:#64748b;"><i class="fas fa-archive"></i></div>
        <div>
            <h2>Archive</h2>
            <p>Manage archived destinations and packages</p>
        </div>
        <i class="fas fa-chevron-down section-chevron"></i>
    </div>
    <div class="guide-section-body">

        <div class="sub-label"><i class="fas fa-map-marked-alt me-1"></i> Archived Destinations</div>
        <ul class="step-list">
            <li>
                <div class="step-num">1</div>
                <div class="step-content">
                    <strong>Restoring a Destination</strong>
                    <span>Click the
                        <span class="btn-demo btn-demo-green"><i class="fas fa-rotate-left"></i></span>
                        <strong>green restore button</strong> to make the destination active again. It will immediately become visible to customers along with all its linked packages.
                    </span>
                </div>
            </li>
            <li>
                <div class="step-num">2</div>
                <div class="step-content">
                    <strong>Permanently Deleting a Destination</strong>
                    <span>Click the
                        <span class="btn-demo btn-demo-red"><i class="fas fa-trash"></i></span>
                        <strong>red delete button</strong> to remove the destination forever. This action <strong>cannot be undone</strong>.
                    </span>
                </div>
            </li>
            <li>
                <div class="step-num">3</div>
                <div class="step-content">
                    <strong>When Permanent Deletion is Blocked</strong>
                    <span>The
                        <span class="btn-demo btn-demo-red"><i class="fas fa-trash"></i></span>
                        red delete button will be disabled if any package under that destination still has active bookings with a status of <span class="badge-pill badge-blue">Pending</span> or <span class="badge-pill badge-green">Confirmed</span>. Resolve all active bookings first before attempting permanent deletion.
                    </span>
                </div>
            </li>
        </ul>

        <div class="danger-box">
            <i class="fas fa-ban"></i>
            <p><strong>Blocked deletion example:</strong> Destination "Palawan" is archived but one of its packages still has 2 confirmed bookings. The system will prevent permanent deletion until those bookings are resolved.</p>
        </div>

        <div class="sub-label mt-4"><i class="fas fa-box-open me-1"></i> Archived Packages</div>
        <ul class="step-list">
            <li>
                <div class="step-num">1</div>
                <div class="step-content">
                    <strong>Restoring a Package</strong>
                    <span>Click the
                        <span class="btn-demo btn-demo-green"><i class="fas fa-rotate-left"></i></span>
                        <strong>green restore button</strong> to make the package active and bookable again.
                    </span>
                </div>
            </li>
            <li>
                <div class="step-num">2</div>
                <div class="step-content">
                    <strong>Permanently Deleting a Package</strong>
                    <span>Click the
                        <span class="btn-demo btn-demo-red"><i class="fas fa-trash"></i></span>
                        <strong>red delete button</strong> to remove the package forever. This action <strong>cannot be undone</strong>.
                    </span>
                </div>
            </li>
            <li>
                <div class="step-num">3</div>
                <div class="step-content">
                    <strong>When Permanent Deletion is Blocked</strong>
                    <span>The
                        <span class="btn-demo btn-demo-red"><i class="fas fa-trash"></i></span>
                        red delete button will be disabled if the package has any bookings with a status of <span class="badge-pill badge-blue">Pending</span> or <span class="badge-pill badge-green">Confirmed</span>. You must wait for those bookings to be completed or cancelled first.
                    </span>
                </div>
            </li>
        </ul>

        <div class="danger-box">
            <i class="fas fa-ban"></i>
            <p><strong>Blocked deletion example:</strong> Package "3D2N Batangas Dive Tour" is archived but still has 1 pending booking. The system will block permanent deletion until that booking is cancelled or completed.</p>
        </div>

        <div class="tip-box">
            <i class="fas fa-lightbulb"></i>
            <p>If a package has no bookings at all — or only <span class="badge-pill badge-red" style="font-size:0.68rem;">Cancelled</span> bookings — the red delete button will be enabled and permanent deletion will proceed immediately.</p>
        </div>

    </div>
</div>

{{-- 8. Settings --}}
<div class="guide-section" id="settings">
    <div class="guide-section-header" onclick="toggleSection('settings')">
        <div class="section-icon" style="background:#f5f0ff;color:#6f42c1;"><i class="fas fa-cog"></i></div>
        <div>
            <h2>Account Settings</h2>
            <p>Manage your admin profile and appearance</p>
        </div>
        <i class="fas fa-chevron-down section-chevron"></i>
    </div>
    <div class="guide-section-body">
        <div class="shortcut-grid">
            <div class="shortcut-card">
                <i class="fas fa-user-circle"></i>
                <div>
                    <strong>Update Profile</strong>
                    <span>Change your display name and profile photo</span>
                </div>
            </div>
            <div class="shortcut-card">
                <i class="fas fa-lock"></i>
                <div>
                    <strong>Change Password</strong>
                    <span>Update your login credentials anytime</span>
                </div>
            </div>
            <div class="shortcut-card">
                <i class="fas fa-moon"></i>
                <div>
                    <strong>Dark Mode</strong>
                    <span>Toggle via the sun/moon icon in the navbar</span>
                </div>
            </div>
            <div class="shortcut-card">
                <i class="fas fa-bell"></i>
                <div>
                    <strong>Notifications</strong>
                    <span>Bell icon shows unread alerts in real time</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSection(id) {
    const el = document.getElementById(id);
    el.classList.toggle('open');
}
function openSection(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add('open');
}
</script>

@endsection