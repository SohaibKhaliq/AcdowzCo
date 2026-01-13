<x-core::form-group>
    <x-core::form.label class="required" for="payout_methods">
        {{ trans('plugins/marketplace::marketplace.settings.payout_methods') }}
    </x-core::form.label>
    @php
        $payoutMethods = Botble\Marketplace\Enums\PayoutPaymentMethodsEnum::payoutMethods();
    @endphp
    @foreach (Botble\Marketplace\Enums\PayoutPaymentMethodsEnum::labels() as $key => $item)
        <input type="hidden" name="{{ 'payout_methods[' . $key . ']' }}" value="0">
        <x-core::form.checkbox
            :label="$item"
            name="{{ 'payout_methods[' . $key . ']' }}"
            :checked="Arr::get(old('payout_methods', MarketplaceHelper::getSetting('payout_methods')), $key, Arr::get($payoutMethods, $key . '.is_enabled', false))"
            value="1"
            :inline="true"
        />
    @endforeach
</x-core::form-group>
