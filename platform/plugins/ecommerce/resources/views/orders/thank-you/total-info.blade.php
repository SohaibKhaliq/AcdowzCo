@if (
    $order->sub_total != $order->amount
    || $order->shipping_method->getValue()
    || (EcommerceHelper::isTaxEnabled() && (float) $order->tax_amount)
    || (float) $order->discount_amount
)
    <hr class="border-dark-subtle" />
@endif

@if ($order->sub_total != $order->amount)
    @include('plugins/ecommerce::orders.thank-you.total-row', [
        'label' => __('Subtotal'),
        'value' => format_price($order->sub_total),
    ])
@endif

@if ($order->shipping_method->getValue())
    @include('plugins/ecommerce::orders.thank-you.total-row', [
        'label' =>
            __('Shipping fee') .
            ($order->is_free_shipping
                ? ' <small>(' . __('Using coupon code') . ': <strong>' . $order->coupon_code . '</strong>)</small>'
                : ''),
        'value' => $order->shipping_method_name . ((float) $order->shipping_amount ? ' - ' . format_price($order->shipping_amount) : ' - ' . __('Free')),
    ])
@endif

@if (EcommerceHelper::isTaxEnabled() && (float) $order->tax_amount)
    @php
        $taxGroups = [];
        foreach ($order->products as $orderProduct) {
            if ($orderProduct->tax_amount > 0 && !empty($orderProduct->options['taxClasses'])) {
                foreach ($orderProduct->options['taxClasses'] as $taxName => $taxRate) {
                    $taxKey = $taxName . ' (' . $taxRate . '%)';
                    if (!isset($taxGroups[$taxKey])) {
                        $taxGroups[$taxKey] = 0;
                    }
                    $taxGroups[$taxKey] += $orderProduct->tax_amount;
                }
            }
        }
    @endphp

    @if (!empty($taxGroups))
        @foreach ($taxGroups as $taxName => $taxAmount)
            @include('plugins/ecommerce::orders.thank-you.total-row', [
                'label' => __('Tax') . ' <small>(' . $taxName . ')</small>',
                'value' => format_price($taxAmount),
            ])
        @endforeach
    @else
        @include('plugins/ecommerce::orders.thank-you.total-row', [
            'label' => __('Tax'),
            'value' => format_price($order->tax_amount),
        ])
    @endif
@endif

@if ((float) $order->discount_amount)
    @include('plugins/ecommerce::orders.thank-you.total-row', [
        'label' => __('Discount'),
        'value' =>
            format_price($order->discount_amount) .
            ($order->coupon_code
                ? ' <small>(' . __('Using coupon code') . ': <strong>' . $order->coupon_code . '</strong>)</small>'
                : ''),
    ])
@endif

@if ((float) $order->payment_fee)
    @include('plugins/ecommerce::orders.thank-you.total-row', [
        'label' => __('plugins/payment::payment.payment_fee'),
        'value' => format_price($order->payment_fee),
    ])
@endif

{!! apply_filters('ecommerce_thank_you_total_info', null, $order) !!}

<hr class="border-dark-subtle" />

<div class="row">
    <div class="col-6">
        <p>{{ __('Total') }}:</p>
    </div>
    <div class="col-6 float-end">
        <p class="total-text raw-total-text"> {{ format_price($order->amount) }} </p>
    </div>
</div>
