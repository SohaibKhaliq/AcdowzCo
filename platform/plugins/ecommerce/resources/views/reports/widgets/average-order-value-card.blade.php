<x-core::card class="analytic-card">
    <x-core::card.body class="p-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <x-core::icon
                    class="text-white bg-indigo rounded p-1"
                    name="ti ti-receipt"
                    size="md"
                />
            </div>
            <div class="col mt-0">
                <p class="text-secondary mb-0 fs-4">
                    {{ trans('plugins/ecommerce::reports.average_order_value') }}
                </p>
                <h3 class="mb-n1 fs-1">
                    {{ format_price($average) }}
                </h3>
            </div>
        </div>
    </x-core::card.body>
    @include('plugins/ecommerce::reports.widgets.card-description')
</x-core::card>
