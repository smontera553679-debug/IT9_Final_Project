@extends('layouts.customer')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap');

    .package-detail-wrap {
        font-family: 'DM Sans', sans-serif;
        background-color: var(--bg-body);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* ── Hero ── */
    .pkg-hero { position:relative;width:100%;height:clamp(320px,60vw,88vh);min-height:300px;overflow:hidden;background:var(--bg-body); }
    .pkg-hero-img { width:100%;height:100%;object-fit:cover;opacity:0.72;transform:scale(1.04);transition:transform 8s ease; }
    .pkg-hero:hover .pkg-hero-img { transform:scale(1); }
    .pkg-hero-overlay { position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,16,0.88) 0%,rgba(10,10,16,0.15) 55%,transparent 100%); }
    .pkg-hero-content { position:absolute;bottom:0;left:0;right:0;padding:clamp(20px,4vw,56px) clamp(16px,5vw,5vw) clamp(24px,4vw,4rem);display:flex;align-items:flex-end;justify-content:space-between;gap:1.5rem;flex-wrap:wrap; }
    .pkg-hero-left { max-width:680px;min-width:0; }
    .pkg-eyebrow { display:inline-flex;align-items:center;gap:10px;color:#e8d9b5;font-size:0.7rem;font-weight:500;letter-spacing:0.2em;text-transform:uppercase;margin-bottom:0.75rem; }
    .pkg-eyebrow::before { content:'';display:block;width:24px;height:1px;background:#b89a5a; }
    .pkg-hero-title { font-family:'Cormorant Garamond',serif;font-size:clamp(1.8rem,5vw,5rem);font-weight:300;line-height:1.08;color:#fff;margin:0 0 1rem;letter-spacing:-0.02em; }
    .pkg-hero-title em { font-style:italic;color:#e8d9b5; }
    .pkg-hero-pills { display:flex;flex-wrap:wrap;gap:8px; }
    .pkg-pill { display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.10);border:1px solid rgba(255,255,255,0.2);backdrop-filter:blur(8px);color:rgba(255,255,255,0.9);font-size:clamp(0.68rem,1.5vw,0.78rem);font-weight:500;padding:6px 12px;border-radius:100px;letter-spacing:0.03em; }
    .pkg-pill i { color:#e8d9b5;font-size:0.65rem; }
    .pkg-hero-price { text-align:right;color:#fff;flex-shrink:0; }
    .pkg-hero-price .label { font-size:0.68rem;letter-spacing:0.15em;text-transform:uppercase;color:rgba(255,255,255,0.55);margin-bottom:4px; }
    .pkg-hero-price .amount { font-family:'Cormorant Garamond',serif;font-size:clamp(1.8rem,4vw,3.6rem);font-weight:300;line-height:1;letter-spacing:-0.02em; }
    .pkg-hero-price .per { font-size:0.72rem;color:rgba(255,255,255,0.55);margin-top:4px; }
    .star-row { display:flex;gap:3px;margin-top:10px; }
    .star-row i { color:#b89a5a;font-size:0.78rem; }
    .star-row i.empty { color:rgba(255,255,255,0.25); }

    /* ── Body ── */
    .pkg-body { max-width:1200px;margin:0 auto;padding:clamp(24px,4vw,4rem) clamp(16px,5vw,5vw) clamp(40px,6vw,6rem); }

    .pkg-back { display:inline-flex;align-items:center;gap:8px;color:var(--text-muted);text-decoration:none;font-size:0.78rem;font-weight:500;letter-spacing:0.05em;text-transform:uppercase;padding:0.5rem 0;border-bottom:1px solid transparent;transition:all 0.2s;margin-bottom:clamp(20px,3vw,3rem); }
    .pkg-back:hover { color:#b89a5a;border-color:#b89a5a; }

    .pkg-description { font-family:'Cormorant Garamond',serif;font-size:clamp(1rem,2.5vw,1.35rem);font-weight:300;line-height:1.8;color:var(--text-muted);max-width:760px;margin-bottom:clamp(24px,4vw,4rem);font-style:italic;border-left:2px solid #b89a5a;padding-left:clamp(12px,2vw,1.75rem); }

    .section-label { font-size:0.66rem;font-weight:600;letter-spacing:0.22em;text-transform:uppercase;color:#b89a5a;margin-bottom:1rem;display:flex;align-items:center;gap:10px; }
    .section-label::after { content:'';flex:1;height:1px;background:var(--border-color);max-width:60px; }

    /* ── Info grid ── */
    .pkg-info-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:2px;background:var(--border-color);border:1px solid var(--border-color);border-radius:12px;overflow:hidden;margin-bottom:clamp(24px,4vw,4rem); }
    .pkg-info-cell { background:var(--bg-dropdown);padding:clamp(12px,2vw,1.4rem) clamp(14px,2vw,1.6rem);display:flex;flex-direction:column;gap:3px;transition:background-color 0.3s ease; }
    .pkg-info-cell .cell-label { font-size:0.65rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--text-muted);font-weight:500; }
    .pkg-info-cell .cell-value { font-family:'Cormorant Garamond',serif;font-size:clamp(1rem,2vw,1.25rem);font-weight:600;color:var(--text-title); }

    /* ── Cards row ── */
    .pkg-cards-row { display:grid;grid-template-columns:repeat(3,1fr);gap:clamp(12px,2vw,24px);margin-bottom:clamp(24px,4vw,4rem);align-items:start; }
    .pkg-card { background:var(--bg-dropdown);border:1px solid var(--border-color);border-radius:12px;overflow:hidden;transition:background-color 0.3s ease; }
    .pkg-card-head { padding:clamp(10px,1.5vw,1rem) clamp(12px,1.5vw,1.4rem);border-bottom:1px solid var(--border-color);display:flex;align-items:center;gap:10px; }
    .pkg-card-head .head-icon { width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.7rem; }
    .pkg-card-head .head-title { font-size:0.68rem;font-weight:600;letter-spacing:0.14em;text-transform:uppercase;color:var(--text-title); }

    /* ── Itinerary rows ── */
    .itinerary-item-row { display:flex;gap:0;border-bottom:1px solid var(--border-color); }
    .itinerary-item-row:last-child { border-bottom:none; }
    .day-num {
        min-width:56px;
        padding:0.8rem 0;
        text-align:center;
        font-family:'Cormorant Garamond',serif;
        font-size:1rem;
        font-weight:600;
        color:#b89a5a;
        border-right:1px solid var(--border-color);
        background:var(--bg-body);
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        gap:3px;
    }
    .day-num .day-number { line-height:1; }
    .day-num .day-transport { font-size:1rem;line-height:1; }
    .day-text { padding:0.8rem 0.9rem;font-size:0.82rem;color:var(--text-muted);line-height:1.5; }

    .inc-exc-item { display:flex;align-items:flex-start;gap:9px;padding:0.65rem clamp(10px,1.5vw,1.4rem);border-bottom:1px solid var(--border-color);font-size:0.82rem;color:var(--text-muted);line-height:1.45; }
    .inc-exc-item:last-child { border-bottom:none; }
    .inc-exc-item i { margin-top:2px;font-size:0.72rem; }

    /* ── CTA Bar ── */
    .pkg-cta-bar { background:var(--bg-navbar);border:1px solid var(--border-color);border-radius:14px;padding:clamp(20px,3vw,2.5rem) clamp(20px,3vw,3rem);display:flex;align-items:center;justify-content:space-between;gap:1.5rem;flex-wrap:wrap;transition:background-color 0.3s ease; }
    .pkg-cta-bar .cta-title { font-family:'Cormorant Garamond',serif;font-size:clamp(1.3rem,3vw,1.9rem);font-weight:300;color:var(--text-title);line-height:1.2;margin-bottom:4px; }
    .pkg-cta-bar .cta-sub { font-size:0.78rem;color:var(--text-muted); }

    .btn-book-now { display:inline-flex;align-items:center;gap:10px;background:#b89a5a;color:#0f1117 !important;text-decoration:none;font-weight:600;font-size:clamp(0.78rem,1.5vw,0.85rem);letter-spacing:0.1em;text-transform:uppercase;padding:clamp(12px,1.5vw,1rem) clamp(20px,2.5vw,2.5rem);border-radius:100px;border:none;transition:all 0.25s ease;white-space:nowrap; }
    .btn-book-now:hover { background:#e8d9b5;transform:translateY(-2px);box-shadow:0 10px 30px rgba(184,154,90,0.35); }

    .carousel-control-prev,.carousel-control-next { width:44px;height:44px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,0.15);backdrop-filter:blur(6px);border-radius:50%;border:1px solid rgba(255,255,255,0.3); }
    .carousel-control-prev { left:14px; }
    .carousel-control-next { right:14px; }

    @media (max-width:900px) {
        .pkg-cards-row { grid-template-columns:1fr; }
        .pkg-info-grid  { grid-template-columns:1fr 1fr; }
        .pkg-hero-content { flex-direction:column;align-items:flex-start; }
        .pkg-hero-price { text-align:left; }
        .pkg-cta-bar { flex-direction:column;align-items:flex-start; }
        .btn-book-now { width:100%;justify-content:center; }
    }
    @media (max-width:540px) {
        .pkg-info-grid { grid-template-columns:1fr; }
        .pkg-hero-pills { gap:6px; }
        .pkg-pill { font-size:0.65rem;padding:5px 10px; }
    }
</style>

@php
    /* ── Transport maps ── */
    $transportEmojis = [
        'van'    => '🚐',
        'flight' => '✈️',
        'bus'    => '🚌',
        'boat'   => '⛵',
        'train'  => '🚂',
        'walk'   => '🚶',
        'car'    => '🚗',
        'bike'   => '🏍️',
    ];

    $transportFaIcons = [
        'van'    => 'fa-van-shuttle',
        'flight' => 'fa-plane',
        'bus'    => 'fa-bus',
        'boat'   => 'fa-sailboat',
        'train'  => 'fa-train',
        'walk'   => 'fa-person-walking',
        'car'    => 'fa-car',
        'bike'   => 'fa-motorcycle',
    ];

    $categoryIcons = [
        'Beach'      => 'fa-umbrella-beach',
        'Mountain'   => 'fa-mountain',
        'City'       => 'fa-city',
        'Historical' => 'fa-landmark',
    ];

    $toArray = function ($value) use (&$toArray): array {
        if (is_array($value))  return $value;
        if (empty($value))     return [];
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if (is_array($decoded))  return $decoded;
                if (is_string($decoded)) return $toArray($decoded);
            }
            return array_values(array_filter(array_map('trim', explode(',', $value))));
        }
        return [];
    };

    $packageTransports = $toArray($package->getRawOriginal('transport'));
    $itinerary         = $toArray($package->getRawOriginal('itinerary'));
    $inclusions        = $toArray($package->getRawOriginal('inclusions'));
    $exclusions        = $toArray($package->getRawOriginal('exclusions'));
    $packageImages     = $toArray($package->getRawOriginal('images'));

    if (empty($packageImages) && $package->image) {
        $packageImages = [$package->image];
    }

    $heroImage = count($packageImages) > 0
        ? asset('storage/' . $packageImages[0])
        : asset('images/default.png');

    $firstTransport = $packageTransports[0] ?? 'van';
    $heroPillIcon   = $transportFaIcons[$firstTransport] ?? 'fa-van-shuttle';
    $heroPillLabel  = ucfirst($firstTransport);

    $rating = $package->rating ?? 4;

    $uniqueTransports = array_unique($packageTransports);
    $transportDisplay = array_map(
        fn($t) => ($transportEmojis[$t] ?? '🚐') . ' ' . ucfirst($t),
        $uniqueTransports
    );

    $category     = $package->category ?? null;
    $categoryIcon = $categoryIcons[$category] ?? 'fa-tag';
@endphp

<div class="package-detail-wrap">

    {{-- ── Hero ── --}}
    <div class="pkg-hero">

        @if(count($packageImages) > 1)
            <div id="heroCarousel" class="carousel slide h-100" data-bs-ride="carousel">
                <div class="carousel-inner h-100">
                    @foreach($packageImages as $index => $image)
                        <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image) }}" class="pkg-hero-img" alt="{{ $package->name }}">
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <i class="fa-solid fa-chevron-left text-white"></i>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <i class="fa-solid fa-chevron-right text-white"></i>
                </button>
            </div>
        @else
            <img src="{{ $heroImage }}" class="pkg-hero-img" alt="{{ $package->name }}">
        @endif

        <div class="pkg-hero-overlay"></div>
        <div class="pkg-hero-content">
            <div class="pkg-hero-left">
                <div class="pkg-eyebrow">{{ $package->destination->country ?? 'Tour Package' }}</div>
                <h1 class="pkg-hero-title">{{ $package->name }}</h1>
                <div class="pkg-hero-pills">
                    <span class="pkg-pill">
                        <i class="fa-solid fa-calendar-days"></i>
                        {{ $package->duration_days }}D / {{ $package->duration_days - 1 }}N
                    </span>
                    <span class="pkg-pill">
                        <i class="fa-solid fa-users"></i>
                        Max {{ $package->max_group_size }} pax
                    </span>
                    <span class="pkg-pill">
                        <i class="fa-solid {{ $heroPillIcon }}"></i>
                        {{ $heroPillLabel }}
                    </span>
                    <span class="pkg-pill">
                        <i class="fa-solid fa-globe"></i>
                        {{ $package->language }}
                    </span>
                    @if($category)
                    <span class="pkg-pill">
                        <i class="fa-solid {{ $categoryIcon }}"></i>
                        {{ $category }}
                    </span>
                    @endif
                </div>
                <div class="star-row">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star {{ $i <= $rating ? '' : 'empty' }}"></i>
                    @endfor
                </div>
            </div>
            <div class="pkg-hero-price">
                <div class="label">Starting from</div>
                <div class="amount">₱{{ number_format($package->price_per_person, 0) }}</div>
                <div class="per">per person</div>
            </div>
        </div>
    </div>

    {{-- ── Body ── --}}
    <div class="pkg-body">

        <a href="{{ route('customer.destinations') }}" class="pkg-back">
            <i class="fa-solid fa-arrow-left"></i> All Destinations
        </a>

        <p class="pkg-description">{{ $package->description }}</p>

        <div class="section-label">Trip Details</div>
        <div class="pkg-info-grid">
            <div class="pkg-info-cell">
                <span class="cell-label">Duration</span>
                <span class="cell-value">{{ $package->duration_days }}D / {{ $package->duration_days - 1 }}N</span>
            </div>
            <div class="pkg-info-cell">
                <span class="cell-label">Group Size</span>
                <span class="cell-value">Up to {{ $package->max_group_size }} people</span>
            </div>
            <div class="pkg-info-cell">
                <span class="cell-label">Language</span>
                <span class="cell-value">{{ $package->language }}</span>
            </div>
            <div class="pkg-info-cell">
                <span class="cell-label">Currency</span>
                <span class="cell-value">{{ $package->currency ?? 'PHP' }}</span>
            </div>
            <div class="pkg-info-cell">
                <span class="cell-label">Rating</span>
                <span class="cell-value">{{ $rating }} / 5 Stars</span>
            </div>
            <div class="pkg-info-cell">
                <span class="cell-label">Transport</span>
                <span class="cell-value">
                    {{ implode(', ', $transportDisplay) ?: ucfirst($firstTransport) }}
                </span>
            </div>
            @if($category)
            <div class="pkg-info-cell">
                <span class="cell-label">Category</span>
                <span class="cell-value">
                    <i class="fa-solid {{ $categoryIcon }}" style="font-size:0.9rem;color:#b89a5a;margin-right:4px;"></i>
                    {{ $category }}
                </span>
            </div>
            @endif
        </div>

        <div class="section-label">What's Included</div>
        <div class="pkg-cards-row">

            {{-- ── Itinerary Card ── --}}
            <div class="pkg-card">
                <div class="pkg-card-head">
                    <div class="head-icon" style="background:rgba(184,154,90,0.15);">
                        <i class="fa-solid fa-route" style="color:#b89a5a;"></i>
                    </div>
                    <span class="head-title">Itinerary</span>
                </div>
                @forelse($itinerary as $index => $desc)
                    @php
                        $dayTransport = $packageTransports[$index] ?? 'van';
                        $dayEmoji     = $transportEmojis[$dayTransport] ?? '🚐';
                    @endphp
                    <div class="itinerary-item-row">
                        <div class="day-num">
                            <span class="day-number">{{ $index + 1 }}</span>
                            <span class="day-transport" title="{{ ucfirst($dayTransport) }}">{{ $dayEmoji }}</span>
                        </div>
                        <div class="day-text">{{ $desc }}</div>
                    </div>
                @empty
                    <div class="inc-exc-item" style="color:var(--text-muted);">No itinerary available.</div>
                @endforelse
            </div>

            {{-- ── Inclusions Card ── --}}
            <div class="pkg-card">
                <div class="pkg-card-head">
                    <div class="head-icon" style="background:rgba(34,197,94,0.15);">
                        <i class="fa-solid fa-circle-check" style="color:#22c55e;"></i>
                    </div>
                    <span class="head-title">Inclusions</span>
                </div>
                @forelse($inclusions as $inc)
                    <div class="inc-exc-item">
                        <i class="fa-solid fa-check" style="color:#22c55e;margin-top:3px;"></i>
                        <span>{{ trim($inc) }}</span>
                    </div>
                @empty
                    <div class="inc-exc-item" style="color:var(--text-muted);">None listed.</div>
                @endforelse
            </div>

            {{-- ── Exclusions Card ── --}}
            <div class="pkg-card">
                <div class="pkg-card-head">
                    <div class="head-icon" style="background:rgba(239,68,68,0.15);">
                        <i class="fa-solid fa-circle-xmark" style="color:#ef4444;"></i>
                    </div>
                    <span class="head-title">Exclusions</span>
                </div>
                @forelse($exclusions as $exc)
                    <div class="inc-exc-item">
                        <i class="fa-solid fa-xmark" style="color:#ef4444;margin-top:3px;"></i>
                        <span>{{ trim($exc) }}</span>
                    </div>
                @empty
                    <div class="inc-exc-item" style="color:var(--text-muted);">None listed.</div>
                @endforelse
            </div>

        </div>

        {{-- ── CTA Bar ── --}}
        <div class="pkg-cta-bar">
            <div>
                <div class="cta-title">Ready to explore?</div>
                <div class="cta-sub">Secure your spot — limited availability per departure.</div>
            </div>
            <a href="{{ route('customer.checkout', $package->id) }}" class="btn-book-now">
                Book This Package <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

    </div>
</div>
@endsection