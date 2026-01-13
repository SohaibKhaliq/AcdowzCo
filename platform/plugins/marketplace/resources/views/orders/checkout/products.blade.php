<div class="bg-light py-2">
    <p class="font-weight-bold mb-0">{{ __('Product(s)') }}:</p>
</div>

<div class="checkout-products-marketplace shipping-method-wrapper">
    @foreach ($groupedProducts as $grouped)
        @php
            $cartItems = $grouped['products']->pluck('cartItem');
            $store = $grouped['store'];
            if (! $store->exists) {
                $store->id = 0;
                $store->name = get_ecommerce_setting('company_name_for_invoicing', get_ecommerce_setting('store_name')) ?: Theme::getSiteTitle();
                $store->logo = theme_option('favicon') ?: Theme::getLogo();
            }
            $storeId = $store->id;
            $sessionData = Arr::get($sessionCheckoutData, 'marketplace.' . $storeId, []);
            $shipping = Arr::get($sessionData, 'shipping', []);
            $defaultShippingOption = Arr::get($sessionData, 'shipping_option');
            $defaultShippingMethod = Arr::get($sessionData, 'shipping_method');
            $promotionDiscountAmount = Arr::get($sessionData, 'promotion_discount_amount', 0);
            $couponDiscountAmount = Arr::get($sessionData, 'coupon_discount_amount', 0);
            $shippingAmount = Arr::get($sessionData, 'shipping_amount', 0);
            $isFreeShipping = Arr::get($sessionData, 'is_free_shipping', 0);
            $rawTotal = Cart::rawTotalByItems($cartItems);
            $shippingCurrent = Arr::get($shipping, $defaultShippingMethod . '.' . $defaultShippingOption, []);
            $isAvailableShipping = Arr::get($sessionData, 'is_available_shipping', true);

            $orderAmount = max($rawTotal - $promotionDiscountAmount - $couponDiscountAmount, 0);
            $orderAmount += (float) $shippingAmount;
        @endphp
        <div class="mt-3 bg-light mb-3">
            @if (MarketplaceHelper::getSetting('show_vendor_info_at_checkout', true))
                <div class="p-2 vendor-information-section" style="background: antiquewhite;">
                    <img
                        class="img-fluid rounded"
                        src="{{ RvMedia::getImageUrl($store->logo_square ?: $store->logo, null, false, RvMedia::getDefaultImage()) }}"
                        alt="{{ $store->name }}"
                        style="max-width: 30px; margin-inline-end: 3px;"
                    >
                    <span class="font-weight-bold">{!! BaseHelper::clean($store->name) !!}</span>
                    @if ($store->id && EcommerceHelper::isReviewEnabled())
                        <div class="d-flex align-items-center gap-2">
                            @include(EcommerceHelper::viewPath('includes.rating-star'), ['avg' => $store->reviews()->avg('star')])
                            <span class="small text-muted">
                                @if (($reviewsCount = $store->reviews()->count()) === 1)
                                    ({{ __('1 Review') }})
                                @else
                                    ({{ __(':count Reviews', ['count' => number_format($reviewsCount)]) }})
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            @endif

            <div class="py-3">
                @foreach ($grouped['products'] as $product)
                    @include('plugins/ecommerce::orders.checkout.product', [
                        'product' => $product,
                        'cartItem' => $product->cartItem,
                        'key' => $product->cartItem->rowId,
                    ])
                @endforeach
            </div>

            @if ($isAvailableShipping && MarketplaceHelper::isChargeShippingPerVendor())
                <div class="shipping-method-wrapper py-3" @style(['display: none' => (bool) get_ecommerce_setting('disable_shipping_options', false)])>
                    @if (!empty($shipping))
                        <div class="payment-checkout-form">
                            <h6>{{ __('Shipping method') }}:</h6>

                            <input
                                name="shipping_option[{{ $storeId }}]"
                                type="hidden"
                                value="{{ old("shipping_option.$storeId", $defaultShippingOption ?: array_key_first(Arr::first($shipping))) }}"
                            >

                            <div id="shipping-method-{{ $storeId }}">
                                <ul class="list-group list_payment_method">
                                    @foreach ($shipping as $shippingKey => $shippingItems)
                                        @foreach ($shippingItems as $shippingOption => $shippingItem)
                                            @include('plugins/ecommerce::orders.partials.shipping-option', [
                                                'shippingItem' => $shippingItem,
                                                'attributes' => [
                                                    'id' => "shipping-method-$storeId-$shippingKey-$shippingOption",
                                                    'name' => "shipping_method[$storeId]",
                                                    'class' => 'magic-radio shipping_method_input',
                                                    'checked' => old("shipping_method.$storeId", $defaultShippingMethod) == $shippingKey && old("shipping_option.$storeId", $defaultShippingOption) == $shippingOption,
                                                    'disabled' => Arr::get($shippingItem, 'disabled'),
                                                    'data-id' => $storeId,
                                                    'data-option' => $shippingOption,
                                                ],
                                            ])
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @else
                        <p>{{ __('No shipping methods available!') }}</p>
                    @endif

                    <div class="payment-info-loading loading-spinner" style="display: none;"></div>
                </div>
            @endif

            <hr class="border-dark-subtle" />
            @if (count($groupedProducts) > 1 && MarketplaceHelper::getSetting('display_order_total_info_for_each_store', false))
                <div class="p-3">
                    <div class="row">
                        <div class="col-6">
                            <p>{{ __('Subtotal') }}:</p>
                        </div>
                        <div class="col-6 text-end">
                            <p class="price-text sub-total-text text-end">
                                {{ format_price(Cart::rawSubTotalByItems($cartItems)) }} </p>
                        </div>
                    </div>
                    @if (EcommerceHelper::isTaxEnabled())
                        <div class="row">
                            <div class="col-6">
                                <p>{{ __('Tax') }}:</p>
                            </div>
                            <div class="col-6 text-end">
                                <p class="price-text tax-price-text">
                                    {{ format_price(Cart::rawTaxByItems($cartItems)) }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($couponDiscountAmount)
                        <div class="row">
                            <div class="col-6">
                                <p>{{ __('Discount amount') }}:</p>
                            </div>
                            <div class="col-6 text-end">
                                <p class="price-text coupon-price-text">{{ format_price($couponDiscountAmount) }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($isAvailableShipping && MarketplaceHelper::isChargeShippingPerVendor())
                        <div class="row">
                            <div class="col-6">
                                <p>{{ __('Shipping fee') }}:</p>
                            </div>
                            <div class="col-6 text-end">
                                <p class="price-text">
                                    @if (Arr::get($shippingCurrent, 'price') && $isFreeShipping)
                                        <span class="font-italic" style="text-decoration-line: line-through;">
                                            {{ format_price(Arr::get($shippingCurrent, 'price')) }}
                                        </span>
                                        <span class="font-weight-bold">{{ __('Free shipping') }}</span>
                                    @else
                                        <span class="font-weight-bold">
                                            {{ format_price(Arr::get($shippingCurrent, 'price')) }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-6">
                            <p>{{ __('Total') }}:</p>
                        </div>
                        <div class="col-6 float-end">
                            <p class="total-text raw-total-text mb-0" data-price="{{ $rawTotal }}">
                                {{ format_price($orderAmount) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>
