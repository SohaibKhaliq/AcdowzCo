<div
    class="alert alert-warning"
    style="margin-top: 15px;"
>
    {{ __('Minimum order amount to use :name payment method is :amount, you need to buy more :more to place an order!', ['name' => $paymentLabel, 'amount' => format_price($minimumOrderAmount), 'more' => format_price($minimumOrderAmount - Cart::instance('cart')->rawSubTotal())]) }}
</div>
