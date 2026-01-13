<x-core::table :hover="false" :striped="false" class="table-borderless text-end">
    <x-core::table.body>

        <x-core::table.body.row>
            <x-core::table.body.cell>{{ trans('plugins/ecommerce::order.quantity') }}</x-core::table.body.cell>
            <x-core::table.body.cell>
                {{ number_format($order->products->sum('qty')) }}
            </x-core::table.body.cell>
        </x-core::table.body.row>
        <x-core::table.body.row>
            <x-core::table.body.cell>
                {{ trans('plugins/ecommerce::order.sub_amount') }}</x-core::table.body.cell>
            <x-core::table.body.cell>
                {{ format_price($order->sub_total) }}
            </x-core::table.body.cell>
        </x-core::table.body.row>
        <x-core::table.body.row>
            <x-core::table.body.cell>
                {{ trans('plugins/ecommerce::order.discount') }}
                @if ($order->coupon_code)
                    <p class="mb-0">
                        {!! trans('plugins/ecommerce::order.coupon_code', [
                            'code' => Html::tag('strong', $order->coupon_code)->toHtml(),
                        ]) !!}
                    </p>
                @elseif ($order->discount_description)
                    <p class="mb-0">{{ $order->discount_description }}</p>
                @endif
            </x-core::table.body.cell>
            <x-core::table.body.cell>
                {{ format_price($order->discount_amount) }}
            </x-core::table.body.cell>
        </x-core::table.body.row>
        @if ($order->shipping_method_name)
            <x-core::table.body.row>
                <x-core::table.body.cell>
                    <p class="mb-1">{{ trans('plugins/ecommerce::order.shipping_fee') }}</p>
                    <span class="small d-block">{{ $order->shipping_method_name }}</span>
                    <span class="small d-block">{{ number_format(ecommerce_convert_weight($weight)) }} {{ ecommerce_weight_unit(true) }}</span>
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ format_price($order->shipping_amount) }}
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endif
        @if (EcommerceHelper::isTaxEnabled())
            <x-core::table.body.row>
                <x-core::table.body.cell>
                    {{ trans('plugins/ecommerce::order.tax') }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ format_price($order->tax_amount) }}
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endif

        @if ((float) $order->payment_fee)
            <x-core::table.body.row>
                <x-core::table.body.cell>
                    {{ trans('plugins/payment::payment.payment_fee') }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ format_price($order->payment_fee) }}
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endif
        <x-core::table.body.row>
            <x-core::table.body.cell>
                {{ trans('plugins/ecommerce::order.total_amount') }}
            </x-core::table.body.cell>
            <x-core::table.body.cell>
                @if (is_plugin_active('payment') && $order->payment->id)
                    <span @class(['text-warning' => $order->payment->status != Botble\Payment\Enums\PaymentStatusEnum::COMPLETED]) class="text-warning">
                {{ format_price($order->amount) }}
            </span>
                @else
                    {{ format_price($order->amount) }}
                @endif
            </x-core::table.body.cell>
        </x-core::table.body.row>
        @if (is_plugin_active('payment') && $order->payment->id)
            <x-core::table.body.row>
                <x-core::table.body.cell>
                    {{ trans('plugins/ecommerce::order.paid_amount') }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    @if ($isInAdmin && $order->payment->id)
                        <a
                            href="{{ route('payment.show', $order->payment->id) }}"
                            target="_blank"
                        >
                            <span>{{ format_price($order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::COMPLETED ? $order->payment->amount : 0) }}</span>
                        </a>
                    @else
                        <span>{{ format_price($order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::COMPLETED ? $order->payment->amount : 0) }}</span>
                    @endif
                </x-core::table.body.cell>
            </x-core::table.body.row>

            <x-core::table.body.row>
                <x-core::table.body.cell>
                    {{ trans('plugins/ecommerce::order.payment_method') }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    @if ($isInAdmin && $order->payment->id)
                        <a href="{{ route('payment.show', $order->payment->id) }}" target="_blank">
                            {{ $order->payment->payment_channel->label() }}

                            <x-core::icon name="ti ti-external-link" />
                        </a>
                    @else
                        {{ $order->payment->payment_channel->label() }}
                    @endif
                </x-core::table.body.cell>
            </x-core::table.body.row>

            <x-core::table.body.row>
                <x-core::table.body.cell>
                    {{ trans('plugins/ecommerce::order.payment_status_label') }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {!! BaseHelper::clean($order->payment->status->toHtml()) !!}
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endif

        @if(!empty($proofDownloadUrl))
            @if ($order->proof_file && Storage::disk('local')->exists($order->proof_file))
                <x-core::table.body.row>
                    <x-core::table.body.cell colspan="2">
                        <div style="margin-top: -1rem !important">
                            @include('plugins/ecommerce::orders.partials.payment-proof-detail', ['downloadUrl' => $proofDownloadUrl])
                        </div>
                    </x-core::table.body.cell>
                </x-core::table.body.row>
            @endif
        @endif

        {!! apply_filters('ecommerce_admin_order_extra_info', null, $order) !!}

        <x-core::table.body.row>
            <td colspan="2">
                <hr class="my-0">
            </td>
        </x-core::table.body.row>

        @if (is_plugin_active('payment') && $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::REFUNDED)
            <x-core::table.body.row class="hidden">
                <x-core::table.body.cell>
                    {{ trans('plugins/ecommerce::order.refunded_amount') }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    <span>{{ format_price($order->payment->amount) }}</span>
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endif
        <x-core::table.body.row class="hidden">
            <x-core::table.body.cell>
                {{ trans('plugins/ecommerce::order.amount_received') }}
            </x-core::table.body.cell>
            <x-core::table.body.cell>
                {{ format_price(is_plugin_active('payment') && $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::COMPLETED ? $order->amount : 0) }}
            </x-core::table.body.cell>
        </x-core::table.body.row>
    </x-core::table.body>
</x-core::table>
