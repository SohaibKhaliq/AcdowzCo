@if ($paginator->hasPages())
    <div class="pagination-numeric-short">
        @if ($paginator->onFirstPage())
            <a
                class="disabled"
                href="#"
                aria-disabled="true"
            >
                <span class="svg-icon">
                    <svg>
                        <use
                            href="#svg-icon-chevron-left"
                            xlink:href="#svg-icon-chevron-left"
                        ></use>
                    </svg>
                </span>
            </a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">
                <span class="svg-icon">
                    <svg>
                        <use
                            href="#svg-icon-chevron-left"
                            xlink:href="#svg-icon-chevron-left"
                        ></use>
                    </svg>
                </span>
            </a>
        @endif

        <form
            class="toolbar-pagination"
            action="{{ $paginator->path() }}"
            method="GET"
        >
            @foreach (request()->input() as $key => $item)
                @if ($key != $paginator->getPageName() && is_string($item))
                    <input
                        name="{{ $key }}"
                        type="hidden"
                        value="{{ $item }}"
                    >
                @endif
            @endforeach
            <input
                class="catalog-page-number"
                name="{{ $paginator->getPageName() }}"
                type="number"
                value="{{ $paginator->currentPage() }}"
                min="1"
                max="{{ $paginator->lastPage() }}"
                step="1"
            >
        </form>/ {{ $paginator->lastPage() }}

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">
                <span class="svg-icon">
                    <svg>
                        <use
                            href="#svg-icon-chevron-right"
                            xlink:href="#svg-icon-chevron-right"
                        ></use>
                    </svg>
                </span>
            </a>
        @else
            <a
                class="disabled"
                href="#"
                aria-disabled="true"
            >
                <span class="svg-icon">
                    <svg>
                        <use
                            href="#svg-icon-chevron-right"
                            xlink:href="#svg-icon-chevron-right"
                        ></use>
                    </svg>
                </span>
            </a>
        @endif
    </div>
@endif
