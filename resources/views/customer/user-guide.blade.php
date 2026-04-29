@extends('layouts.customer')

@section('content')

<style>
    .guide-wrap { max-width: 860px; margin: 40px auto; padding: 0 20px 60px; }

    .guide-hero {
        background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
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
        background: #f5f0ff; border-color: #d8cbff; color: #6f42c1;
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

    .guide-section-body { display: none; padding: 28px; }
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
        background: linear-gradient(135deg, #007bff, #6f42c1);
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

    .shortcut-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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
        background: #eff6ff; color: #3b82f6;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; flex-shrink: 0;
    }
    .shortcut-card strong { font-size: 0.82rem; color: var(--text-title, #1a1a2e); display: block; }
    .shortcut-card span   { font-size: 0.73rem; color: var(--text-muted, #64748b); }

    @media (max-width: 600px) {
        .guide-hero { padding: 32px 24px; }
        .guide-hero h1 { font-size: 1.4rem; }
        .guide-section-header { padding: 18px 20px; }
        .guide-section-body { padding: 20px; }
    }
</style>

<div class="guide-wrap">

    {{-- Hero --}}
    <div class="guide-hero">
        <h1><i class="fas fa-compass me-2"></i> Help & User Guide</h1>
        <p>Learn how to browse tour packages, make bookings, and manage your trips with ByteTrip.</p>
    </div>

    {{-- Quick-jump nav --}}
    <div class="guide-nav">
        <a href="#browsing"  onclick="openSection('browsing')"><i class="fas fa-map-marked-alt"></i> Browse Packages</a>
        <a href="#booking"   onclick="openSection('booking')"><i class="fas fa-calendar-plus"></i> Make a Booking</a>
        <a href="#payment"   onclick="openSection('payment')"><i class="fas fa-coins"></i> Payment</a>
        <a href="#mybookings" onclick="openSection('mybookings')"><i class="fas fa-calendar-check"></i> My Bookings</a>
        <a href="#cancel"    onclick="openSection('cancel')"><i class="fas fa-times-circle"></i> Cancellations</a>
        <a href="#account"   onclick="openSection('account')"><i class="fas fa-user-circle"></i> My Account</a>
    </div>

    {{-- 1. Browsing --}}
    <div class="guide-section open" id="browsing">
        <div class="guide-section-header" onclick="toggleSection('browsing')">
            <div class="section-icon" style="background:#eff6ff;color:#3b82f6;"><i class="fas fa-map-marked-alt"></i></div>
            <div>
                <h2>Browsing Tour Packages</h2>
                <p>Find the perfect trip for you</p>
            </div>
            <i class="fas fa-chevron-down section-chevron"></i>
        </div>
        <div class="guide-section-body">
            <ul class="step-list">
                <li>
                    <div class="step-num">1</div>
                    <div class="step-content">
                        <strong>Go to Tour Packages</strong>
                        <span>Click <strong>Tour Packages</strong> in the navigation bar to see all available destinations.</span>
                    </div>
                </li>
                <li>
                    <div class="step-num">2</div>
                    <div class="step-content">
                        <strong>Select a Destination</strong>
                        <span>Click on any destination card to view the tour packages available for that location.</span>
                    </div>
                </li>
                <li>
                    <div class="step-num">3</div>
                    <div class="step-content">
                        <strong>View Package Details</strong>
                        <span>Click <strong>View Details</strong> on any package to see the full itinerary, inclusions, price, and duration before deciding to book.</span>
                    </div>
                </li>
            </ul>
            <div class="tip-box">
                <i class="fas fa-lightbulb"></i>
                <p>Check the homepage for <strong>Featured Packages</strong> and <strong>Popular Destinations</strong> — these are curated picks by our admins just for you.</p>
            </div>
        </div>
    </div>

    {{-- 2. Booking --}}
    <div class="guide-section" id="booking">
        <div class="guide-section-header" onclick="toggleSection('booking')">
            <div class="section-icon" style="background:#f0fdf4;color:#22c55e;"><i class="fas fa-calendar-plus"></i></div>
            <div>
                <h2>Making a Booking</h2>
                <p>Reserve your tour package in a few steps</p>
            </div>
            <i class="fas fa-chevron-down section-chevron"></i>
        </div>
        <div class="guide-section-body">
            <ul class="step-list">
                <li>
                    <div class="step-num">1</div>
                    <div class="step-content">
                        <strong>Click "Book Now"</strong>
                        <span>On the package detail page, click the <strong>Book Now</strong> button to go to the checkout page.</span>
                    </div>
                </li>
                <li>
                    <div class="step-num">2</div>
                    <div class="step-content">
                        <strong>Fill in Your Details</strong>
                        <span>Enter your preferred travel date, number of travellers, and any special requests on the checkout form.</span>
                    </div>
                </li>
                <li>
                    <div class="step-num">3</div>
                    <div class="step-content">
                        <strong>Confirm Your Booking</strong>
                        <span>Review the summary and click <strong>Confirm Booking</strong>. Your booking will be created with a <span class="badge-pill badge-blue">Pending</span> status while you arrange payment.</span>
                    </div>
                </li>
            </ul>
            <div class="tip-box">
                <i class="fas fa-lightbulb"></i>
                <p>You must be logged in to make a booking. If you haven't registered yet, sign up for a free account first.</p>
            </div>
        </div>
    </div>

    {{-- 3. Payment --}}
    <div class="guide-section" id="payment">
        <div class="guide-section-header" onclick="toggleSection('payment')">
            <div class="section-icon" style="background:#fff7ed;color:#f97316;"><i class="fas fa-coins"></i></div>
            <div>
                <h2>Payment</h2>
                <p>How to submit your payment proof</p>
            </div>
            <i class="fas fa-chevron-down section-chevron"></i>
        </div>
        <div class="guide-section-body">
            <ul class="step-list">
                <li>
                    <div class="step-num">1</div>
                    <div class="step-content">
                        <strong>Complete Your Payment</strong>
                        <span>Transfer the total amount shown on your booking using the payment details provided (bank transfer, GCash, etc.).</span>
                    </div>
                </li>
                <li>
                    <div class="step-num">2</div>
                    <div class="step-content">
                        <strong>Upload Your Receipt</strong>
                        <span>Go to <strong>My Bookings</strong>, find your booking, and click <strong>Upload Payment Proof</strong>. Attach a clear screenshot or photo of your receipt.</span>
                    </div>
                </li>
                <li>
                    <div class="step-num">3</div>
                    <div class="step-content">
                        <strong>Wait for Verification</strong>
                        <span>Your booking status will change to <span class="badge-pill badge-orange">For Review</span>. Our team will verify it and update the status to <span class="badge-pill badge-green">Confirmed</span> once approved.</span>
                    </div>
                </li>
                <li>
                    <div class="step-num">4</div>
                    <div class="step-content">
                        <strong>Get Notified</strong>
                        <span>You'll receive a notification (bell icon) as soon as your payment is verified or if any action is needed from your side.</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    {{-- 4. My Bookings --}}
    <div class="guide-section" id="mybookings">
        <div class="guide-section-header" onclick="toggleSection('mybookings')">
            <div class="section-icon" style="background:#f5f0ff;color:#6f42c1;"><i class="fas fa-calendar-check"></i></div>
            <div>
                <h2>My Bookings</h2>
                <p>Track and manage all your trips</p>
            </div>
            <i class="fas fa-chevron-down section-chevron"></i>
        </div>
        <div class="guide-section-body">
            <ul class="step-list">
                <li>
                    <div class="step-num">1</div>
                    <div class="step-content">
                        <strong>View All Bookings</strong>
                        <span>Click <strong>My Bookings</strong> in the navigation bar to see every booking you've made along with their current status.</span>
                    </div>
                </li>
                <li>
                    <div class="step-num">2</div>
                    <div class="step-content">
                        <strong>Booking Statuses Explained</strong>
                        <span>
                            <span class="badge-pill badge-blue">Pending</span> Payment not yet uploaded &nbsp;
                            <span class="badge-pill badge-orange">For Review</span> Payment under verification &nbsp;
                            <span class="badge-pill badge-green">Confirmed</span> All good, your trip is set &nbsp;
                            <span class="badge-pill badge-red">Cancelled</span> Booking was cancelled
                        </span>
                    </div>
                </li>
                <li>
                    <div class="step-num">3</div>
                    <div class="step-content">
                        <strong>View Booking Details</strong>
                        <span>Click on any booking to see the full details — package info, travel date, total price, and payment history.</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    {{-- 5. Cancellations --}}
    <div class="guide-section" id="cancel">
        <div class="guide-section-header" onclick="toggleSection('cancel')">
            <div class="section-icon" style="background:#fef2f2;color:#ef4444;"><i class="fas fa-times-circle"></i></div>
            <div>
                <h2>Cancellations</h2>
                <p>How to request a cancellation</p>
            </div>
            <i class="fas fa-chevron-down section-chevron"></i>
        </div>
        <div class="guide-section-body">
            <ul class="step-list">
                <li>
                    <div class="step-num">1</div>
                    <div class="step-content">
                        <strong>Request a Cancellation</strong>
                        <span>Go to <strong>My Bookings</strong> and click <strong>Cancel Booking</strong> on the booking you wish to cancel. This sends a request to the admin for review.</span>
                    </div>
                </li>
                <li>
                    <div class="step-num">2</div>
                    <div class="step-content">
                        <strong>Wait for Approval</strong>
                        <span>Cancellations are not instant — an admin must approve your request. You'll be notified once a decision has been made.</span>
                    </div>
                </li>
                <li>
                    <div class="step-num">3</div>
                    <div class="step-content">
                        <strong>Cancellation Outcome</strong>
                        <span>If approved, your booking status changes to <span class="badge-pill badge-red">Cancelled</span>. If rejected, your booking remains active and you'll receive a notification explaining why.</span>
                    </div>
                </li>
            </ul>
            <div class="tip-box">
                <i class="fas fa-lightbulb"></i>
                <p>Cancellation policies may apply depending on how close to the travel date your request is submitted. Contact support for refund-related queries.</p>
            </div>
        </div>
    </div>

    {{-- 6. Account --}}
    <div class="guide-section" id="account">
        <div class="guide-section-header" onclick="toggleSection('account')">
            <div class="section-icon" style="background:#f5f0ff;color:#6f42c1;"><i class="fas fa-user-circle"></i></div>
            <div>
                <h2>My Account</h2>
                <p>Profile, password, and preferences</p>
            </div>
            <i class="fas fa-chevron-down section-chevron"></i>
        </div>
        <div class="guide-section-body">
            <div class="shortcut-grid">
                <div class="shortcut-card">
                    <i class="fas fa-user-edit"></i>
                    <div>
                        <strong>Update Profile</strong>
                        <span>Change your name and profile photo in Account Settings</span>
                    </div>
                </div>
                <div class="shortcut-card">
                    <i class="fas fa-lock"></i>
                    <div>
                        <strong>Change Password</strong>
                        <span>Update your password anytime from Account Settings</span>
                    </div>
                </div>
                <div class="shortcut-card">
                    <i class="fas fa-moon"></i>
                    <div>
                        <strong>Dark Mode</strong>
                        <span>Toggle light/dark theme via the sun/moon icon in the navbar</span>
                    </div>
                </div>
                <div class="shortcut-card">
                    <i class="fas fa-bell"></i>
                    <div>
                        <strong>Notifications</strong>
                        <span>The bell icon shows real-time updates on your bookings</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- /.guide-wrap --}}

<script>
function toggleSection(id) {
    document.getElementById(id).classList.toggle('open');
}
function openSection(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add('open');
}
</script>

@endsection