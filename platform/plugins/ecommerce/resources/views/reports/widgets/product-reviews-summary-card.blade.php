<x-core::card class="analytic-card">
    <x-core::card.body class="p-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <x-core::icon
                    class="text-white bg-cyan rounded p-1"
                    name="ti ti-star"
                    size="md"
                />
            </div>
            <div class="col mt-0">
                <p class="text-secondary mb-0 fs-4">
                    {{ trans('plugins/ecommerce::reports.product_reviews') }}
                </p>
                <h3 class="mb-n1 fs-1">
                    {{ number_format($rating, 1) }} <small class="text-muted fs-6">({{ $total }} {{ trans('plugins/ecommerce::reports.reviews') }})</small>
                </h3>
            </div>
        </div>
    </x-core::card.body>
    @include('plugins/ecommerce::reports.widgets.card-description')
</x-core::card>
