@extends('layouts.customer')

@section('content')
<style>
    .hero-section {
        background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.25)),
                    url('{{ asset("images/cover.jpg") }}');
        background-size: cover; background-position: center;
        min-height: 420px; display: flex; align-items: center; padding: 60px 0;
    }
    .hero-title { font-size:clamp(1.8rem,5vw,3.5rem);font-weight:800;font-style:italic;line-height:1.2;color:#fff;text-shadow:2px 2px 8px rgba(0,0,0,0.5); }
    .hero-text  { font-size:clamp(0.9rem,2vw,1.15rem);max-width:650px;color:#fff;text-shadow:1px 1px 4px rgba(0,0,0,0.5);line-height:1.6; }

    /* ── Sections ── */
    .section-destinations { background-color: var(--bg-body); transition: background-color 0.3s ease; }
    .section-packages     { background-color: var(--bg-dropdown); transition: background-color 0.3s ease; }

    .section-destinations h2,
    .section-packages h2 { color: var(--text-title) !important; }
    .section-destinations .text-muted,
    .section-packages .text-muted { color: var(--text-muted) !important; }

    /* ── Destination card ── */
    .destination-card { transition:transform 0.3s ease;border-radius:15px;height:100%;cursor:pointer; }
    .destination-card:hover { transform:translateY(-8px); }
    .dest-img { height:clamp(180px,25vw,300px);object-fit:cover; }

    .destination-card.card {
        background-color: var(--bg-dropdown) !important;
        border: 1px solid var(--border-color) !important;
    }
    .destination-card .card-body {
        background-color: var(--bg-dropdown) !important;
        text-align: center !important;
    }
    .destination-card .card-body h5 { color: var(--text-title) !important; }
    .destination-card .card-body small { color: var(--text-muted) !important; }

    /* ── Package card ── */
    .package-card { border-radius:15px;transition:all 0.3s ease; }
    .package-card img { border-top-left-radius:15px;border-top-right-radius:15px;height:clamp(160px,20vw,250px);object-fit:cover;width:100%; }
    .package-card:hover { box-shadow:0 15px 30px rgba(0,0,0,0.2) !important; }

    .package-card.card {
        background-color: var(--bg-dropdown) !important;
        border: 1px solid var(--border-color) !important;
    }
    .package-card .card-body,
    .package-card .card-footer {
        background-color: var(--bg-dropdown) !important;
        border-color: var(--border-color) !important;
    }
    .package-card .card-body h5   { color: var(--text-title) !important; }
    .package-card .card-body p    { color: var(--text-muted) !important; }
    .package-card .card-body .text-primary { color: #a855f7 !important; }
    .package-card .card-body .text-warning { color: #fbbf24 !important; }
    .package-card .card-body .text-muted   { color: var(--text-muted) !important; }
    .package-card .card-body .fw-bold.text-primary { color: #a855f7 !important; }

    .package-card .btn-outline-primary {
        border-color: #a855f7 !important; color: #a855f7 !important;
        background: transparent !important;
    }
    .package-card .btn-outline-primary:hover {
        background-color: rgba(168,85,247,0.1) !important;
    }

    /* ── Horizontal scroll ── */
    .h-scroll-row { display:flex;flex-wrap:nowrap;overflow-x:auto;-webkit-overflow-scrolling:touch;gap:14px;padding-bottom:10px;scrollbar-width:thin;scrollbar-color:var(--border-color) transparent; }
    .h-scroll-row::-webkit-scrollbar { height:5px; }
    .h-scroll-row::-webkit-scrollbar-thumb { background:var(--border-color);border-radius:10px; }
    .h-scroll-dest { flex:0 0 72vw;max-width:72vw; }
    .h-scroll-pkg  { flex:0 0 80vw;max-width:80vw; }

    @media (max-width:575px) {
        .hero-section { min-height:320px;padding:40px 0; }
        .package-price { font-size:1.25rem !important; }
        .dest-grid-wrap { display:none !important; }
        .pkg-grid-wrap  { display:none !important; }
        .dest-scroll-wrap { display:flex !important; }
        .pkg-scroll-wrap  { display:flex !important; }
    }
    .dest-scroll-wrap, .pkg-scroll-wrap { display:none; }
</style>

{{-- HERO --}}
<div class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-11">
                <h1 class="hero-title mb-3">Indulge in Journeys Worth Remembering</h1>
                <p class="hero-text mb-0">
                    Discover breathtaking destinations, unforgettable adventures, and curated travel experiences designed just for you.
                    Whether you're craving a sun-soaked escape, a thrilling expedition, or a cultural city tour — <strong>ByteTrip</strong> brings the world closer to you.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- POPULAR DESTINATIONS --}}
<section id="destinations" class="section-destinations py-4 py-md-5">
    <div class="container">
        <div class="text-center mb-4 mb-md-5">
            <h2 class="fw-bold">Popular Destinations</h2>
            <p class="text-muted">Handpicked locations for your next escape</p>
        </div>

        {{-- Desktop Grid --}}
        <div class="row g-3 g-md-4 dest-grid-wrap">
            @forelse($popular as $dest)
                <div class="col-6 col-md-4">
                    <div class="card border-0 shadow-sm destination-card overflow-hidden">
                        <img src="{{ $dest->image ? asset('storage/' . $dest->image) : 'https://images.unsplash.com/photo-1537996194471-e657df975ab4' }}"
                             class="card-img-top dest-img" alt="{{ $dest->name }}">
                        <div class="card-body p-2 p-md-3 text-center">
                            <h5 class="fw-bold mb-0" style="font-size:clamp(0.85rem,2vw,1rem);">{{ $dest->name }}</h5>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4"><p class="text-muted">No popular destinations marked yet.</p></div>
            @endforelse
        </div>

        {{-- Mobile Horizontal Scroll --}}
        <div class="h-scroll-row dest-scroll-wrap">
            @forelse($popular as $dest)
                <div class="h-scroll-dest">
                    <div class="card border-0 shadow-sm destination-card overflow-hidden" style="height:100%;">
                        <img src="{{ $dest->image ? asset('storage/' . $dest->image) : 'https://images.unsplash.com/photo-1537996194471-e657df975ab4' }}"
                             class="card-img-top" style="height:155px;object-fit:cover;" alt="{{ $dest->name }}">
                        <div class="card-body p-2 text-center">
                            <h5 class="fw-bold mb-0" style="font-size:0.9rem;">{{ $dest->name }}</h5>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted py-4">No popular destinations marked yet.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- FEATURED PACKAGES --}}
<section id="packages" class="section-packages py-4 py-md-5">
    <div class="container">
        <div class="text-center mb-4 mb-md-5">
            <h2 class="fw-bold">Featured Tour Packages</h2>
            <p class="text-muted mb-0">Unbeatable prices for premium experiences</p>
        </div>

        {{-- Desktop Grid --}}
        <div class="row g-3 g-md-4 pkg-grid-wrap">
            @forelse($featured as $package)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 border-0 shadow package-card">
                        <div class="position-relative">
                            <img src="{{ $package->image ? asset('storage/' . $package->image) : asset('images/cover.jpg') }}" class="card-img-top" alt="{{ $package->name }}">
                            @if($package->is_featured)
                                <span class="badge bg-primary position-absolute top-0 end-0 m-2 shadow-sm" style="z-index:10;background:#a855f7 !important;">Featured</span>
                            @endif
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex justify-content-between mb-2 small text-muted">
                                <span><i class="fa fa-calendar-alt me-1" style="color:#a855f7;"></i> {{ $package->duration_days }} Days</span>
                                <span><i class="fa fa-user me-1" style="color:#a855f7;"></i> Max {{ $package->max_group_size ?? 'N/A' }}</span>
                            </div>
                            <h5 class="fw-bold mb-2" style="font-size:clamp(0.9rem,2.5vw,1.1rem);">{{ $package->name }}</h5>
                            <p class="text-muted small mb-3">{{ Str::limit($package->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold text-primary package-price" style="font-size:clamp(1.1rem,3vw,1.4rem);">
                                        {{ $package->currency }} {{ number_format($package->price_per_person) }}
                                    </span>
                                    <span class="text-muted small">/person</span>
                                </div>
                                <div class="text-warning fw-bold small">
                                    <i class="fa fa-star"></i> {{ number_format($package->rating, 1) }}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-0 p-3 p-md-4 pt-0">
                            <a href="{{ route('customer.package_details', $package->id) }}" class="btn btn-outline-primary rounded-pill w-100 fw-bold">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No featured packages available at the moment.</p>
                </div>
            @endforelse
        </div>

        {{-- Mobile Horizontal Scroll --}}
        <div class="h-scroll-row pkg-scroll-wrap">
            @forelse($featured as $package)
                <div class="h-scroll-pkg">
                    <div class="card h-100 border-0 shadow package-card">
                        <div class="position-relative">
                            <img src="{{ $package->image ? asset('storage/' . $package->image) : asset('images/cover.jpg') }}" class="card-img-top" style="height:155px;object-fit:cover;" alt="{{ $package->name }}">
                            @if($package->is_featured)
                                <span class="badge position-absolute top-0 end-0 m-2 shadow-sm" style="z-index:10;background:#a855f7;">Featured</span>
                            @endif
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between mb-2 small text-muted">
                                <span><i class="fa fa-calendar-alt me-1" style="color:#a855f7;"></i> {{ $package->duration_days }} Days</span>
                                <span><i class="fa fa-user me-1" style="color:#a855f7;"></i> Max {{ $package->max_group_size ?? 'N/A' }}</span>
                            </div>
                            <h5 class="fw-bold mb-2" style="font-size:0.95rem;">{{ $package->name }}</h5>
                            <p class="text-muted small mb-3">{{ Str::limit($package->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold text-primary" style="font-size:1.1rem;">{{ $package->currency }} {{ number_format($package->price_per_person) }}</span>
                                    <span class="text-muted small">/person</span>
                                </div>
                                <div class="text-warning fw-bold small"><i class="fa fa-star"></i> {{ number_format($package->rating, 1) }}</div>
                            </div>
                        </div>
                        <div class="card-footer border-0 p-3 pt-0">
                            <a href="{{ route('customer.package_details', $package->id) }}" class="btn btn-outline-primary rounded-pill w-100 fw-bold">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted py-4">No featured packages available at the moment.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection