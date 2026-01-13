<form
    class="cart-form"
    action="{{ route('public.cart.add-to-cart') }}"
    method="POST"
>
    @csrf
    @if (!empty($withVariations) && $product->variations()->count())
        <div class="pr_switch_wrap">
            {!! render_product_swatches($product, [
                'selected' => $selectedAttrs,
            ]) !!}
        </div>
    @endif

    @if (isset($withProductOptions) && $withProductOptions)
        {!! render_product_options($product) !!}
    @endif

    <input
        class="hidden-product-id"
        name="id"
        type="hidden"
        value="{{ $product->is_variation || !$product->defaultVariation->product_id ? $product->id : $product->defaultVariation->product_id }}"
    />

    @if (EcommerceHelper::isCartEnabled() || !empty($withButtons))
        {!! apply_filters(ECOMMERCE_PRODUCT_DETAIL_EXTRA_HTML, null, $product) !!}
        <div class="product-button">
            @if (EcommerceHelper::isCartEnabled())
                @if ($product->has_variation && empty($isQuickShop) && empty($withButtons))
                    <button
                        class="btn btn-primary mb-2 js-quick-shop-button w-100 @if ($product->isOutOfStock()) disabled @endif"
                        type="button"
                        data-url="{{ route('public.ajax.quick-shop', $product->slug) }}"
                        title="{{ __('Select Options') }}"
                        @if ($product->isOutOfStock()) disabled @endif
                    >
                        <span class="svg-icon">
                            <svg>
                                <use
                                    href="#svg-icon-cart"
                                    xlink:href="#svg-icon-cart"
                                ></use>
                            </svg>
                        </span>
                        <span class="add-to-cart-text ms-2">{{ __('Select Options') }}</span>
                    </button>
                @else
                    @if (! empty($withButtons) || ! empty($isQuickShop))
                        {!! Theme::partial('ecommerce.product-quantity', compact('product')) !!}
                    @endif
                    <button
                        class="btn btn-primary mb-2 add-to-cart-button @if ($product->isOutOfStock()) disabled @endif"
                        name="add_to_cart"
                        type="submit"
                        value="{{ $product->min_cart_quantity }}"
                        title="{{ __('Add to cart') }}"
                        @if ($product->isOutOfStock()) disabled @endif
                    >
                        <span class="svg-icon">
                            <svg>
                                <use
                                    href="#svg-icon-cart"
                                    xlink:href="#svg-icon-cart"
                                ></use>
                            </svg>
                        </span>
                        <span class="add-to-cart-text ms-2">{{ __('Add to cart') }}</span>
                    </button>
                @endif

                @if (EcommerceHelper::isQuickBuyButtonEnabled() && isset($withBuyNow) && $withBuyNow)
                    <button
                        class="btn btn-primary btn-black mb-2 add-to-cart-button @if ($product->isOutOfStock()) disabled @endif"
                        name="checkout"
                        type="submit"
                        value="{{ $product->min_cart_quantity }}"
                        title="{{ __('Buy Now') }}"
                        @if ($product->isOutOfStock()) disabled @endif
                    >
                        <span class="add-to-cart-text ms-2">{{ __('Buy Now') }}</span>
                    </button>
                @endif
            @endif
            @if (!empty($withButtons))
                {!! Theme::partial('ecommerce.product-loop-buttons', compact('product', 'wishlistIds')) !!}
            @endif
        </div>
    @endif
</form>
