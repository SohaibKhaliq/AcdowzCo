<div class="row cart-item">
    <div class="col-3">
        <div class="checkout-product-img-wrapper">
            <img
                class="item-thumb img-thumbnail img-rounded"
                src="{{ RvMedia::getImageUrl(Arr::get($cartItem->options, 'image'), 'thumb', default: RvMedia::getDefaultImage()) }}"
                alt="{{ $product->original_product->name }}"
            >
            <span class="checkout-quantity">{{ $cartItem->qty }}</span>
        </div>
    </div>
    <div class="col">

        {!! apply_filters('ecommerce_cart_before_item_content', null, $cartItem) !!}

        <p class="mb-0">
            {{ $product->original_product->name }}
            @if ($product->isOutOfStock())
                <span class="stock-status-label">({!! BaseHelper::clean($product->stock_status_html) !!})</span>
            @endif
        </p>
        @if($product->variation_attributes)
            <p class="mb-0">
                <small>{{ $product->variation_attributes }}</small>
            </p>
        @endif

        @if (get_ecommerce_setting('checkout_product_quantity_editable', true))
            <div
                class="ec-checkout-quantity"
                data-url="{{ route('public.cart.update') }}"
                data-row-id="{{ $cartItem->rowId }}"
            >
                <button type="button" class="ec-checkout-quantity-control ec-checkout-quantity-minus" data-bb-toggle="decrease-qty">
                    <x-core::icon name="ti ti-minus" />
                </button>
                <input
                    type="number"
                    name="items[{{ $key }}][values][qty]"
                    value="{{ $cartItem->qty }}"
                    min="1"
                    max="{{ $product->with_storehouse_management ? $product->quantity : 1000 }}"
                    data-bb-toggle="update-cart"
                    readonly
                />
                <button type="button" class="ec-checkout-quantity-control ec-checkout-quantity-plus" data-bb-toggle="increase-qty">
                    <x-core::icon name="ti ti-plus" />
                </button>
            </div>
        @endif

        @include(EcommerceHelper::viewPath('includes.cart-item-options-extras'), [
            'options' => $cartItem->options,
        ])

        @if (!empty($cartItem->options['options']))
            {!! render_product_options_html($cartItem->options['options'], $product->original_price) !!}
        @endif

        @if (EcommerceHelper::isTaxEnabled() && $cartItem->taxRate > 0)
            <p class="mb-0">
                <small class="text-muted">
                    {{ __('Tax') }}: {{ format_price($cartItem->tax * $cartItem->qty) }}
                    @if ($cartItem->options && $cartItem->options->taxClasses)
                        (
                        @foreach ($cartItem->options->taxClasses as $taxName => $taxRate)
                            {{ $taxName }} {{ $taxRate }}%@if (!$loop->last), @endif
                        @endforeach
                        )
                    @elseif ($cartItem->taxRate > 0)
                        ({{ $cartItem->taxRate }}%)
                    @endif
                </small>
            </p>
        @endif

        {!! apply_filters('ecommerce_cart_after_item_content', null, $cartItem) !!}
    </div>
    <div class="col-auto text-end">
        <p class="mb-1">{{ format_price($cartItem->price) }}</p>
        @if (EcommerceHelper::isTaxEnabled() && $cartItem->tax > 0)
            <p class="mb-0">
                <small class="text-muted">{{ __('Total') }}: {{ format_price(($cartItem->price + $cartItem->tax) * $cartItem->qty) }}</small>
            </p>
        @else
            <p class="mb-0">
                <small class="text-muted">{{ __('Total') }}: {{ format_price($cartItem->price * $cartItem->qty) }}</small>
            </p>
        @endif
    </div>
</div>
