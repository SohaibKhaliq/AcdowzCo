<x-core::table :hover="false" :striped="false" class="order-products-table">
    <x-core::table.body>
        @foreach ($order->products as $orderProduct)
            @php
                $product = $orderProduct->product->original_product;
            @endphp

            <x-core::table.body.row>
                <x-core::table.body.cell style="width: 80px">
                    <img
                        src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                        alt="{{ $orderProduct->product_name }}"
                    >
                </x-core::table.body.cell>
                <x-core::table.body.cell style="width: 45%" class="text-start">
                    <div class="d-flex align-items-center flex-wrap">
                        @if ($editProductRoute && $product->getKey() && $product->original_product->getKey())
                            <a
                                href="{{ route($editProductRoute, $product->original_product->getKey()) }}"
                                title="{{ $orderProduct->product_name }}"
                                target="_blank"
                                class="me-2"
                            >
                                {{ $orderProduct->product_name }}
                            </a>
                        @else
                            <span class="me-2">{{ $orderProduct->product_name }}</span>
                        @endif

                        @if ($sku = Arr::get($orderProduct->options, 'sku') ?: ($product && $product->sku ? $product->sku : null))
                            <p class="mb-0">({{ trans('plugins/ecommerce::order.sku') }}: <strong>{{ $sku }}</strong>)</p>
                        @endif
                    </div>

                    @if ($attributes = Arr::get($orderProduct->options, 'attributes'))
                        <div>
                            <small>{{ $attributes }}</small>
                        </div>
                    @endif

                    @if($isInAdmin)
                        @if (!empty($orderProduct->product_options) && is_array($orderProduct->product_options))
                            {!! render_product_options_html($orderProduct->product_options, $orderProduct->price) !!}
                        @endif
                    @endif

                    @include(
                        EcommerceHelper::viewPath('includes.cart-item-options-extras'),
                        ['options' => $orderProduct->options]
                    )

                    {!! apply_filters(ECOMMERCE_ORDER_DETAIL_EXTRA_HTML, null, $orderProduct, $order) !!}
                    {!! apply_filters('ecommerce_order_product_item_extra_info', null, $orderProduct, $order) !!}

                    @if (! EcommerceHelper::isDisabledPhysicalProduct() && $order->shipment->id)
                        <ul class="list-unstyled ms-1 small">
                            <li>
                                <span class="bull">↳</span>
                                <span class="black">{{ trans('plugins/ecommerce::order.shipping') }}</span>
                                @if($isInAdmin)
                                    <a
                                        class="text-underline bold-light"
                                        href="{{ route('ecommerce.shipments.edit', $order->shipment->id) }}"
                                        title="{{ $order->shipping_method_name }}"
                                        target="_blank"
                                    >{{ $order->shipping_method_name }}</a>
                                @else
                                    <span class="text-underline bold-light">{{ $order->shipping_method_name }}</span>
                                @endif
                            </li>

                            @if ($isInAdmin && is_plugin_active('marketplace') && $order->store?->name)
                                <li class="ws-nm">
                                    <span class="bull">↳</span>
                                    <span
                                        class="black">{{ trans('plugins/marketplace::store.store') }}</span>
                                    <a
                                        class="fw-semibold text-decoration-underline"
                                        href="{{ $order->store->url }}"
                                        target="_blank"
                                    >{{ $order->store->name }}</a>
                                </li>
                            @endif
                        </ul>
                    @endif
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ format_price($orderProduct->price) }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    x
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $orderProduct->qty }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ format_price($orderProduct->price * $orderProduct->qty) }}
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endforeach
    </x-core::table.body>
</x-core::table>
