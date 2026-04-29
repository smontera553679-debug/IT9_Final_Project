@extends('layouts.customer')

@section('content')
<style>
    .scrollable-feed {
        width: 100%; padding: clamp(16px, 3vw, 30px);
        background-color: var(--bg-body); min-height: 100vh;
        transition: background-color 0.3s ease;
    }

    .destination-card {
        display: flex; flex-direction: row; width: 100%;
        background: var(--bg-dropdown);
        min-height: 260px; margin-bottom: clamp(16px, 2.5vw, 30px);
        border-radius: 18px; overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
    }
    .destination-card:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0,0,0,0.18); }
    .destination-card.unavailable { opacity: 0.5; filter: grayscale(80%); pointer-events: none; }

    .card-img-wrapper { width: 38%; flex-shrink: 0; }
    .card-img-left { height: 100%; width: 100%; object-fit: cover; display: block; }

    .card-content { padding: clamp(20px, 3vw, 50px); flex: 1; display: flex; flex-direction: column; justify-content: space-between; min-width: 0; }

    .dest-info-top h2 { color: #a855f7; font-size: clamp(1.4rem, 4vw, 3rem); font-weight: 900; margin-bottom: 8px; }
    .dest-motto { font-size: clamp(0.9rem, 2vw, 1.4rem); font-style: italic; color: var(--text-muted); margin-bottom: 12px; font-family: 'Georgia', serif; }
    .dest-description { font-size: clamp(0.85rem, 1.5vw, 1.1rem); color: var(--text-muted); line-height: 1.7; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

    .btn-view-packages {
        display: inline-block; background-color: #a855f7; color: white;
        font-size: clamp(0.8rem, 1.5vw, 1rem); font-weight: 800;
        text-transform: uppercase; letter-spacing: 1px; border-radius: 10px;
        padding: clamp(10px, 1.5vw, 16px) clamp(18px, 3vw, 36px);
        border: none; text-decoration: none; align-self: flex-start;
        margin-top: 16px; transition: all 0.3s ease;
    }
    .btn-view-packages:hover { background-color: #9333ea; color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(168,85,247,0.3); }

    .unavailable-badge {
        display: inline-block; background: var(--text-muted); color: var(--bg-body);
        font-size: 0.75rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 1px; border-radius: 50px; padding: 10px 24px;
        margin-top: 16px; align-self: flex-start;
    }

    @media (max-width: 640px) {
        .destination-card { flex-direction: column; min-height: unset; }
        .card-img-wrapper { width: 100%; height: 200px; }
        .card-content { padding: 20px; }
        .btn-view-packages, .unavailable-badge { align-self: stretch; text-align: center; }
    }
    @media (max-width: 400px) { .card-img-wrapper { height: 160px; } }
</style>

<div class="scrollable-feed">
    @forelse($destinations as $dest)
        @php $isActive = $dest->status == 'active'; @endphp
        <div class="destination-card {{ !$isActive ? 'unavailable' : '' }}">
            <div class="card-img-wrapper">
                <img src="{{ $dest->image ? asset('storage/' . $dest->image) : 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34' }}"
                     class="card-img-left" alt="{{ $dest->name }}">
            </div>
            <div class="card-content">
                <div class="dest-info-top">
                    <h2>{{ $dest->name }}</h2>
                    @if($dest->title)
                        <div class="dest-motto">"{{ $dest->title }}"</div>
                    @endif
                    <p class="dest-description">{{ $dest->description }}</p>
                </div>
                @if($isActive)
                    <a href="{{ route('customer.destination.packages', $dest->id) }}" class="btn-view-packages">Explore Packages &rarr;</a>
                @else
                    <span class="unavailable-badge">Currently Unavailable</span>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted">No destinations available.</div>
    @endforelse
</div>
@endsection