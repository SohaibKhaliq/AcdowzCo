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
                {{ trans('plugins/ecommerce::products.download') }}
            </th>
            <th style="text-align: left">
                {{ trans('plugins/ecommerce::products.license_codes.code') }}
            </th>
        </tr>

        @foreach ($order->digitalProducts() as $orderProduct)
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
                    @if ($orderProduct->product_file_internal_count)
                        <div>
                            <a href="{{ $orderProduct->download_hash_url }}">{{ __('All files') }}</a>
                        </div>
                    @endif
                    @if ($orderProduct->product_file_external_count)
                        <div>
                            <a href="{{ $orderProduct->download_external_url }}">{{ __('External link downloads') }}</a>
                        </div>
                    @endif
                </td>
                <td>
                    @if ($orderProduct->license_code)
                        <code style="background-color: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-family: monospace;">
                            {{ $orderProduct->license_code }}
                        </code>
                    @else
                        <span style="color: #6c757d;">{{ __('N/A') }}</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </table><br>
</div>
