@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="glass-pagination-container">
    <ul class="glass-pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
            <span aria-hidden="true"><i class="fa-solid fa-chevron-left"></i></span>
        </li>
        @else
        <li>
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li class="active" aria-current="page"><span>{{ $page }}</span></li>
        @else
        <li><a href="{{ $url }}">{{ $page }}</a></li>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <li>
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </li>
        @else
        <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
            <span aria-hidden="true"><i class="fa-solid fa-chevron-right"></i></span>
        </li>
        @endif
    </ul>

    <div class="pagination-info">
        Showing {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }}
    </div>
</nav>

<style>
    .glass-pagination-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .glass-pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 6px;
    }

    .glass-pagination li {
        display: inline-flex;
    }

    .glass-pagination li a,
    .glass-pagination li span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #4b5563;
        text-decoration: none;
        background: rgba(255, 255, 255, 0.4);
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }

    .glass-pagination li a:hover {
        background: #fff;
        color: #111827;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
    }

    .glass-pagination li.active span {
        background: var(--accent-lime, #C6F048);
        color: #111827;
        font-weight: 800;
        box-shadow: 0 4px 10px rgba(198, 240, 72, 0.3);
        border: 1px solid rgba(198, 240, 72, 0.5);
    }

    .glass-pagination li.disabled span {
        color: #9ca3af;
        background: transparent;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .pagination-info {
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 500;
    }

    /* Mobile Responsive */
    @media (max-width: 640px) {
        .glass-pagination-container {
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
        }

        .pagination-info {
            text-align: center;
            order: -1;
        }
    }
</style>
@endif