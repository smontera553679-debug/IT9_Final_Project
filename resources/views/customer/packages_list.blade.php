@extends('layouts.customer')

@section('content')
<style>
    .package-grid-container {
        padding: clamp(16px, 3vw, 40px) clamp(12px, 2.5vw, 20px);
        background-color: var(--bg-body);
        min-height: 100vh;
        transition: background-color 0.3s ease;
    }

    .btn-back {
        display: inline-flex; align-items: center; gap: 8px;
        color: var(--text-muted); text-decoration: none;
        font-weight: 600; font-size: 0.9rem;
        padding: 8px 14px; border-radius: 8px;
        background: var(--bg-dropdown);
        border: 1px solid var(--border-color);
        transition: all 0.2s ease;
        margin-bottom: clamp(16px, 3vw, 30px);
    }
    .btn-back:hover { color: #a855f7; background: var(--btn-hover-bg); border-color: var(--btn-hover-border); transform: translateX(-4px); }

    .package-card {
        background: var(--bg-dropdown);
        border: 1px solid var(--border-color) !important;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%; display: flex; flex-direction: column;
    }
    .package-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,0.2); }
    .package-card.unavailable { opacity: 0.5; filter: grayscale(80%); pointer-events: none; }

    .package-img-wrapper { aspect-ratio: 4 / 3; width: 100%; position: relative; overflow: hidden; }
    .package-img-wrapper img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
    .package-card:hover .package-img-wrapper img { transform: scale(1.04); }

    .pkg-card-body { padding: clamp(16px, 2.5vw, 28px); flex-grow: 1; display: flex; flex-direction: column; }

    .package-title { color: #a855f7; font-size: clamp(1.1rem, 3vw, 1.6rem); font-weight: 800; margin-bottom: 10px; }

    .package-meta-row {
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        margin-bottom: 18px; font-size: clamp(0.85rem, 2vw, 1.1rem);
        color: var(--text-muted);
    }
    .price-text { font-weight: 800; color: var(--text-primary); }
    .meta-divider { color: var(--border-color); }
    .rating-badge { display: flex; align-items: center; gap: 5px; }
    .fa-star { color: #ffc107; }

    .btn-view-details {
        display: block; background-color: #a855f7; color: white;
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
        border-radius: 50px; padding: clamp(10px, 1.5vw, 14px) 0;
        border: none; width: 100%; text-decoration: none; text-align: center;
        margin-top: auto; transition: all 0.3s ease;
    }
    .btn-view-details:hover { background-color: #9333ea; color: white; box-shadow: 0 5px 15px rgba(168,85,247,0.3); }

    .unavailable-badge {
        display: block; background: var(--text-muted); color: var(--bg-body);
        font-size: 0.72rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 1px; border-radius: 50px; padding: 12px 0;
        width: 100%; text-align: center; margin-top: auto;
    }
</style>

<div class="package-grid-container">
    <div class="container-fluid px-0 px-sm-2">
        <a href="{{ route('customer.destinations') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Back to Destinations
        </a>
        <div class="row g-3 g-md-4">
            @forelse($packages as $package)
                @php $isActive = $package->status == 'active'; @endphp
                <div class="col-6 col-md-4 col-lg-4">
                    <div class="package-card {{ !$isActive ? 'unavailable' : '' }}">
                        <div class="package-img-wrapper">
                            @php
                                $decoded = is_string($package->images) ? json_decode($package->images, true) : $package->images;
                                $displayImage = (is_array($decoded) && count($decoded) > 0) ? $decoded[0] : $package->image;
                            @endphp
                            <img src="{{ $displayImage ? asset('storage/' . $displayImage) : asset('images/default.png') }}" alt="{{ $package->name }}">
                        </div>
                        <div class="pkg-card-body">
                            <h2 class="package-title">{{ $package->name }}</h2>
                            <div class="package-meta-row">
                                <span class="price-text">₱{{ number_format($package->price_per_person, 0) }}</span>
                                <span class="meta-divider">|</span>
                                <span>{{ $package->duration_days }}D{{ $package->duration_days - 1 }}N</span>
                                <span class="meta-divider">|</span>
                                <div class="rating-badge">
                                    <i class="fa-solid fa-star"></i>
                                    <span>{{ $package->rating ?? '4' }}</span>
                                </div>
                            </div>
                            @if($isActive)
                                <a href="{{ route('customer.package_details', $package->id) }}" class="btn-view-details">View Details</a>
                            @else
                                <span class="unavailable-badge">Unavailable</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No tour packages found for this destination.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection