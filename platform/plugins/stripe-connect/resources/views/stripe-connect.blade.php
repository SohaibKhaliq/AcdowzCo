<div class="mb-3">
    @if($customer->stripe_account_active)
        <x-core::form.label>{{ trans('plugins/stripe-connect::stripe-connect.notifications.connected') }}</x-core::form.label>

        <div class="btn-list mt-2">
            <x-core::button
                tag="a"
                :href="route('stripe-connect.dashboard')"
                target="_blank"
                icon="ti ti-brand-stripe"
                color="success"
                size="sm"
            >
                {{ trans('plugins/stripe-connect::stripe-connect.go_to_dashboard') }}
            </x-core::button>

            <x-core::button
                tag="a"
                :href="route('stripe-connect.disconnect')"
                icon="ti ti-x"
                color="danger"
                size="sm"
                onclick="return confirm('{{ trans('plugins/stripe-connect::stripe-connect.disconnect.confirm') }}');"
            >
                {{ trans('plugins/stripe-connect::stripe-connect.disconnect.label') }}
            </x-core::button>
        </div>
    @else
        <x-core::form.label>{{ trans('plugins/stripe-connect::stripe-connect.connect.description') }}</x-core::form.label>

        <div class="mt-2">
            <x-core::button
                tag="a"
                :href="route('stripe-connect.connect')"
                icon="ti ti-brand-stripe"
                color="primary"
                size="sm"
            >
                {{ trans('plugins/stripe-connect::stripe-connect.connect.label') }}
            </x-core::button>
        </div>
    @endif
</div>
