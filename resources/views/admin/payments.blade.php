@extends('layouts.admin')

@section('content')
<style>
    /* ══════════════════════════════════════
       PAYMENTS — THEME-AWARE STYLES
    ══════════════════════════════════════ */

    .pay-wrap .page-title { color: var(--text-title); transition: color 0.3s ease; }

    /* ── Card ── */
    .pay-wrap .card {
        background: var(--bg-navbar);
        border: 1px solid var(--border-color) !important;
        border-radius: 15px;
        overflow: visible;
        transition: background 0.3s ease, border-color 0.3s ease;
    }
    .pay-wrap .table-responsive {
        background: var(--bg-navbar);
        transition: background 0.3s ease;
    }

    /* ── thead ── */
    .pay-wrap .tbl-head {
        background-color: var(--border-head);
        border-bottom: 2px solid var(--border-color);
        transition: background-color 0.3s ease, border-color 0.3s ease;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .pay-wrap .tbl-head th {
        color: var(--text-muted);
        font-size: 0.72rem; letter-spacing: 0.08em;
        text-transform: uppercase; font-weight: 700;
        background-color: var(--border-head) !important;
        --bs-table-bg: transparent !important;
    }

    /* ── tbody rows ── */
    .pay-wrap .table > :not(caption) > * > *,
    .pay-wrap .table tbody tr td {
        background-color: var(--bg-navbar) !important;
        color: var(--text-primary) !important;
        border-color: var(--border-item) !important;
        --bs-table-bg: var(--bg-navbar) !important;
        --bs-table-color: var(--text-primary) !important;
        transition: background-color 0.15s ease, color 0.3s ease, border-color 0.3s ease;
    }
    .pay-wrap .table-hover tbody tr:hover > * {
        background-color: var(--notif-hover) !important;
    }

    /* ── Outer table wrapper ── */
    .pay-wrap .table-outer {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 600px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .pay-wrap .table-outer::-webkit-scrollbar { display: none; }

    /* ── Inner clip wrapper ── */
    .pay-wrap .table-clip {
        border-radius: 15px;
        overflow: hidden;
    }

    /* ── Pay ID ── */
    .pay-wrap .pay-id { color: #a78bfa; font-weight: 700; }

    /* ── Muted text ── */
    .pay-wrap .col-muted { color: var(--text-muted); transition: color 0.3s ease; }

    /* ── Cancellation reason badge ── */
    .pay-wrap .cancel-reason-badge {
        background: var(--bg-body) !important;
        border-color: var(--border-color) !important;
        color: #ef4444 !important;
        transition: background 0.3s ease, border-color 0.3s ease;
    }

    /* ── Empty state ── */
    .pay-wrap .empty-state { color: var(--text-time); font-size: 0.88rem; transition: color 0.3s ease; }

    /* ══════════════════════════════════════
       PROOF & REJECT MODALS — THEME-AWARE
    ══════════════════════════════════════ */
    .proof-overlay {
        display: none; position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.8); z-index: 9999;
        justify-content: center; align-items: center;
        backdrop-filter: blur(5px); padding: 16px;
    }

    .proof-content {
        background: var(--bg-dropdown);
        border: 1px solid var(--border-light);
        width: 100%; max-width: 500px;
        border-radius: 16px; overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        animation: zoomIn 0.3s ease-out;
        transition: background 0.3s ease;
    }

    .proof-header {
        padding: 14px 18px; display: flex;
        justify-content: space-between; align-items: center;
        background: var(--border-head);
        border-bottom: 1px solid var(--border-color);
        transition: background 0.3s ease, border-color 0.3s ease;
    }

    .proof-header h5 {
        color: var(--text-title);
        transition: color 0.3s ease;
    }

    .btn-modal-back {
        border: none; background: #444; color: white;
        padding: 5px 14px; border-radius: 20px;
        font-weight: bold; cursor: pointer; font-size: 0.85rem;
        transition: background 0.2s ease;
    }
    .btn-modal-back:hover { background: #555; }

    .proof-body {
        padding: 18px; text-align: center;
        max-height: 70vh; overflow-y: auto;
    }
    .proof-body img { max-width: 100%; height: auto; border-radius: 10px; }

    .proof-body .form-check-label { color: var(--text-primary); transition: color 0.3s ease; }
    .proof-body p.text-muted { color: var(--text-muted) !important; }

    @keyframes zoomIn {
        from { transform: scale(0.9); opacity: 0; }
        to   { transform: scale(1); opacity: 1; }
    }
</style>

<div class="pay-wrap container-fluid py-3 py-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold fs-4 page-title">Payment Records</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-clip">
                <div class="table-outer">
                    <table class="table table-hover align-middle mb-0" style="min-width:700px;">
                        <thead class="tbl-head">
                            <tr>
                                <th class="ps-3 ps-md-4 py-3" style="white-space:nowrap;">Pay ID</th>
                                <th class="py-3" style="white-space:nowrap;">Booking</th>
                                <th class="py-3" style="white-space:nowrap;">Amount</th>
                                <th class="text-center py-3">Proof</th>
                                <th class="text-center py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr>
                                <td class="ps-3 ps-md-4" style="white-space:nowrap;">
                                    <span class="pay-id">PAY-{{ $payment->id }}</span>
                                </td>
                                <td class="col-muted" style="white-space:nowrap;">#{{ $payment->booking_id }}</td>
                                <td style="white-space:nowrap; color:var(--text-title); font-weight:600;">
                                    ₱{{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="text-center">
                                    @if($payment->proof_file)
                                        <button type="button"
                                                onclick="openProof('{{ asset('storage/' . $payment->proof_file) }}')"
                                                class="btn btn-sm text-white fw-bold"
                                                style="background-color:#6f42c1; border-radius:50px; padding:6px 18px; border:none; font-size:13px;">
                                            View
                                        </button>
                                    @else
                                        <span class="col-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php $booking = $payment->booking; @endphp

                                    @if($payment->status == 'confirmed')
                                        <span class="badge bg-success rounded-pill px-3 py-2">Confirmed</span>

                                    @elseif($payment->status == 'rejected')
                                        <div>
                                            <span class="badge bg-danger rounded-pill px-3 py-2 mb-1">Rejected</span>
                                            <div class="small col-muted">{{ $payment->reject_reason }}</div>
                                        </div>

                                    @elseif($booking && $booking->status == 'awaiting_cancellation')
                                        <div class="d-flex flex-column align-items-center gap-2">
                                            <span class="badge cancel-reason-badge border px-2 py-1" style="font-size:0.7rem;">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $booking->cancellation_reason ?? 'No reason' }}
                                            </span>
                                            @if($booking->cancellation_reason === 'Other')
                                                <div class="small col-muted" style="max-width:180px;">
                                                    {{ $booking->cancellation_custom_reason ?? 'N/A' }}
                                                </div>
                                            @endif
                                            <div class="d-flex gap-1 flex-wrap justify-content-center">
                                                <form action="{{ route('admin.bookings.approve-cancel', $booking->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-2 shadow-sm" style="font-size:0.72rem;">Approve</button>
                                                </form>
                                                <form action="{{ route('admin.bookings.reject-cancel', $booking->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-2" style="font-size:0.72rem;">Reject</button>
                                                </form>
                                            </div>
                                        </div>

                                    @elseif($booking && $booking->status == 'cancelled')
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">Cancelled</span>

                                    @else
                                        <div class="d-flex gap-1 justify-content-center flex-wrap">
                                            <form action="{{ route('payments.confirm', $payment->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm fw-bold rounded-pill px-3 shadow-sm btn-success">
                                                    Confirm
                                                </button>
                                            </form>
                                            <button type="button"
                                                    onclick="openRejectModal({{ $payment->id }})"
                                                    class="btn btn-sm fw-bold rounded-pill px-3 shadow-sm btn-danger">
                                                Reject
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 empty-state">
                                    <i class="fas fa-credit-card mb-2 d-block" style="font-size:2rem; opacity:0.3;"></i>
                                    No records found.
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

{{-- Proof Modal --}}
<div id="proofModal" class="proof-overlay">
    <div class="proof-content">
        <div class="proof-header">
            <h5 class="m-0 fw-bold" style="font-size:1rem;">Transaction Receipt</h5>
            <button onclick="closeProof()" class="btn-modal-back">&larr; Back</button>
        </div>
        <div class="proof-body">
            <img id="modalImage" src="" alt="Payment Proof">
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="proof-overlay">
    <div class="proof-content" style="max-width:420px;">
        <div class="proof-header">
            <h5 class="m-0 fw-bold" style="font-size:1rem;">Reject Payment</h5>
            <button onclick="closeRejectModal()" class="btn-modal-back">&larr; Back</button>
        </div>
        <div class="proof-body text-start">
            <form id="rejectForm" method="POST">
                @csrf
                <p class="text-muted small mb-3">Select a reason for rejecting this payment:</p>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="reject_reason" id="reason1" value="Insufficient Payment" required>
                    <label class="form-check-label" for="reason1">Insufficient Payment</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="reject_reason" id="reason2" value="Payment Verification Failed">
                    <label class="form-check-label" for="reason2">Payment Verification Failed</label>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="radio" name="reject_reason" id="reason3" value="Proof Not Accepted">
                    <label class="form-check-label" for="reason3">Proof Not Accepted</label>
                </div>
                <button type="submit" class="btn btn-danger w-100 rounded-pill fw-bold">Confirm Rejection</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openProof(imgUrl) {
        document.getElementById('modalImage').src = imgUrl;
        document.getElementById('proofModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeProof() {
        document.getElementById('proofModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    function openRejectModal(paymentId) {
        document.getElementById('rejectForm').action = `/admin/payments/${paymentId}/reject`;
        document.querySelectorAll('#rejectForm input[type=radio]').forEach(r => r.checked = false);
        document.getElementById('rejectModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    window.onclick = function(e) {
        if (e.target === document.getElementById('proofModal')) closeProof();
        if (e.target === document.getElementById('rejectModal')) closeRejectModal();
    }
</script>
@endsection