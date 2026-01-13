@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', SeoHelper::getTitle())

@section('content')
    @if($orderProducts->isNotEmpty())
        <div class="bb-customer-card-list">
            @foreach ($orderProducts as $orderProduct)
                @php
                    $product = get_products([
                        'condition' => [
                            'ec_products.id' => $orderProduct->product_id,
                        ],
                        'take'   => 1,
                        'select' => [
                            'ec_products.id',
                            'ec_products.images',
                            'ec_products.name',
                            'ec_products.price',
                            'ec_products.sale_price',
                            'ec_products.sale_type',
                            'ec_products.start_date',
                            'ec_products.end_date',
                            'ec_products.sku',
                            'ec_products.is_variation',
                            'ec_products.status',
                            'ec_products.order',
                            'ec_products.created_at',
                        ],
                    ]);

                    $hasDigitalFiles = $orderProduct->product_file_internal_count || $orderProduct->product_file_external_count;
                @endphp
                <div class="bb-customer-card">
                    <div class="bb-customer-card-header">
                        <div class="bb-customer-card-title">
                            <span class="value">{{ __('Digital Product') }}</span>
                        </div>
                        @if ($hasDigitalFiles)
                            <div class="bb-customer-card-status">
                                <span>{{ __('Downloaded') }}: {{ $orderProduct->times_downloaded }} {{ __('times') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="bb-customer-card-body">
                        <div class="bb-customer-card-content">
                            <div class="bb-customer-card-image">
                                <img
                                    src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                    alt="{{ $orderProduct->product_name }}"
                                >
                            </div>
                            <div class="bb-customer-card-details">
                                <div class="bb-customer-card-name">
                                    @if($product && $product->original_product?->url)
                                        <a href="{{ $product->original_product->url }}">{!! BaseHelper::clean($orderProduct->product_name) !!}</a>
                                    @else
                                        {!! BaseHelper::clean($orderProduct->product_name) !!}
                                    @endif
                                </div>

                                <div class="bb-customer-card-meta">
                                    @if ($sku = Arr::get($orderProduct->options, 'sku'))
                                        <span>({{ $sku }})</span>
                                    @endif

                                    @if ($attributes = Arr::get($orderProduct->options, 'attributes'))
                                        <span class="d-block">{{ $attributes }}</span>
                                    @elseif ($product && $product->is_variation)
                                        <span class="d-block">
                                            @php $attributes = get_product_attributes($product->id) @endphp
                                            @if ($attributes->isNotEmpty())
                                                @foreach ($attributes as $attribute)
                                                    {{ $attribute->attribute_set_title }}: {{ $attribute->title }}@if (!$loop->last), @endif
                                                @endforeach
                                            @endif
                                        </span>
                                    @endif

                                    @if (is_plugin_active('marketplace') && ($product = $orderProduct->product) && $product->original_product->store?->id)
                                        <span class="d-block">{{ __('Sold by') }}: <a href="{{ $product->original_product->store->url }}" class="text-primary">{{ $product->original_product->store->name }}</a></span>
                                    @endif

                                    <span class="d-block">{{ __('Ordered at') }}: {{ $orderProduct->created_at->translatedFormat('M d, Y h:m') }}</span>
                                </div>

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
                                    <div class="bb-customer-card-license-code mt-3">
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
                                                                    data-ecommerce-clipboard
                                                                    data-clipboard-text="{{ $code }}"
                                                                    data-clipboard-message="{{ __('License code copied!') }}">
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
                                                        data-ecommerce-clipboard
                                                        data-clipboard-text="{{ $licenseCodes[0] ?? $orderProduct->license_code }}"
                                                        data-clipboard-message="{{ __('License code copied!') }}">
                                                    <x-core::icon name="ti ti-copy" />
                                                    {{ __('Copy') }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($hasDigitalFiles)
                        <div class="bb-customer-card-footer">
                            @if ($orderProduct->product_file_internal_count)
                                <a
                                    class="btn btn-primary"
                                    href="{{ route('customer.downloads.product', $orderProduct->id) }}"
                                >
                                    <x-core::icon name="ti ti-download" class="me-1" />
                                    <span>{{ __('Download all files') }}</span>
                                </a>
                            @endif
                            @if ($orderProduct->product_file_external_count)
                                <a
                                    class="btn btn-info"
                                    href="{{ route('customer.downloads.product', [$orderProduct->id, 'external' => true]) }}"
                                >
                                    <x-core::icon name="ti ti-link" class="me-1" />
                                    <span>{{ __('External link downloads') }}</span>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="tp-pagination">
            {!! $orderProducts->links() !!}
        </div>
    @else
        @include(EcommerceHelper::viewPath('customers.partials.empty-state'), [
            'title' => __('No digital products!'),
            'subtitle' => __('You have not purchased any digital products yet.'),
        ])
    @endif
@stop
