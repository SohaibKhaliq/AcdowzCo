@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', SeoHelper::getTitle())

@section('content')
    @if($products->isNotEmpty() || $reviews->isNotEmpty())
        @include(EcommerceHelper::viewPath('customers.product-reviews.icons'))

        <div class="bb-customer-card-list product-reviews-cards">
            {{-- Waiting for Review Card --}}
            @if ($products->isNotEmpty())
                <div class="bb-customer-card waiting-review-card">
                    <div class="bb-customer-card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                                    <x-core::icon name="ti ti-clock" class="text-warning" />
                                </div>
                                <div>
                                    <h3 class="bb-customer-card-title h5 mb-0">{{ __('Waiting for your review') }}</h3>
                                    <p class="text-muted small mb-0">{{ __('Share your experience with these products') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bb-customer-card-body">
                        <div class="bb-customer-card-list">
                            @foreach ($products as $product)
                                <div class="bb-customer-card-content">
                                    <div class="row align-items-center g-3">
                                        <div class="col-auto">
                                            <div class="bb-customer-card-image">
                                                {{ RvMedia::image($product->order_product_image ?: $product->image, $product->name, 'thumb', true, ['class' => 'img-fluid rounded']) }}
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="bb-customer-card-details">
                                                <h4 class="bb-customer-card-name h6 mb-1">
                                                    <a href="{{ $product->url }}" class="text-decoration-none">
                                                        {!! BaseHelper::clean($product->order_product_name ?: $product->name) !!}
                                                    </a>
                                                </h4>

                                                @if ($product->order_completed_at)
                                                    <div class="bb-customer-card-meta">
                                                        <span class="text-muted small">
                                                            <x-core::icon name="ti ti-calendar" class="me-1" />
                                                            {{ __('Order completed') }}:
                                                            <time>{{ Carbon\Carbon::parse($product->order_completed_at)->translatedFormat('M d, Y') }}</time>
                                                        </span>
                                                    </div>
                                                @endif

                                                <div class="bb-customer-card-actions mt-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="text-muted small">{{ __('Rate this product:') }}</span>
                                                        <div class="d-flex ecommerce-product-star ecommerce-product-item" data-id="{{ $product->id }}">
                                                            @for ($i = 5; $i >= 1; $i--)
                                                                <label class="order-{{ $i }} cursor-pointer">
                                                                    <x-core::icon name="ti ti-star-filled" class="ecommerce-icon text-warning" data-star="{{ $i }}" />
                                                                </label>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (!$loop->last)
                                    <hr class="my-3">
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Reviewed Products Card --}}
            @if ($reviews->isNotEmpty())
                <div class="bb-customer-card reviewed-products-card">
                    <div class="bb-customer-card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                    <x-core::icon name="ti ti-star-filled" class="text-success" />
                                </div>
                                <div>
                                    <h3 class="bb-customer-card-title h5 mb-0">{{ __('Your Reviews') }}</h3>
                                    <p class="text-muted small mb-0">{{ __('Products you have reviewed') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bb-customer-card-body">
                        @include(EcommerceHelper::viewPath('customers.product-reviews.reviewed'))
                    </div>
                </div>
            @endif

            {{-- Empty State for Waiting Reviews --}}
            @if ($products->isEmpty() && $reviews->isNotEmpty())
                <div class="bb-customer-card waiting-review-card">
                    <div class="bb-customer-card-header">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-secondary bg-opacity-10 rounded-circle p-2">
                                <x-core::icon name="ti ti-clock" class="text-secondary" />
                            </div>
                            <div>
                                <h3 class="bb-customer-card-title h5 mb-0">{{ __('Waiting for your review') }}</h3>
                                <p class="text-muted small mb-0">{{ __('No products pending review') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bb-customer-card-body">
                        <div class="text-center py-4">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3 d-inline-flex mb-3">
                                <x-core::icon name="ti ti-shopping-bag" class="text-info" />
                            </div>
                            <h5 class="h6 mb-2">{{ __('All caught up!') }}</h5>
                            <p class="text-muted small mb-3">
                                {{ __("You don't have any products waiting for review. Keep shopping to discover new products!") }}
                            </p>
                            <a href="{{ route('public.products') }}" class="btn btn-outline-primary btn-sm">
                                <x-core::icon name="ti ti-shopping-bag" class="me-1" />
                                {{ __('Browse Products') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @include(EcommerceHelper::viewPath('customers.product-reviews.modal'))
    @else
        @include(EcommerceHelper::viewPath('customers.partials.empty-state'), [
            'title' => __('No reviews yet!'),
            'subtitle' => __('Start shopping and share your experience by reviewing products you\'ve purchased.'),
            'actionUrl' => route('public.products'),
            'actionLabel' => __('Start Shopping'),
        ])
    @endif
@endsection
