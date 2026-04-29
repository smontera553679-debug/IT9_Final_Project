@extends('layouts.customer')

@section('content')
<style>
    .checkout-wrap {
        padding: clamp(20px, 4vw, 48px) 0;
        background-color: var(--bg-body);
        min-height: 100vh;
        transition: background-color 0.3s ease;
    }

    .checkout-wrap h2 { color: var(--text-title) !important; }

    /* ── Form cards ── */
    .checkout-wrap .card {
        background-color: var(--bg-dropdown) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 16px !important;
        transition: background-color 0.3s ease;
    }
    .checkout-wrap .card h5 { color: var(--text-title) !important; }
    .checkout-wrap .card .text-muted { color: var(--text-muted) !important; }

    /* ── Form labels & inputs ── */
    .checkout-wrap .form-label { color: var(--text-muted) !important; font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
    .checkout-wrap .form-control,
    .checkout-wrap .form-select {
        background-color: var(--bg-body) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
        border-radius: 8px !important;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .checkout-wrap .form-control:focus,
    .checkout-wrap .form-select:focus {
        border-color: #a855f7 !important;
        box-shadow: 0 0 0 3px rgba(168,85,247,0.15) !important;
    }
    .checkout-wrap .form-control::placeholder { color: var(--text-muted) !important; opacity: 0.6; }

    /* ── Payment option ── */
    .payment-option {
        border: 2px solid var(--border-color);
        border-radius: 12px; padding: 14px 16px;
        cursor: pointer; transition: border-color 0.2s, background 0.2s;
        margin-bottom: 10px;
        background: var(--bg-body);
        color: var(--text-primary);
    }
    .payment-option:has(input:checked) {
        border-color: #a855f7;
        background: var(--notif-unread);
    }
    .payment-option .fw-bold { color: var(--text-primary) !important; }

    .payment-info-box {
        background: var(--bg-dropdown);
        border: 1px solid var(--border-color);
        border-radius: 10px; padding: 14px 16px;
        margin-top: 8px; font-size: 0.9rem;
        display: none; color: var(--text-primary);
    }
    .payment-info-box .small { color: var(--text-muted) !important; }
    .payment-info-box strong { color: var(--text-title) !important; }

    /* ── Alert ── */
    .checkout-wrap .alert-danger {
        background-color: rgba(239,68,68,0.1) !important;
        border-color: rgba(239,68,68,0.3) !important;
        color: #f87171 !important;
        border-radius: 10px !important;
    }
    .checkout-wrap .alert-warning {
        background-color: rgba(251,191,36,0.1) !important;
        border-color: rgba(251,191,36,0.3) !important;
        color: #fbbf24 !important;
        border-radius: 10px !important;
    }

    /* ── Summary card ── */
    .summary-sticky { position: sticky; top: 100px; }
    @media (max-width: 767px) { .summary-sticky { position: static; } }

    .summary-card {
        background: var(--bg-dropdown) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 16px; padding: clamp(16px, 3vw, 24px);
        transition: background-color 0.3s ease;
    }
    .summary-card h5 { color: var(--text-title) !important; }

    .summary-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 6px 0; font-size: 0.92rem;
        color: var(--text-primary);
    }
    .summary-row .text-muted { color: var(--text-muted) !important; }

    .total-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 12px 0 0; font-size: 1.05rem;
    }
    .total-row .fw-bold { color: var(--text-title) !important; }

    .total-amount { font-size: clamp(1.4rem, 4vw, 1.8rem); font-weight: 800; color: #a855f7 !important; line-height: 1; }

    .summary-card hr { border-color: var(--border-color) !important; opacity: 1 !important; }

    /* ── Submit button ── */
    .checkout-wrap .btn-primary {
        background-color: #a855f7 !important;
        border-color: #a855f7 !important;
        color: #fff !important;
    }
    .checkout-wrap .btn-primary:hover {
        background-color: #9333ea !important;
        border-color: #9333ea !important;
    }
    .checkout-wrap .btn-primary:disabled {
        background-color: var(--border-color) !important;
        border-color: var(--border-color) !important;
        color: var(--text-muted) !important;
    }

    /* ── Badge ── */
    .checkout-wrap .badge.bg-info { background-color: rgba(168,85,247,0.2) !important; color: #a855f7 !important; }

    @media (max-width: 576px) { .form-section { padding: 16px !important; } }

    /* ══════════════════════════════════════
       FEEDBACK MODAL
    ══════════════════════════════════════ */
    .feedback-overlay {
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(0, 0, 0, 0.55);
        backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; pointer-events: none;
        transition: opacity 0.35s ease;
    }
    .feedback-overlay.show {
        opacity: 1; pointer-events: all;
    }

    .feedback-modal {
        background: var(--bg-dropdown, #fff);
        border: 1px solid var(--border-color, #e5e7eb);
        border-radius: 24px;
        padding: clamp(28px, 5vw, 48px) clamp(24px, 5vw, 44px);
        max-width: 420px; width: 90%;
        text-align: center;
        box-shadow: 0 24px 60px rgba(0,0,0,0.18);
        transform: translateY(24px) scale(0.97);
        transition: transform 0.35s ease, opacity 0.35s ease;
        opacity: 0;
    }
    .feedback-overlay.show .feedback-modal {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    /* ── Thank-you state ── */
    .feedback-thankyou { display: none; }

    /* ── Stars ── */
    .star-row {
        display: flex; justify-content: center; gap: 10px;
        margin: 18px 0 6px;
    }
    .star-btn {
        background: none; border: none; padding: 0;
        font-size: 2.4rem; line-height: 1;
        color: #d1d5db; /* grey default */
        cursor: pointer;
        transition: color 0.15s ease, transform 0.15s ease;
    }
    .star-btn:hover,
    .star-btn.active {
        color: #f59e0b; /* amber */
        transform: scale(1.15);
    }
    .star-hint {
        font-size: 0.75rem; color: var(--text-muted, #9ca3af);
        margin-bottom: 20px;
        min-height: 18px;
        transition: opacity 0.2s;
    }

    /* ── Submit ── */
    .btn-feedback-submit {
        background: #a855f7; color: #fff;
        border: none; border-radius: 50px;
        padding: 12px 36px; font-weight: 700;
        font-size: 0.95rem; cursor: pointer;
        transition: background 0.2s, transform 0.15s;
        width: 100%;
    }
    .btn-feedback-submit:hover { background: #9333ea; transform: translateY(-1px); }
    .btn-feedback-submit:disabled { background: #d1d5db; cursor: not-allowed; transform: none; }

    /* ── Skip ── */
    .btn-feedback-skip {
        background: none; border: none;
        color: var(--text-muted, #9ca3af);
        font-size: 0.82rem; cursor: pointer;
        margin-top: 12px; text-decoration: underline;
        transition: color 0.2s;
    }
    .btn-feedback-skip:hover { color: var(--text-primary, #374151); }

    /* ── Thank you icon ── */
    .thankyou-icon {
        font-size: 3.5rem; margin-bottom: 12px;
    }
    .thankyou-title {
        font-size: 1.25rem; font-weight: 800;
        color: var(--text-title, #111827);
        margin-bottom: 10px;
    }
    .thankyou-msg {
        font-size: 0.88rem; color: var(--text-muted, #6b7280);
        line-height: 1.6; margin-bottom: 20px;
    }
    .btn-feedback-close {
        background: #a855f7; color: #fff;
        border: none; border-radius: 50px;
        padding: 11px 36px; font-weight: 700;
        font-size: 0.9rem; cursor: pointer;
        transition: background 0.2s;
        width: 100%;
    }
    .btn-feedback-close:hover { background: #9333ea; }

    .feedback-modal-title {
        font-size: 1.15rem; font-weight: 800;
        color: var(--text-title, #111827);
        margin-bottom: 4px;
    }
    .feedback-modal-sub {
        font-size: 0.85rem; color: var(--text-muted, #6b7280);
        line-height: 1.5;
    }
</style>

<div class="checkout-wrap">
<div class="container">
    <h2 class="fw-bold mb-4" style="font-size:clamp(1.3rem,4vw,1.8rem);">Checkout</h2>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customer.confirm') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
        @csrf
        <input type="hidden" name="package_id" value="{{ $package->id }}">
        <input type="hidden" name="total_price" id="input_total_price" value="">

        <div class="row g-3 g-md-4">

            {{-- ── Left Column ── --}}
            <div class="col-12 col-md-8">

                {{-- Travel Details --}}
                <div class="card border-0 shadow-sm mb-3 mb-md-4 form-section p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="fw-bold mb-0">Travel Details</h5>
                        <span class="badge bg-info text-dark" style="font-size:0.8rem;">
                            Max: {{ $package->max_group_size }} people
                        </span>
                    </div>

                    <div id="capacity_warning" class="alert alert-warning d-none py-2" style="font-size:0.9rem;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Exceeds max group size of {{ $package->max_group_size }}.
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold mb-1">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control"
                               value="{{ auth()->user()->fullname ?? auth()->user()->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold mb-1">Phone Number</label>
                        <input type="text" name="contact_number" class="form-control"
                               value="{{ auth()->user()->phone_number ?? '' }}"
                               placeholder="e.g. 09123456789" required>
                    </div>

                    <div class="row g-2 g-md-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold mb-1">
                                Adults <span class="text-muted fw-normal">(₱{{ number_format($package->price_per_person, 2) }})</span>
                            </label>
                            <input type="number" name="num_adults" id="num_adults" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold mb-1">
                                Children <span class="text-muted fw-normal">(₱{{ number_format($package->price_per_person * 0.5, 2) }})</span>
                            </label>
                            <input type="number" name="num_children" id="num_children" class="form-control" min="0" value="0" required>
                        </div>
                    </div>

                    <div>
                        <label class="form-label small fw-bold mb-1">Travel Date</label>
                        <input type="date" name="travel_date" class="form-control" required min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="card border-0 shadow-sm mb-3 mb-md-4 form-section p-3 p-md-4">
                    <h5 class="fw-bold mb-3">Payment Method</h5>

                    <label class="payment-option">
                        <div class="d-flex align-items-center gap-2">
                            <input class="form-check-input mt-0" type="radio" name="method" id="gcash" value="GCash" required onclick="togglePaymentFields()">
                            <span class="fw-bold">GCash</span>
                        </div>
                        <div class="payment-info-box" id="gcash_fields">
                            <p class="mb-1 small text-muted">Send your payment to:</p>
                            <p class="mb-1"><strong>Account Name:</strong> ByteTrip</p>
                            <p class="mb-0"><strong>Account Number:</strong> 09123456789</p>
                        </div>
                    </label>

                    <label class="payment-option">
                        <div class="d-flex align-items-center gap-2">
                            <input class="form-check-input mt-0" type="radio" name="method" id="card" value="Credit / Debit Card" onclick="togglePaymentFields()">
                            <span class="fw-bold">Credit / Debit Card</span>
                        </div>
                        <div class="payment-info-box" id="card_fields">
                            <p class="mb-1 small text-muted">Transfer to our Bank Account:</p>
                            <p class="mb-1"><strong>Account Name:</strong> ByteTrip</p>
                            <p class="mb-0"><strong>Card Number:</strong> 0912345678912345</p>
                        </div>
                    </label>

                    <div class="mt-3">
                        <div class="alert alert-warning py-2 px-3 mb-2" style="border-radius:10px;font-size:0.85rem;">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Note:</strong> Incomplete payments may result in cancellation.
                        </div>
                        <label class="form-label small fw-bold mb-1" style="color:#a855f7 !important;">Upload Proof of Payment</label>
                        <input type="file" name="proof_file" class="form-control" required>
                        <small class="text-muted">Upload a screenshot of your transaction receipt.</small>
                    </div>
                </div>
            </div>

            {{-- ── Right Column: Summary ── --}}
            <div class="col-12 col-md-4">
                <div class="summary-sticky">
                    <div class="summary-card shadow-sm">
                        <h5 class="fw-bold mb-3">Order Summary</h5>

                        <div class="summary-row"><span class="text-muted">Adults</span><span class="fw-semibold" id="display_adults">1</span></div>
                        <div class="summary-row"><span class="text-muted">Children</span><span class="fw-semibold" id="display_children">0</span></div>
                        <div class="summary-row"><span class="text-muted">Total Travelers</span><span class="fw-semibold" id="display_count">1</span></div>

                        <hr class="my-2">

                        <div class="summary-row"><span class="text-muted">Adults subtotal</span><span class="fw-semibold" id="display_adults_sub">₱0.00</span></div>
                        <div class="summary-row" id="row_children_sub" style="display:none!important;">
                            <span class="text-muted">Children subtotal</span><span class="fw-semibold" id="display_children_sub">₱0.00</span>
                        </div>
                        <div class="summary-row"><span class="text-muted">Subtotal</span><span class="fw-semibold" id="display_subtotal">₱0.00</span></div>
                        <div class="summary-row"><span class="text-muted">Taxes (10%)</span><span class="fw-semibold" id="display_tax">₱0.00</span></div>

                        <hr class="my-2">

                        <div class="total-row">
                            <span class="fw-bold">Grand Total</span>
                            <span class="total-amount" id="display_total">₱0.00</span>
                        </div>

                        <button type="submit" id="submit_btn"
                                class="btn btn-primary w-100 rounded-pill py-2 fw-bold mt-4"
                                style="font-size:clamp(0.85rem,2vw,1rem);">
                            Confirm Booking
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
</div>

{{-- ══════════════════════════════════════
     FEEDBACK MODAL OVERLAY
     Shown after form submission via JS
══════════════════════════════════════ --}}
<div class="feedback-overlay" id="feedbackOverlay">
    <div class="feedback-modal" id="feedbackModal">

        {{-- ── Rating State ── --}}
        <div id="feedbackRatingState">
            <div style="font-size:2.2rem; margin-bottom:10px;">✈️</div>
            <div class="feedback-modal-title">How was your booking experience?</div>
            <p class="feedback-modal-sub">
                Please rate your booking experience with our tour system.
            </p>

            <div class="star-row" id="starRow">
                <button class="star-btn" type="button" data-value="1">★</button>
                <button class="star-btn" type="button" data-value="2">★</button>
                <button class="star-btn" type="button" data-value="3">★</button>
                <button class="star-btn" type="button" data-value="4">★</button>
                <button class="star-btn" type="button" data-value="5">★</button>
            </div>
            <div class="star-hint" id="starHint">1 = Poor &nbsp;·&nbsp; 5 = Excellent</div>

            <button class="btn-feedback-submit" id="btnFeedbackSubmit" disabled>
                Submit Rating
            </button>
            <br>
            <button class="btn-feedback-skip" id="btnFeedbackSkip">
                Skip for now
            </button>
        </div>

        {{-- ── Thank-you State ── --}}
        <div id="feedbackThankyou" class="feedback-thankyou">
            <div class="thankyou-icon">🎉</div>
            <div class="thankyou-title">Thank you for your feedback!</div>
            <p class="thankyou-msg">
                We're glad to have been part of your journey.<br>
                Enjoy your travels and we hope to serve you again soon.
            </p>
            <button class="btn-feedback-close" id="btnFeedbackClose">
                Close
            </button>
        </div>

    </div>
</div>

<script>
    const pricePerAdult = {{ $package->price_per_person }};
    const pricePerChild = pricePerAdult * 0.5;
    const taxRate       = 0.10;
    const maxGroupSize  = {{ $package->max_group_size }};
    const fmt = n => '₱' + n.toLocaleString(undefined, {minimumFractionDigits: 2});

    function calculateTotal() {
        const adults   = parseInt(document.getElementById('num_adults').value)   || 0;
        const children = parseInt(document.getElementById('num_children').value) || 0;
        const total_travelers = adults + children;

        const warningDiv = document.getElementById('capacity_warning');
        const submitBtn  = document.getElementById('submit_btn');

        if (total_travelers > maxGroupSize) {
            warningDiv.classList.remove('d-none');
            submitBtn.disabled  = true;
            submitBtn.innerText = 'Limit Exceeded';
        } else {
            warningDiv.classList.add('d-none');
            submitBtn.disabled  = false;
            submitBtn.innerText = 'Confirm Booking';
        }

        const adultsSub = adults * pricePerAdult;
        const childSub  = children * pricePerChild;
        const subtotal  = adultsSub + childSub;
        const tax       = subtotal * taxRate;
        const total     = subtotal + tax;

        document.getElementById('display_adults').innerText       = adults;
        document.getElementById('display_children').innerText     = children;
        document.getElementById('display_count').innerText        = total_travelers;
        document.getElementById('display_adults_sub').innerText   = fmt(adultsSub);
        document.getElementById('display_children_sub').innerText = fmt(childSub);
        document.getElementById('display_subtotal').innerText     = fmt(subtotal);
        document.getElementById('display_tax').innerText          = fmt(tax);
        document.getElementById('display_total').innerText        = fmt(total);
        document.getElementById('input_total_price').value        = total.toFixed(2);

        const childRow = document.getElementById('row_children_sub');
        childRow.style.setProperty('display', children > 0 ? 'flex' : 'none', 'important');
    }

    function togglePaymentFields() {
        document.getElementById('gcash_fields').style.display = document.getElementById('gcash').checked ? 'block' : 'none';
        document.getElementById('card_fields').style.display  = document.getElementById('card').checked  ? 'block' : 'none';
    }

    document.getElementById('num_adults').addEventListener('input', calculateTotal);
    document.getElementById('num_children').addEventListener('input', calculateTotal);
    window.onload = calculateTotal;

    /* ══════════════════════════════════════
       FEEDBACK MODAL LOGIC
    ══════════════════════════════════════ */
    let selectedRating  = 0;
    let submittedBookingId = null;

    const overlay      = document.getElementById('feedbackOverlay');
    const starBtns     = document.querySelectorAll('.star-btn');
    const starHint     = document.getElementById('starHint');
    const submitFbBtn  = document.getElementById('btnFeedbackSubmit');
    const skipBtn      = document.getElementById('btnFeedbackSkip');
    const closeBtn     = document.getElementById('btnFeedbackClose');
    const ratingState  = document.getElementById('feedbackRatingState');
    const thankyouState= document.getElementById('feedbackThankyou');

    const hintMap = {
        1: '😞 Poor',
        2: '😕 Fair',
        3: '😊 Good',
        4: '😄 Very Good',
        5: '🤩 Excellent!',
    };

    function highlightStars(n) {
        starBtns.forEach((btn, i) => {
            btn.classList.toggle('active', i < n);
        });
    }

    starBtns.forEach(btn => {
        btn.addEventListener('mouseenter', () => highlightStars(+btn.dataset.value));
        btn.addEventListener('mouseleave', () => highlightStars(selectedRating));
        btn.addEventListener('click', () => {
            selectedRating = +btn.dataset.value;
            highlightStars(selectedRating);
            starHint.textContent = hintMap[selectedRating];
            submitFbBtn.disabled = false;
        });
    });

    function openFeedbackModal(bookingId) {
        submittedBookingId = bookingId;
        overlay.classList.add('show');
    }

    function closeFeedbackModal() {
        overlay.classList.remove('show');
        // Redirect to my bookings after closing
        setTimeout(() => { window.location.href = "{{ route('customer.bookings') }}"; }, 300);
    }

    skipBtn.addEventListener('click', closeFeedbackModal);
    closeBtn.addEventListener('click', closeFeedbackModal);

    submitFbBtn.addEventListener('click', async function () {
        if (!selectedRating || !submittedBookingId) return;

        submitFbBtn.disabled = true;
        submitFbBtn.textContent = 'Submitting…';

        try {
            const res = await fetch("{{ route('feedback.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    booking_id: submittedBookingId,
                    rating: selectedRating,
                }),
            });

            const data = await res.json();

            if (data.success) {
                ratingState.style.display  = 'none';
                thankyouState.style.display = 'block';
            }
        } catch (e) {
            submitFbBtn.disabled = false;
            submitFbBtn.textContent = 'Submit Rating';
            console.error('Feedback error:', e);
        }
    });

    /* ── Intercept checkout form submission ── */
    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(async res => {
            // Laravel returns JSON if we add Accept header, otherwise it redirects.
            // We'll handle both: check content-type.
            const contentType = res.headers.get('content-type') || '';

            if (contentType.includes('application/json')) {
                const data = await res.json();
                if (data.booking_id) {
                    openFeedbackModal(data.booking_id);
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                // Non-JSON response — parse booking_id from redirect URL or show modal with null
                // Fallback: just open modal without booking_id (feedback won't save but UX preserved)
                openFeedbackModal(null);
            }
        })
        .catch(() => {
            // If fetch fails entirely, just submit normally
            form.submit();
        });
    });
</script>
@endsection