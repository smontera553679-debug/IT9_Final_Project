@if($paginator->hasPages())
<nav class="pagination-nav" aria-label="{{ $label ?? 'Page navigation' }}">
    <p class="pagination-info mb-0">
        Showing <span>{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
        of <span>{{ $paginator->total() }}</span> {{ $label ?? 'records' }}
    </p>
    <div class="pagination-links">

        {{-- Prev --}}
        @if($paginator->onFirstPage())
            <span class="page-btn nav-btn disabled" aria-disabled="true">← Prev</span>
        @else
            <a class="page-btn nav-btn" href="{{ $paginator->previousPageUrl() }}">← Prev</a>
        @endif

        {{-- Page numbers with ellipsis --}}
        @php
            $current  = $paginator->currentPage();
            $last     = $paginator->lastPage();
            $dotShown = ['left' => false, 'right' => false];
        @endphp

        @for($page = 1; $page <= $last; $page++)
            @if($page === 1 || $page === $last || abs($page - $current) <= 1)
                @php $dotShown = ['left' => false, 'right' => false]; @endphp
                <a class="page-btn {{ $page === $current ? 'active' : '' }}"
                   href="{{ $paginator->url($page) }}"
                   @if($page === $current) aria-current="page" @endif>
                    {{ $page }}
                </a>
            @elseif($page < $current && !$dotShown['left'])
                <span class="page-dots">…</span>
                @php $dotShown['left'] = true; @endphp
            @elseif($page > $current && !$dotShown['right'])
                <span class="page-dots">…</span>
                @php $dotShown['right'] = true; @endphp
            @endif
        @endfor

        {{-- Next --}}
        @if($paginator->hasMorePages())
            <a class="page-btn nav-btn" href="{{ $paginator->nextPageUrl() }}">Next →</a>
        @else
            <span class="page-btn nav-btn disabled" aria-disabled="true">Next →</span>
        @endif

    </div>
</nav>
@endif