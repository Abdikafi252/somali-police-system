@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="glass-pagination-container">
    <div class="glass-pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <span class="pagination-btn disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
            <i class="fa-solid fa-chevron-left"></i>
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-btn" aria-label="@lang('pagination.previous')">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <span class="pagination-separator" aria-disabled="true">{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <span class="pagination-btn active" aria-current="page">{{ $page }}</span>
        @else
        <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-btn" aria-label="@lang('pagination.next')">
            <i class="fa-solid fa-chevron-right"></i>
        </a>
        @else
        <span class="pagination-btn disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
            <i class="fa-solid fa-chevron-right"></i>
        </span>
        @endif
    </div>

    <div class="pagination-info">
        Waxaa la muujinayaa <span class="font-bold">{{ $paginator->firstItem() }}</span> ilaa <span class="font-bold">{{ $paginator->lastItem() }}</span> oo ka mid ah <span class="font-bold">{{ $paginator->total() }}</span> natiijo
    </div>
</nav>

<style>
    .glass-pagination-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.2rem;
        margin-top: 2.5rem;
        width: 100%;
        padding: 1rem 0;
    }

    .glass-pagination {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.6rem 0.8rem;
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(16px) !important;
        -webkit-backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 20px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
    }

    .pagination-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 700;
        color: #4b5563;
        background: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .pagination-btn:hover:not(.disabled) {
        background: #ffffff;
        color: #4f46e5;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2);
        border-color: #4f46e5;
    }

    .pagination-btn.active {
        background: #4f46e5;
        /* Primary Color */
        color: white;
        box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
        border: 1px solid rgba(79, 70, 229, 0.5);
        background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
        transform: scale(1.05);
    }

    .pagination-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: rgba(0, 0, 0, 0.02);
        color: #9ca3af;
    }

    .pagination-separator {
        padding: 0 0.5rem;
        color: #9ca3af;
        font-weight: 500;
    }

    .pagination-info {
        font-size: 0.85rem;
        color: #6b7280;
        font-family: 'Inter', sans-serif;
    }

    .font-bold {
        font-weight: 700;
        color: #374151;
    }

    /* Dark Mode Support (if updated via js) */
    [data-theme="dark"] .glass-pagination {
        background: rgba(17, 24, 39, 0.6);
        border-color: rgba(255, 255, 255, 0.05);
    }

    [data-theme="dark"] .pagination-btn {
        background: rgba(31, 41, 55, 0.5);
        color: #9ca3af;
    }

    [data-theme="dark"] .pagination-btn:hover:not(.disabled) {
        background: #374151;
        color: white;
    }

    [data-theme="dark"] .pagination-info {
        color: #9ca3af;
    }

    [data-theme="dark"] .font-bold {
        color: #e5e7eb;
    }
</style>
@endif