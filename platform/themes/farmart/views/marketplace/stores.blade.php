@php
    Theme::set('pageTitle', __('Stores'));;
    Theme::layout('full-width');

    $layout = request()->input('layout') ?: theme_option('store_list_layout');

    $layout = $layout && in_array($layout, array_keys(get_store_list_layouts())) ? $layout : 'grid';
@endphp

{!! Theme::partial('page-header', ['withTitle' => true]) !!}

<div class="container-xxxl mb-4">
    <div class="row">
        <div class="col-12">
            <div class="store-listing-filter-wrap">
                <div class="header-filter row g-0 bg-light border justify-content-between">
                    <div class="col-auto p-2 align-items-center d-flex">
                        <span
                            class="ps-2 fs-6 text-gray">{{ __('Total stores showing: :number', ['number' => $stores->total()]) }}</span>
                    </div>
                    <div class="col-auto p-2">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <button class="store-list-filter-button btn btn-primary rounded-0 py-2 px-3">
                                    <span class="svg-icon">
                                        <svg>
                                            <use
                                                href="#svg-icon-filter"
                                                xlink:href="#svg-icon-filter"
                                            ></use>
                                        </svg>
                                    </span>
                                    <span class="ms-2">{{ __('Filter') }}</span>
                                </button>
                            </div>
                            <div class="col-auto">
                                <div class="store-toolbar__view d-flex align-items-center">
                                    <div class="toolbar-view__icon">
                                        <a
                                            class="grid @if ($layout != 'list') active @endif"
                                            data-layout="grid"
                                            data-target=".store-listing-content"
                                            data-class-remove="row-cols-sm-2 row-cols-1 store-listing__list"
                                            data-class-add="row-cols-md-4 row-cols-sm-2 row-cols-1"
                                            href="#"
                                        >
                                            <span class="svg-icon">
                                                <svg>
                                                    <use
                                                        href="#svg-icon-grid"
                                                        xlink:href="#svg-icon-grid"
                                                    ></use>
                                                </svg>
                                            </span>
                                        </a>
                                        <a
                                            class="list @if ($layout == 'list') active @endif"
                                            data-layout="list"
                                            data-target=".store-listing-content"
                                            data-class-add="row-cols-sm-2 row-cols-1 store-listing__list"
                                            data-class-remove="row-cols-md-4 row-cols-sm-2 row-cols-1"
                                            href="#"
                                        >
                                            <span class="svg-icon">
                                                <svg>
                                                    <use
                                                        href="#svg-icon-list"
                                                        xlink:href="#svg-icon-list"
                                                    ></use>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form
                class="my-3"
                id="store-listing-filter-form-wrap"
                role="form"
                action="{{ route('public.stores') }}"
                method="GET"
                @if (!request()->has('q')) style="display: none" @endif
            >
                @foreach (request()->input() as $key => $item)
                    @if ($key != 'q')
                        <input
                            name="{{ $key }}"
                            type="hidden"
                            value="{{ $item }}"
                        >
                    @endif
                @endforeach
                <div class="row g-0">
                    <div class="col-12 bg-light p-4 border">
                        <div class="store-search">
                            <input
                                class="form-control"
                                name="q"
                                type="search"
                                value="{{ BaseHelper::stringify(request()->query('q')) }}"
                                placeholder="{{ __('Search store...') }}"
                            >
                        </div>
                        <div class="apply-filter row justify-content-end mt-2">
                            <div class="col-auto">
                                <button
                                    class="btn btn-primary px-4 py-2 border border-secondary"
                                    type="submit"
                                >{{ __('Apply') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="my-4">
                @include(Theme::getThemeNamespace('views.marketplace.includes.store-items'))
                <div class="row mt-2 mb-3">
                    {!! $stores->withQueryString()->links(Theme::getThemeNamespace() . '::partials.pagination-numeric') !!}
                </div>
            </div>
        </div>
    </div>
</div>
