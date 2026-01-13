<div class="ps-product--quickshop product-attributes">
    <div class="ps-product__content">
        <div class="ps-product__header">
            <h3 class="product-name">{!! BaseHelper::clean($product->name) !!}</h3>
            {!! Theme::partial('ecommerce.product-price', compact('product')) !!}
        </div>
        
        <div class="ps-product__body">
            @if ($product->brand->id)
                <p class="ps-product__brand">{{ __('Brand') }}: <a href="{{ $product->brand->url }}">{{ $product->brand->name }}</a></p>
            @endif
            
            @if (EcommerceHelper::isReviewEnabled())
                {!! Theme::partial('star-rating', ['avg' => $product->reviews_avg, 'count' => $product->reviews_count]) !!}
            @endif
            
            <div class="product-availability mt-3 mb-3">
                <div class="availability-status">
                    <span class="status-label">{{ __('Availability') }}:</span>
                    <span class="status-value" data-in-stock-text="{{ __('In stock') }}" data-out-of-stock-text="{{ __('Out of stock') }}">
                        @if ($product->isOutOfStock())
                            <span class="text-danger">{{ __('Out of stock') }}</span>
                        @elseif (! $product->with_storehouse_management || $product->quantity < 1)
                            <span class="text-success">{!! BaseHelper::clean($product->stock_status_html) !!}</span>
                        @else
                            <span class="text-success">{{ __('In stock') }}</span>
                        @endif
                    </span>
                </div>
            </div>
            
            @php
                $withVariations = true;
                $withProductOptions = true;
                $isQuickShop = true;
            @endphp
            
            {!! Theme::partial('ecommerce.product-cart-form', compact('product', 'withVariations', 'withProductOptions', 'isQuickShop', 'selectedAttrs', 'productVariation')) !!}
            
            <div class="ps-product__link mt-3">
                <a href="{{ $product->url }}" class="text-decoration-underline">{{ __('View Full Details') }}</a>
            </div>
        </div>
    </div>
</div>