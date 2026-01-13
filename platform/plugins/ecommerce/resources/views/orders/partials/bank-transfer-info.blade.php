<div class="bank-transfer-info">
    <div class="bank-transfer-info__content">
        <div class="bank-transfer-info__icon">
            <x-core::icon name="ti ti-info-circle" />
        </div>
        <div class="bank-transfer-info__details">
            <div class="bank-transfer-info__text">
                {!! BaseHelper::clean($bankInfo) !!}
            </div>
            <p class="bank-transfer-info__amount">{!! BaseHelper::clean(
                __('Bank transfer amount: <strong>:amount</strong>', ['amount' => format_price($orderAmount)]),
            ) !!}</p>
            <p class="bank-transfer-info__description">{!! BaseHelper::clean(
                __('Bank transfer description: <strong>Payment for order :code</strong>', ['code' => str_replace('#', '', $orderCode)]),
            ) !!}</p>
        </div>
    </div>
</div>

@include('plugins/ecommerce::orders.partials.payment-proof-upload')
