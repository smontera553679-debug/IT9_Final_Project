@extends('layouts.admin')

@section('content')
@php
function formatStatNumber($num) {
    if ($num >= 1_000_000_000_000) return number_format($num / 1_000_000_000_000, 1) . 'T';
    if ($num >= 1_000_000_000)     return number_format($num / 1_000_000_000, 1) . 'B';
    if ($num >= 1_000_000)         return number_format($num / 1_000_000, 1) . 'M';
    if ($num >= 1_000)             return number_format($num / 1_000, 1) . 'K';
    return number_format($num);
}
@endphp

<div class="container-fluid p-3 p-md-4" style="background-color: var(--bg-body, #f0f2f8); min-height: 100vh;">

    {{-- ── Stat Cards ── --}}
    <div class="row mb-4 g-3">
        <div class="col-6 col-md-3">
            <div class="stat-card card-purple d-flex align-items-center justify-content-between p-3">
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <div class="icon-box flex-shrink-0">
                        <i class="fas fa-calendar-check text-dark"></i>
                    </div>
                    <div class="text-white overflow-hidden">
                        <div class="fw-bold text-uppercase stat-label">Total Bookings</div>
                    </div>
                </div>
                <div class="stat-number fw-bold text-white ms-2">{{ number_format($totalBookings) }}</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card card-green d-flex align-items-center justify-content-between p-3">
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <div class="icon-box flex-shrink-0">
                        <i class="fas fa-coins text-warning"></i>
                    </div>
                    <div class="text-white overflow-hidden">
                        <div class="fw-bold text-uppercase stat-label">Revenue</div>
                    </div>
                </div>
                <div class="stat-number fw-bold text-white ms-2">₱{{ formatStatNumber($revenue) }}</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card card-blue d-flex align-items-center justify-content-between p-3">
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <div class="icon-box flex-shrink-0">
                        <i class="fas fa-bell text-warning"></i>
                    </div>
                    <div class="text-white overflow-hidden">
                        <div class="fw-bold text-uppercase stat-label">Active Tours</div>
                    </div>
                </div>
                <div class="stat-number fw-bold text-white ms-2">{{ number_format($activeTours) }}</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card card-gold d-flex align-items-center justify-content-between p-3">
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <div class="icon-box flex-shrink-0">
                        <i class="fas fa-star text-warning"></i>
                    </div>
                    <div class="text-white overflow-hidden">
                        <div class="fw-bold text-uppercase stat-label">Overall Rating</div>
                        <div class="stat-sub">{{ $totalRatings }} review{{ $totalRatings != 1 ? 's' : '' }}</div>
                    </div>
                </div>
                <div class="stat-number fw-bold text-white ms-2">
                    {{ $overallRating > 0 ? number_format($overallRating, 1) : '—' }}
                    @if($overallRating > 0)<span class="stat-slash">/5</span>@endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Charts ── --}}
    <div class="row g-3 g-md-4">

        {{-- Revenue Trends --}}
        <div class="col-12 col-md-6">
            <div class="chart-card">
                <div class="chart-card-header">
                    <div class="chart-header-left">
                        <span class="chart-eyebrow" id="rev-eyebrow">Monthly Overview</span>
                        <h5 class="chart-title-text">
                            <span class="chart-title-icon">
                                <i class="fas fa-chart-line"></i>
                            </span>
                            Revenue Trends
                        </h5>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="rev-period-dropdown">
                            <select id="revPeriod" class="rev-period-select">
                                <option value="weekly">Weekly</option>
                                <option value="monthly" selected>Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="chart-badge chart-badge-blue">
                            <span class="badge-dot"></span>
                            Revenue
                        </div>
                    </div>
                </div>
                <div class="chart-canvas-wrap">
                    <canvas id="revenueTrends"></canvas>
                </div>
            </div>
        </div>

        {{-- Popular Destinations --}}
        <div class="col-12 col-md-6">
            <div class="chart-card">
                <div class="chart-card-header">
                    <div class="chart-header-left">
                        <span class="chart-eyebrow">Booking Volume</span>
                        <h5 class="chart-title-text">
                            <span class="chart-title-icon chart-title-icon--orange">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            Popular Destinations
                        </h5>
                    </div>
                    <div class="chart-badge chart-badge-orange">
                        <span class="badge-dot"></span>
                        Bookings
                    </div>
                </div>
                <div class="chart-canvas-wrap">
                    <canvas id="popularDestinations"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
(function () {

    /* ── Abbreviate large numbers for chart tooltips ── */
    function abbreviate(val) {
        if (val >= 1e12) return '₱' + (val / 1e12).toFixed(1) + 'T';
        if (val >= 1e9)  return '₱' + (val / 1e9).toFixed(1)  + 'B';
        if (val >= 1e6)  return '₱' + (val / 1e6).toFixed(1)  + 'M';
        if (val >= 1e3)  return '₱' + (val / 1e3).toFixed(1)  + 'K';
        return '₱' + Number(val).toLocaleString();
    }

    function abbreviateTick(val) {
        if (val >= 1e12) return '₱' + (val / 1e12).toFixed(0) + 'T';
        if (val >= 1e9)  return '₱' + (val / 1e9).toFixed(0)  + 'B';
        if (val >= 1e6)  return '₱' + (val / 1e6).toFixed(0)  + 'M';
        if (val >= 1e3)  return '₱' + (val / 1e3).toFixed(0)  + 'K';
        return '₱' + val;
    }

    /* ══════════════════════════════
       REVENUE TRENDS — Line Chart
    ══════════════════════════════ */
    const ctxRevenue = document.getElementById('revenueTrends').getContext('2d');

    const revDatasets = {
        weekly:  {
            labels:  @json($weeklyLabels),
            data:    @json($weeklyData),
            eyebrow: 'Weekly Overview'
        },
        monthly: {
            labels:  @json($months),
            data:    @json($revenueData),
            eyebrow: 'Monthly Overview'
        },
        yearly:  {
            labels:  @json($yearlyLabels),
            data:    @json($yearlyData),
            eyebrow: 'Yearly Overview'
        },
    };

    function makeGradient(ctx) {
        const g = ctx.createLinearGradient(0, 0, 0, 340);
        g.addColorStop(0,   'rgba(59,130,246,0.28)');
        g.addColorStop(0.5, 'rgba(99,102,241,0.12)');
        g.addColorStop(1,   'rgba(59,130,246,0.00)');
        return g;
    }

    const revenueChart = new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: revDatasets.monthly.labels,
            datasets: [{
                data: revDatasets.monthly.data,
                borderColor: '#3b82f6',
                backgroundColor: makeGradient(ctxRevenue),
                borderWidth: 3,
                tension: 0.42,
                fill: true,
                pointBackgroundColor: '#ffffff',
                pointBorderColor:     '#3b82f6',
                pointBorderWidth:     3,
                pointRadius:          5,
                pointHoverRadius:     8,
                pointHoverBackgroundColor: '#3b82f6',
                pointHoverBorderColor:     '#ffffff',
                pointHoverBorderWidth:     3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 600, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    backgroundColor: '#0f172a',
                    titleColor:      '#94a3b8',
                    bodyColor:       '#f8fafc',
                    titleFont:  { size: 11, weight: '600' },
                    bodyFont:   { size: 15, weight: '700' },
                    padding:    { top: 10, bottom: 10, left: 14, right: 14 },
                    cornerRadius: 10,
                    displayColors: false,
                    borderColor: 'rgba(59,130,246,0.35)',
                    borderWidth: 1,
                    callbacks: {
                        title: ctx => ctx[0].label,
                        label: ctx => '  ' + abbreviate(ctx.raw),
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148,163,184,0.12)', drawBorder: false },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 10, weight: '500' },
                        padding: 8,
                        callback: val => abbreviateTick(val),
                        maxTicksLimit: 5,
                    },
                    border: { display: false },
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 10, weight: '600' },
                        padding: 6,
                        maxRotation: 0,
                    },
                    border: { display: false },
                }
            }
        }
    });

    document.getElementById('revPeriod').addEventListener('change', function () {
        const period = this.value;
        const { labels, data, eyebrow } = revDatasets[period];
        revenueChart.data.labels = labels;
        revenueChart.data.datasets[0].data = data;
        revenueChart.data.datasets[0].backgroundColor = makeGradient(ctxRevenue);
        revenueChart.update();
        document.getElementById('rev-eyebrow').textContent = eyebrow;
    });

    /* ══════════════════════════════
       POPULAR DESTINATIONS — Bar
    ══════════════════════════════ */
    const ctxDest = document.getElementById('popularDestinations').getContext('2d');

    const barPlugin = {
        id: 'perBarGradient',
        beforeDatasetsDraw(chart) {
            const { ctx, chartArea: { top, bottom } } = chart;
            const ds   = chart.data.datasets[0];
            const meta = chart.getDatasetMeta(0);
            ds.backgroundColor = meta.data.map(() => {
                const g = ctx.createLinearGradient(0, top, 0, bottom);
                g.addColorStop(0,    'rgba(251,113,133,0.95)');
                g.addColorStop(0.45, 'rgba(249,115,22,0.90)');
                g.addColorStop(1,    'rgba(251,191,36,0.70)');
                return g;
            });
        }
    };

    new Chart(ctxDest, {
        type: 'bar',
        plugins: [barPlugin],
        data: {
            labels: @json($destNames),
            datasets: [{
                data: @json($destCounts),
                backgroundColor: 'rgba(249,115,22,0.85)',
                borderColor:     'transparent',
                borderRadius:    { topLeft: 8, topRight: 8, bottomLeft: 0, bottomRight: 0 },
                borderSkipped:   false,
                hoverBackgroundColor: 'rgba(249,115,22,1)',
                barPercentage:   0.58,
                categoryPercentage: 0.72,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 900, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    backgroundColor: '#0f172a',
                    titleColor:      '#94a3b8',
                    bodyColor:       '#f8fafc',
                    titleFont:  { size: 11, weight: '600' },
                    bodyFont:   { size: 15, weight: '700' },
                    padding:    { top: 10, bottom: 10, left: 14, right: 14 },
                    cornerRadius: 10,
                    displayColors: false,
                    borderColor: 'rgba(249,115,22,0.35)',
                    borderWidth: 1,
                    callbacks: {
                        title: ctx => ctx[0].label,
                        label: ctx => '  ' + ctx.raw + ' bookings',
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148,163,184,0.12)', drawBorder: false },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 10, weight: '500' },
                        padding: 8,
                        maxTicksLimit: 5,
                        stepSize: 1,
                    },
                    border: { display: false },
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 10, weight: '600' },
                        padding: 6,
                        maxRotation: 30,
                    },
                    border: { display: false },
                }
            }
        }
    });

})();
</script>

<style>
    /* ── Stat Cards ── */
    .stat-card {
        border-radius: 20px;
        min-height: 90px;
        border: none;
        flex-wrap: nowrap;
        overflow: hidden;
    }
    .card-purple { background-color: #8378d3; }
    .card-green  { background-color: #81c79c; }
    .card-blue   { background-color: #64a1e3; }
    .card-gold   { background-color: #f59e0b; }

    .stat-label {
        font-size: clamp(0.55rem, 1.8vw, 0.8rem);
        line-height: 1.25;
        white-space: normal;
        word-break: break-word;
        font-weight: 700;
        text-transform: uppercase;
    }
    .stat-sub {
        font-size: clamp(0.5rem, 1.5vw, 0.7rem);
        opacity: 0.85;
    }
    .stat-number {
        font-size: clamp(1rem, 3.5vw, 2.4rem);
        line-height: 1;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .stat-slash {
        font-size: clamp(0.65rem, 1.5vw, 1rem);
        opacity: 0.85;
    }

    .icon-box {
        background: rgba(255,255,255,0.9);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        display: flex; align-items: center; justify-content: center;
        width: clamp(36px, 7vw, 52px);
        height: clamp(36px, 7vw, 52px);
        padding: clamp(7px, 1.5vw, 12px);
        flex-shrink: 0;
    }
    .icon-box i {
        font-size: clamp(0.9rem, 2.5vw, 1.5rem) !important;
    }

    /* ── Chart Card ── */
    .chart-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #edf0f7;
        box-shadow: 0 2px 16px rgba(15,23,42,0.06);
        padding: 20px 20px 18px;
        transition: box-shadow 0.25s ease, transform 0.25s ease;
        overflow: hidden;
        position: relative;
    }

    .chart-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6 0%, #6366f1 50%, #8b5cf6 100%);
        border-radius: 20px 20px 0 0;
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .col-12:last-child .chart-card::before {
        background: linear-gradient(90deg, #fb7185 0%, #f97316 50%, #fbbf24 100%);
    }

    .chart-card:hover { box-shadow: 0 8px 32px rgba(15,23,42,0.10); transform: translateY(-2px); }
    .chart-card:hover::before { opacity: 1; }

    .chart-card-header {
        display: flex; align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 16px;
        gap: 8px;
        flex-wrap: wrap;
    }

    .chart-header-left { display: flex; flex-direction: column; gap: 3px; }

    .chart-eyebrow {
        font-size: 0.65rem; font-weight: 700;
        letter-spacing: 0.18em; text-transform: uppercase; color: #94a3b8;
        transition: color 0.2s ease;
    }

    .chart-title-text {
        font-weight: 700; font-size: 0.95rem;
        color: #1e293b; margin: 0;
        display: flex; align-items: center; gap: 7px;
    }

    .chart-title-icon {
        width: 26px; height: 26px; border-radius: 7px;
        background: rgba(59,130,246,0.10); color: #3b82f6;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.75rem; flex-shrink: 0;
    }
    .chart-title-icon--orange { background: rgba(249,115,22,0.10); color: #f97316; }

    .chart-badge {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.68rem; font-weight: 600;
        padding: 4px 10px; border-radius: 100px;
        border: 1px solid transparent; white-space: nowrap; flex-shrink: 0;
    }
    .chart-badge-blue   { background: rgba(59,130,246,0.08);  color: #3b82f6; border-color: rgba(59,130,246,0.20); }
    .chart-badge-orange { background: rgba(249,115,22,0.08);  color: #f97316; border-color: rgba(249,115,22,0.20); }

    .badge-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; flex-shrink: 0; }

    /* ── Period Dropdown ── */
    .rev-period-dropdown { display: flex; align-items: center; }

    .rev-period-select {
        appearance: none;
        -webkit-appearance: none;
        background-color: rgba(59,130,246,0.07);
        border: 1px solid rgba(59,130,246,0.20);
        border-radius: 100px;
        color: #3b82f6;
        font-size: 0.68rem;
        font-weight: 600;
        padding: 4px 26px 4px 10px;
        cursor: pointer;
        outline: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%233b82f6' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 9px center;
        transition: background-color 0.2s, border-color 0.2s;
    }
    .rev-period-select:hover {
        background-color: rgba(59,130,246,0.13);
        border-color: rgba(59,130,246,0.35);
    }

    .chart-canvas-wrap { position: relative; height: 260px; }
    .chart-canvas-wrap canvas { width: 100% !important; height: 100% !important; }

    /* ── Mobile tweaks ── */
    @media (max-width: 576px) {
        .stat-card  { min-height: 80px; border-radius: 14px; padding: 10px 12px !important; }
        .chart-canvas-wrap { height: 220px; }
        .chart-card { padding: 16px 14px 14px; }
        .chart-title-text { font-size: 0.85rem; }
        .rev-period-select { font-size: 0.65rem; }
    }

    /* ── Dark mode ── */
    [data-theme="dark"] .chart-card {
        background: #1e2130; border-color: #252836;
        box-shadow: 0 2px 16px rgba(0,0,0,0.30);
    }
    [data-theme="dark"] .chart-title-text  { color: #e8eaf0; }
    [data-theme="dark"] .chart-eyebrow     { color: #5a6070; }
    [data-theme="dark"] .chart-badge-blue  { background: rgba(59,130,246,0.12); border-color: rgba(59,130,246,0.25); }
    [data-theme="dark"] .chart-badge-orange{ background: rgba(249,115,22,0.12);  border-color: rgba(249,115,22,0.25); }
    [data-theme="dark"] .rev-period-select {
        background-color: rgba(59,130,246,0.12);
        border-color: rgba(59,130,246,0.25);
        color: #60a5fa;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%2360a5fa' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    }
</style>
@endsection