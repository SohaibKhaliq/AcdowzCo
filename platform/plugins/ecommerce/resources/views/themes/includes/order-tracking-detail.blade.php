@if ($order)
    <div class="bb-order-detail-wrapper">
        <!-- Order Information Section -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="bb-order-info">
                    <div class="row">
                        <div @class(['col-12' => ! $order->address->name, 'col-md-6' => $order->address->name])>
                            <div class="bb-order-info-section">
                                <h5 class="bb-section-title mb-3">{{ __('Order Information') }}</h5>
                                <div class="bb-order-info-list">
                                    <div class="bb-order-info-item">
                                        <span class="label">{{ __('Order number') }}:</span>
                                        <span class="value fw-bold">{{ $order->code }}</span>
                                    </div>
                                    <div class="bb-order-info-item">
                                        <span class="label">{{ __('Time') }}:</span>
                                        <span class="value">{{ $order->created_at->translatedFormat('d M Y H:i:s') }}</span>
                                    </div>
                                    <div class="bb-order-info-item">
                                        <span class="label">{{ __('Order status') }}:</span>
                                        <span class="value">{{ $order->status->label() }}</span>
                                    </div>
                                    @if($order->cancellation_reason)
                                        <div class="bb-order-info-item">
                                            <span class="label">{{ __('Cancellation Reason') }}:</span>
                                            <span class="value text-warning">{{ $order->cancellation_reason_message }}</span>
                                        </div>
                                    @endif
                                    @if (is_plugin_active('payment') && $order->payment->id)
                                        <div class="bb-order-info-item">
                                            <span class="label">{{ __('Payment method') }}:</span>
                                            <span class="value">{{ $order->payment->payment_channel->label() }}</span>
                                        </div>
                                        <div class="bb-order-info-item">
                                            <span class="label">{{ __('Payment status') }}:</span>
                                            <span class="value">{{ $order->payment->status->label() }}</span>
                                        </div>
                                    @endif
                                    @if ($order->description)
                                        <div class="bb-order-info-item">
                                            <span class="label">{{ __('Note') }}:</span>
                                            <span class="value text-warning fst-italic">{{ $order->description }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($order->address->name)
                            <div class="col-md-6">
                                <div class="bb-order-address-section">
                                    <h5 class="bb-section-title mb-3">{{ __('Shipping Address') }}</h5>
                                    <div class="bb-order-info-list">
                                        <div class="bb-order-info-item">
                                            <span class="label">{{ __('Full Name') }}:</span>
                                            <span class="value">{{ $order->address->name }}</span>
                                        </div>
                                        <div class="bb-order-info-item">
                                            <span class="label">{{ __('Phone') }}:</span>
                                            <span class="value">{{ $order->address->phone }}</span>
                                        </div>
                                        <div class="bb-order-info-item">
                                            <span class="label">{{ __('Address') }}:</span>
                                            <span class="value">{{ $order->address->full_address }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="bb-section-title mb-3">{{ __('Products') }}</h5>
                <div class="bb-order-products">
                    <div class="bb-order-product-cards mb-3">
                        @foreach ($order->products as $orderProduct)
                            @php
                                $product = get_products([
                                    'condition' => [
                                        'ec_products.id' => $orderProduct->product_id,
                                    ],
                                    'take' => 1,
                                    'select' => ['ec_products.id', 'ec_products.images', 'ec_products.name', 'ec_products.price', 'ec_products.sale_price', 'ec_products.sale_type', 'ec_products.start_date', 'ec_products.end_date', 'ec_products.sku', 'ec_products.is_variation', 'ec_products.status', 'ec_products.order', 'ec_products.created_at'],
                                ]);
                            @endphp
                            <div class="bb-order-product-card">
                                <div class="bb-order-product-card-content">
                                    <div class="bb-order-product-card-image">
                                        <img src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                            alt="{{ $orderProduct->product_name }}">
                                    </div>
                                    <div class="bb-order-product-card-details">
                                        <div class="bb-order-product-card-header">
                                            <div class="bb-order-product-card-name">
                                                @if ($product && $product->original_product?->url)
                                                    <a href="{{ $product->original_product->url }}">{!! BaseHelper::clean($orderProduct->product_name) !!}</a>
                                                @else
                                                    {!! BaseHelper::clean($orderProduct->product_name) !!}
                                                @endif
                                            </div>

                                            @if ($sku = Arr::get($orderProduct->options, 'sku'))
                                                <div class="bb-order-product-card-sku">
                                                    <span class="text-muted">{{ $sku }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="bb-order-product-card-meta">
                                            @if ($attributes = Arr::get($orderProduct->options, 'attributes'))
                                                <div class="bb-order-product-card-attributes">
                                                    <small>{{ $attributes }}</small>
                                                </div>
                                            @elseif ($product && $product->is_variation)
                                                <div class="bb-order-product-card-attributes">
                                                    <small>
                                                        @if ($attributes = get_product_attributes($product->getKey()))
                                                            @foreach ($attributes as $attribute)
                                                                {{ $attribute->attribute_set_title }}: {{ $attribute->title }}
                                                                @if (!$loop->last)
                                                                    ,
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </small>
                                                </div>
                                            @endif

                                            @include(
                                                EcommerceHelper::viewPath('includes.cart-item-options-extras'),
                                                ['options' => $orderProduct->options]
                                            )

                                            @if (!empty($orderProduct->product_options) && is_array($orderProduct->product_options))
                                                {!! render_product_options_html($orderProduct->product_options, $orderProduct->price) !!}
                                            @endif

                                            @if ($orderProduct->license_code)
                                                @php
                                                    $licenseCodes = $orderProduct->license_codes_array;
                                                    $hasMultipleCodes = count($licenseCodes) > 1;
                                                @endphp
                                                <div class="bb-order-product-card-license-code mt-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <x-core::icon name="ti ti-key" class="text-primary" />
                                                        <span class="fw-semibold">
                                                            {{ $hasMultipleCodes 
                                                                ? trans('plugins/ecommerce::products.license_codes.codes') . ' (' . count($licenseCodes) . ')' 
                                                                : trans('plugins/ecommerce::products.license_codes.code') }}:
                                                        </span>
                                                    </div>
                                                    <div class="mt-1">
                                                        @if ($hasMultipleCodes)
                                                            <div class="d-flex flex-column gap-2">
                                                                @foreach ($licenseCodes as $index => $code)
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="text-muted me-2">{{ $index + 1 }}.</span>
                                                                        <code class="bg-light p-2 rounded d-inline-block">{{ $code }}</code>
                                                                        <button type="button"
                                                                                class="btn btn-sm btn-outline-secondary ms-2"
                                                                                onclick="navigator.clipboard.writeText('{{ $code }}'); this.innerHTML='<i class=\'ti ti-check\'></i> Copied!'; setTimeout(() => this.innerHTML='<i class=\'ti ti-copy\'></i> Copy', 2000)">
                                                                            <x-core::icon name="ti ti-copy" />
                                                                            {{ __('Copy') }}
                                                                        </button>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <code class="bg-light p-2 rounded d-inline-block">{{ $licenseCodes[0] ?? $orderProduct->license_code }}</code>
                                                            <button type="button"
                                                                    class="btn btn-sm btn-outline-secondary ms-2"
                                                                    onclick="navigator.clipboard.writeText('{{ $licenseCodes[0] ?? $orderProduct->license_code }}'); this.innerHTML='<i class=\'ti ti-check\'></i> Copied!'; setTimeout(() => this.innerHTML='<i class=\'ti ti-copy\'></i> Copy', 2000)">
                                                                <x-core::icon name="ti ti-copy" />
                                                                {{ __('Copy') }}
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if (is_plugin_active('marketplace') && ($product = $orderProduct->product) && $product->original_product->store?->id)
                                                <div class="bb-order-product-card-vendor">
                                                    <small>{{ __('Sold by') }}: <a
                                                            href="{{ $product->original_product->store->url }}"
                                                            class="text-primary">{{ $product->original_product->store->name }}</a>
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="bb-order-product-card-info">
                                        <div class="bb-order-product-card-price">
                                            <div class="bb-order-product-card-price-item">
                                                <span class="label">{{ __('Price') }}:</span>
                                                <span class="value">{{ $orderProduct->amount_format }}</span>
                                            </div>
                                            <div class="bb-order-product-card-price-item">
                                                <span class="label">{{ __('Quantity') }}:</span>
                                                <span class="value">{{ $orderProduct->qty }}</span>
                                            </div>
                                            <div class="bb-order-product-card-price-item total">
                                                <span class="label">{{ __('Total') }}:</span>
                                                <span class="value">{{ $orderProduct->total_format }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="bb-order-totals">
                        @if (EcommerceHelper::isTaxEnabled() && (float)$order->tax_amount)
                            <div class="bb-order-total-item">
                                <span class="label">{{ __('Tax') }}:</span>
                                <span class="value">{{ format_price($order->tax_amount) }}</span>
                            </div>
                        @endif

                        @if ((float)$order->discount_amount)
                            <div class="bb-order-total-item">
                                <span class="label">{{ __('Discount') }}:</span>
                                <span class="value">
                                    {{ format_price($order->discount_amount) }}
                                    @if ($order->discount_amount)
                                        @if ($order->coupon_code)
                                            <span class="small">
                                                ({!! BaseHelper::html(__('Coupon code: ":code"', ['code' => Html::tag('strong', $order->coupon_code)->toHtml()])) !!})
                                            </span>
                                        @elseif ($order->discount_description)
                                            <span class="small">({{ $order->discount_description }})</span>
                                        @endif
                                    @endif
                                </span>
                            </div>
                        @endif

                        @if ((float)$order->shipping_amount && EcommerceHelper::countDigitalProducts($order->products) != $order->products->count())
                            <div class="bb-order-total-item">
                                <span class="label">{{ __('Shipping fee') }}:</span>
                                <span class="value">{{ format_price($order->shipping_amount) }}</span>
                            </div>
                        @endif

                        <div class="bb-order-total-item grand-total">
                            <span class="label">{{ __('Total Amount') }}:</span>
                            <span class="value">{{ format_price($order->amount) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Information Section -->
        @if (! EcommerceHelper::isDisabledPhysicalProduct() && $order->shipment->id)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="bb-section-title mb-3">{{ __('Shipping Information') }}</h5>
                    <div class="bb-order-shipping">
                        <div class="bb-order-info-list">
                            <div class="bb-order-info-item">
                                <span class="label">{{ __('Shipping Status') }}:</span>
                                <span class="value">{!! BaseHelper::clean($order->shipment->status->toHtml()) !!}</span>
                            </div>

                            @if ($order->shipment->shipping_company_name)
                                <div class="bb-order-info-item">
                                    <span class="label">{{ __('Shipping Company Name') }}:</span>
                                    <span class="value">{{ $order->shipment->shipping_company_name }}</span>
                                </div>
                            @endif

                            @if ($order->shipment->tracking_id)
                                <div class="bb-order-info-item">
                                    <span class="label">{{ __('Tracking ID') }}:</span>
                                    <span class="value">{{ $order->shipment->tracking_id }}</span>
                                </div>
                            @endif

                            @if ($order->shipment->tracking_link)
                                <div class="bb-order-info-item">
                                    <span class="label">{{ __('Tracking Link') }}:</span>
                                    <span class="value">
                                        <a href="{{ $order->shipment->tracking_link }}" target="_blank">{{ $order->shipment->tracking_link }}</a>
                                    </span>
                                </div>
                            @endif

                            @if ($order->shipment->note)
                                <div class="bb-order-info-item">
                                    <span class="label">{{ __('Delivery Notes') }}:</span>
                                    <span class="value">{{ $order->shipment->note }}</span>
                                </div>
                            @endif

                            @if ($order->shipment->estimate_date_shipped)
                                <div class="bb-order-info-item">
                                    <span class="label">{{ __('Estimate Date Shipped') }}:</span>
                                    <span class="value">{{ $order->shipment->estimate_date_shipped }}</span>
                                </div>
                            @endif

                            @if ($order->shipment->date_shipped)
                                <div class="bb-order-info-item">
                                    <span class="label">{{ __('Date Shipped') }}:</span>
                                    <span class="value">{{ $order->shipment->date_shipped }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@elseif (request()->input('order_id') || request()->input('email'))
    <div role="alert" class="alert alert-danger mt-3">
        <div class="d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M12 9v4"></path>
                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"></path>
                <path d="M12 16h.01"></path>
            </svg>

            {{ __('The order could not be found. Please try again or contact us if you need assistance.') }}
        </div>
    </div>
@endif
