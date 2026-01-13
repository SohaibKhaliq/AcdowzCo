@php
    Theme::layout('full-width');

    $currentMainFilterUrl = $store->url;

    $categories = ProductCategoryHelper::getProductCategoriesWithUrl();
    $categoriesRequest = (array) request()->input('categories', []);
    $categoryId = Arr::get($categoriesRequest, 0);

    Theme::asset()->container('footer')->add('store-search-fix-js', 'themes/farmart/assets/js/store-search-fix.js', ['jquery']);
@endphp

{!! Theme::partial('page-header') !!}

<div class="container-xxxl">
    <div class="row mt-5">
        <div class="col-xl-3 col-lg-4">
            <aside
                class="catalog-primary-sidebar catalog-sidebar"
                data-toggle-target="product-categories-primary-sidebar"
            >
                <div class="backdrop"></div>
                <div class="catalog-sidebar--inner side-left">
                    <div class="panel__header d-lg-none mb-4">
                        <span class="panel__header-title">{{ __('Filter Products') }}</span>
                        <a
                            class="close-toggle--sidebar"
                            data-toggle-closest=".catalog-primary-sidebar"
                            href="#"
                        >
                            <span class="svg-icon">
                                <svg>
                                    <use
                                        href="#svg-icon-arrow-right"
                                        xlink:href="#svg-icon-arrow-right"
                                    ></use>
                                </svg>
                            </span>
                        </a>
                    </div>

                    <div class="catalog-filter-sidebar-content px-3 px-md-0">
                        <form action="{{ $store->url }}" method="GET" class="bb-product-form-filter">
                            @include(EcommerceHelper::viewPath('includes.filters.filter-hidden-fields'))
                            <input name="categories[]" type="hidden" value="{{ $categoryId }}">

                            @include(EcommerceHelper::viewPath('includes.filters.categories'))
                        </form>
                    </div>
                </div>
            </aside>
            <aside
                class="catalog-primary-sidebar catalog-sidebar"
                data-toggle-target="contact-store-primary-sidebar"
            >
                <div class="backdrop"></div>
                <div class="catalog-sidebar--inner side-left">
                    <div class="panel__header d-lg-none mb-4">
                        <span class="panel__header-title">{{ __('Contact Vendor') }}</span>
                        <a
                            class="close-toggle--sidebar"
                            data-toggle-closest=".catalog-primary-sidebar"
                            href="#"
                        >
                            <span class="svg-icon">
                                <svg>
                                    <use
                                        href="#svg-icon-arrow-right"
                                        xlink:href="#svg-icon-arrow-right"
                                    ></use>
                                </svg>
                            </span>
                        </a>
                    </div>

                    @if (MarketplaceHelper::isEnabledMessagingSystem() && (! auth('customer')->check() || $store->id != auth('customer')->user()->store->id))
                        <div class="catalog-filter-sidebar-content px-3 px-md-0">
                            <div class="widget-wrapper widget-contact-store">
                                <h4 class="widget-title">{{ __('Contact Vendor') }}</h4>
                                <div class="mb-4">
                                    <p>{{ __('All messages are recorded and spam is not tolerated. Your email address will be shown to the recipient.') }}</p>
                                    {!!
                                        $contactForm
                                        ->setFormOption('class', 'form-contact-store bb-contact-store-form')
                                        ->setFormInputClass('form-control')
                                        ->setFormLabelClass('d-none sr-only')
                                        ->modify(
                                            'submit',
                                            'submit',
                                            Botble\Base\Forms\FieldOptions\ButtonFieldOption::make()
                                                ->addAttribute('data-bb-loading', 'button-loading')
                                                ->cssClass('btn btn-primary')
                                                ->label(__('Send message'))
                                                ->wrapperAttributes(['class' => 'd-grid'])
                                                ->toArray(),
                                            true
                                        )
                                        ->renderForm()
                                    !!}
                                </div>

                                @include(MarketplaceHelper::viewPath('includes.contact-form-script'))
                            </div>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
        <div class="col-xl-9 col-lg-8">
            @include(Theme::getThemeNamespace() . '::views.marketplace.includes.info-box', [
                'showContactVendor' => true,
            ])
            <div class="row justify-content-center my-5 mb-2">
                <div class="col-12">
                    <div class="mb-3">
                        <form
                            class="products-filter-form-vendor"
                            action="{{ URL::current() }}"
                            method="GET"
                        >
                            <div class="input-group">
                                <input
                                    class="form-control"
                                    name="q"
                                    type="text"
                                    value="{{ BaseHelper::stringify(request()->query('q')) }}"
                                    placeholder="{{ __('Search in this store...') }}"
                                >
                                <button
                                    class="btn btn-primary px-3 justify-content-center"
                                    type="submit"
                                >
                                    <span class="svg-icon me-2 d-block text-center w-100">
                                        <svg>
                                            <use
                                                href="#svg-icon-search"
                                                xlink:href="#svg-icon-search"
                                            ></use>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-light p-2 my-3">
                <div class="row catalog-header justify-content-between">
                    <div class="col-auto catalog-header__left d-flex align-items-center">
                        <h2 class="h6 catalog-header__title d-none d-lg-block mb-0 ps-2">
                            <span class="products-found">
                                <span
                                    class="text-primary me-1">{{ $products->total() }}</span>{{ __('Products found') }}
                            </span>
                        </h2>
                        <a
                            class="d-lg-none sidebar-filter-mobile"
                            data-toggle="product-categories-primary-sidebar"
                            href="#"
                        >
                            <span class="svg-icon me-2">
                                <svg>
                                    <use
                                        href="#svg-icon-filter"
                                        xlink:href="#svg-icon-filter"
                                    ></use>
                                </svg>
                            </span>
                            <span>{{ __('Filter') }}</span>
                        </a>
                    </div>
                    <div class="col-auto catalog-header__right">
                        <div class="catalog-toolbar row align-items-center">
                            @include(Theme::getThemeNamespace() . '::views.ecommerce.includes.layout')
                        </div>
                    </div>
                </div>
            </div>
            <div class="products-listing position-relative bb-product-items-wrapper">
                @include(Theme::getThemeNamespace('views.marketplace.stores.items'))
            </div>
        </div>
    </div>
</div>
