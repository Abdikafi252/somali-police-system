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

    <div class="pagination-info glass-pill">
        Showing <span class="highlight">{{ $paginator->firstItem() }}</span> - <span class="highlight">{{ $paginator->lastItem() }}</span> of <span class="highlight">{{ $paginator->total() }}</span>
    </div>
</nav>

<style>
    .glass-pagination-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        margin-top: 2rem;
        width: 100%;
    }

    .glass-pagination {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        background: rgba(255, 255, 255, 0.05);
        /* Very subtle glass */
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .pagination-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #6b7280;
        background: rgba(255, 255, 255, 0.5);
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .pagination-btn:hover:not(.disabled) {
        background: white;
        color: #4f46e5;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        border-color: rgba(79, 70, 229, 0.1);
    }

    .pagination-btn.active {
        background: #4f46e5;
        /* Primary Color */
        color: white;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
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

    .glass-pill {
        background: rgba(255, 255, 255, 0.4);
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        font-size: 0.8rem;
        font-weight: 600;
        color: #6b7280;
        backdrop-filter: blur(4px);
    }

    .highlight {
        color: #4f46e5;
        font-weight: 800;
    }

    /* Dark Mode Support (if updated via js) */
</style>
@endif