@if ($paginator->hasPages())
<div class="glass-pagination-container">

    <!-- Pagination Info -->
    <div class="pagination-info">
        <p>
            Showing
            <span class="font-bold">{{ $paginator->firstItem() }}</span>
            to
            <span class="font-bold">{{ $paginator->lastItem() }}</span>
            of
            <span class="font-bold">{{ $paginator->total() }}</span>
            results
        </p>
    </div>

    <!-- Pagination Links -->
    <nav role="navigation" aria-label="Pagination Navigation" class="pagination-links">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <span class="page-link disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
            <i class="fa-solid fa-chevron-left"></i>
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-link" aria-label="@lang('pagination.previous')">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <span class="page-link disabled" aria-disabled="true">{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <span class="page-link active" aria-current="page">{{ $page }}</span>
        @else
        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-link" aria-label="@lang('pagination.next')">
            <i class="fa-solid fa-chevron-right"></i>
        </a>
        @else
        <span class="page-link disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
            <i class="fa-solid fa-chevron-right"></i>
        </span>
        @endif
    </nav>
</div>

<style>
    .glass-pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        padding: 0.8rem 1.5rem;
        border-radius: 16px;
        margin-top: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .pagination-info p {
        margin: 0;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .pagination-info span.font-bold {
        font-weight: 800;
        color: #1e293b;
    }

    .pagination-links {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        color: #475569;
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.6);
    }

    .page-link:hover:not(.disabled) {
        background: #fff;
        color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        border-color: #3b82f6;
    }

    .page-link.active {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
    }

    .page-link.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: transparent;
        border-color: transparent;
    }

    @media (max-width: 640px) {
        .glass-pagination-container {
            flex-direction: column;
            justify-content: center;
            gap: 1rem;
            padding: 1rem;
        }

        .pagination-info {
            text-align: center;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endif