<div
    class="row @if ($layout == 'list') row-cols-sm-2 row-cols-1 store-listing__list @else row-cols-md-4 row-cols-sm-2 row-cols-1 @endif store-listing-content">
    @if ($stores->isNotEmpty() && $stores->loadMissing('metadata'))
        @foreach ($stores as $store)
            <div class="col my-2">
                <div class="card store-card-wrapper h-100">
                    <div class="card-header p-0 pt-3 pb-3 text-center">
                        <a
                            class="store-logo"
                            href="{{ $store->url }}"
                        >
                            <img
                                class="lazyload"
                                data-src="{{ $store->logo_url }}"
                                alt="{{ $store->name }}"
                                style="background-color: #fff; border-radius: 50%"
                            >
                        </a>
                    </div>
                    <div class="card-body store-content bg-light">
                        <div class="store-data-container row g-1">
                            <div class="col-12 store-data">
                                <div class="store-title d-flex align-items-center">
                                    <h2 class="h5 mb-0">
                                        <a href="{{ $store->url }}">{{ $store->name }}</a>
                                        {!! $store->badge !!}
                                    </h2>
                                </div>
                                @if (EcommerceHelper::isReviewEnabled())
                                    <div class="mt-1">
                                        {!! Theme::partial('star-rating', [
                                            'avg' => $store->reviews()->avg('star'),
                                            'count' => $store->reviews()->count(),
                                        ]) !!}
                                    </div>
                                @endif
                                @if (! MarketplaceHelper::hideStoreAddress() && $store->full_address)
                                    <div class="vendor-store-address mt-3 mb-1">
                                        <i class="icon icon-map-marker me-1"></i>
                                        {{ $store->full_address }}
                                    </div>
                                @endif
                                @if (!MarketplaceHelper::hideStorePhoneNumber() && $store->phone)
                                    <div class="vendor-store-phone mb-1">
                                        <i class="icon icon-telephone me-1"></i> <a
                                            href="tel:{{ $store->phone }}"
                                        >{{ $store->phone }}</a>
                                    </div>
                                @endif
                                @if (!MarketplaceHelper::hideStoreEmail() && $store->email)
                                    <div class="vendor-store-email mb-1">
                                        <i class="icon icon-envelope me-1"></i> <a
                                            href="mailto:{{ $store->email }}"
                                        >{{ $store->email }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer store-footer bg-light border-0">
                        <div class="px-2 border-top visit-store-wrapper">
                            <a
                                class="mt-2 btn btn-secondary"
                                href="{{ $store->url }}"
                                title="{{ __('Visit Store') }}"
                            >
                                <span class="svg-icon">
                                    <svg>
                                        <use
                                            href="#svg-icon-store"
                                            xlink:href="#svg-icon-store"
                                        ></use>
                                    </svg>
                                </span> {{ __('Visit Store') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12 w-100">
            <div class="alert alert-warning">
                {{ __('No vendor found.') }}
            </div>
        </div>
    @endif
</div>
