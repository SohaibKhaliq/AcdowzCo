@php
    $digitalProducts = $order->products->where('product_type', 'digital');
@endphp

@if($digitalProducts->isNotEmpty())
    <x-core::card class="mb-3">
        <x-core::card.header>
            <x-core::card.title>
                {{ trans('plugins/ecommerce::order.digital_product_downloads.title') }}
            </x-core::card.title>
        </x-core::card.header>
        <ul class="list-group list-group-flush">
            @foreach($digitalProducts as $orderProduct)
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
                            'ec_products.generate_license_code',
                        ],
                    ]);
                @endphp
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <img
                                    src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                    alt="{{ $orderProduct->product_name }}"
                                    width="60"
                                >
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">
                                        <a
                                            href="{{ route('products.edit', $orderProduct->product_id) }}"
                                            target="_blank"
                                            title="{{ $orderProduct->product_name }}"
                                        >
                                            {{ $orderProduct->product_name }}
                                        </a>
                                    </h5>

                                    @if ($sku = Arr::get($orderProduct->options, 'sku') ?: $orderProduct->sku)
                                        <div class="text-muted small mb-1">{{ __('SKU') }}: {{ $sku }}</div>
                                    @endif

                                    <div class="d-flex gap-3 mb-2 text-muted small">
                                        <span>{{ __('Quantity') }}: {{ $orderProduct->qty }}</span>
                                        <span>{{ __('Price') }}: {{ format_price($orderProduct->price) }}</span>
                                    </div>

                                    @if($order->is_finished && ($orderProduct->product_file_internal_count || $orderProduct->product_file_external_count))
                                        <div class="d-inline-flex gap-3 flex-wrap mb-2">
                                            <div class="d-flex align-items-center gap-1">
                                                <x-core::icon name="ti ti-download" class="text-success" />
                                                <span class="small">{{ trans('plugins/ecommerce::order.digital_product_downloads.download_count', ['count' => number_format($orderProduct->times_downloaded)]) }}</span>
                                            </div>
                                            <div @class(['d-flex align-items-center gap-1', 'text-warning' => ! $orderProduct->downloaded_at])>
                                                <x-core::icon name="ti ti-clock" />
                                                <span class="small">
                                                    @if($orderProduct->downloaded_at)
                                                        {{ trans('plugins/ecommerce::order.digital_product_downloads.first_download', ['time' => BaseHelper::formatDateTime($orderProduct->downloaded_at)]) }}
                                                    @else
                                                        {{ trans('plugins/ecommerce::order.digital_product_downloads.not_downloaded_yet') }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($orderProduct->license_code)
                                        @php
                                            $licenseCodes = $orderProduct->license_codes_array;
                                            $hasMultipleCodes = count($licenseCodes) > 1;
                                        @endphp
                                        <div class="license-codes-section mb-3 p-3 bg-light rounded">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <x-core::icon name="ti ti-key" class="text-primary" />
                                                <span class="fw-semibold">
                                                    {{ $hasMultipleCodes
                                                        ? trans('plugins/ecommerce::products.license_codes.codes') . ' (' . count($licenseCodes) . ')'
                                                        : trans('plugins/ecommerce::products.license_codes.code') }}:
                                                </span>
                                            </div>
                                            <div>
                                                @if ($hasMultipleCodes)
                                                    <div class="d-flex flex-column gap-2">
                                                        @foreach ($licenseCodes as $index => $code)
                                                            <div class="d-flex align-items-center">
                                                                <span class="text-muted me-2">{{ $index + 1 }}.</span>
                                                                <code class="bg-white p-2 rounded d-inline-block">{{ $code }}</code>
                                                                <x-core::copy
                                                                    :value="$code"
                                                                    :message="__('License code copied!')"
                                                                    class="ms-2"
                                                                />
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center">
                                                        <code class="bg-white p-2 rounded d-inline-block">{{ $licenseCodes[0] ?? $orderProduct->license_code }}</code>
                                                        <x-core::copy
                                                            :value="$licenseCodes[0] ?? $orderProduct->license_code"
                                                            :message="__('License code copied!')"
                                                            class="ms-2"
                                                        />
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif ($product && $product->generate_license_code)
                                        <div class="alert alert-info mb-2">
                                            <x-core::icon name="ti ti-info-circle" />
                                            @if (!$order->is_finished)
                                                {{ __('License code will be generated when the order is completed.') }}
                                            @else
                                                {{ __('License code is being generated...') }}
                                            @endif
                                        </div>
                                    @endif

                                    @if($order->is_finished && ($orderProduct->product_file_internal_count || $orderProduct->product_file_external_count))
                                        <div class="btn-group" role="group">
                                            @if ($orderProduct->product_file_internal_count)
                                                <a
                                                    class="btn btn-sm btn-primary"
                                                    href="{{ route('customer.downloads.product', $orderProduct->id) }}"
                                                    target="_blank"
                                                >
                                                    <x-core::icon name="ti ti-download" />
                                                    {{ __('Download files') }} ({{ $orderProduct->product_file_internal_count }})
                                                </a>
                                            @endif
                                            @if ($orderProduct->product_file_external_count)
                                                <a
                                                    class="btn btn-sm btn-info"
                                                    href="{{ route('customer.downloads.product', [$orderProduct->id, 'external' => true]) }}"
                                                    target="_blank"
                                                >
                                                    <x-core::icon name="ti ti-external-link" />
                                                    {{ __('External links') }} ({{ $orderProduct->product_file_external_count }})
                                                </a>
                                            @endif
                                        </div>
                                    @elseif (!$order->is_finished)
                                        <div class="text-muted small">
                                            <x-core::icon name="ti ti-info-circle" />
                                            {{ __('Download links will be available when the order is completed.') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </x-core::card>
@endif
