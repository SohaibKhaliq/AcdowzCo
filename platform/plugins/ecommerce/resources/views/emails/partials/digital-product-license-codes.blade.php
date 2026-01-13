<div class="table">
    <table>
        <tr>
            <th style="text-align: left">
                {{ trans('plugins/ecommerce::products.product_image') }}
            </th>
            <th style="text-align: left">
                {{ trans('plugins/ecommerce::products.product_name') }}
            </th>
            <th style="text-align: left">
                {{ trans('plugins/ecommerce::products.license_codes.codes') }}
            </th>
        </tr>

        @foreach ($order->digitalProducts() as $orderProduct)
            {{-- Only show products that have license codes but no files --}}
            @if ($orderProduct->license_code && !$orderProduct->product_file_internal_count && !$orderProduct->product_file_external_count && EcommerceHelper::isEnabledLicenseCodesForDigitalProducts())
                <tr>
                    <td>
                        <img
                            src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb') }}"
                            alt="{{ $orderProduct->product_image }}"
                            width="50"
                        >
                    </td>
                    <td>
                        <span>{{ $orderProduct->product_name }}</span>
                        @if ($attributes = Arr::get($orderProduct->options, 'attributes'))
                            <span class="bb-text-muted">{{ $attributes }}</span>
                        @endif

                        @if ($orderProduct->product_options_implode)
                            <span class="bb-text-muted">{{ $orderProduct->product_options_implode }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($orderProduct->license_code)
                            @php
                                $licenseCodes = $orderProduct->license_codes_array;
                                $hasMultipleCodes = count($licenseCodes) > 1;
                            @endphp
                            @if ($hasMultipleCodes)
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    @foreach ($licenseCodes as $index => $code)
                                        <div>
                                            <span style="color: #6c757d; margin-right: 4px;">{{ $index + 1 }}.</span>
                                            <code style="background-color: #f8f9fa; padding: 8px 12px; border-radius: 4px; font-family: monospace; font-size: 14px; font-weight: bold; color: #495057;">
                                                {{ $code }}
                                            </code>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <code style="background-color: #f8f9fa; padding: 8px 12px; border-radius: 4px; font-family: monospace; font-size: 14px; font-weight: bold; color: #495057;">
                                    {{ $licenseCodes[0] ?? $orderProduct->license_code }}
                                </code>
                            @endif
                        @else
                            <span style="color: #6c757d;">{{ __('N/A') }}</span>
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
    </table><br>
</div>
