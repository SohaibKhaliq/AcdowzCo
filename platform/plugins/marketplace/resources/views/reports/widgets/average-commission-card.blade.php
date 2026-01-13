<x-core::card class="analytic-card">
    <x-core::card.body class="p-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <x-core::icon
                    class="text-white bg-info rounded p-1"
                    name="ti ti-percentage"
                    size="md"
                />
            </div>
            <div class="col mt-0">
                <p class="text-secondary mb-0 fs-4">
                    {{ trans('plugins/marketplace::marketplace.reports.average_commission') }}
                </p>
                <h3 class="mb-n1 fs-1">
                    {{ number_format($rate, 2) }}%
                </h3>
            </div>
        </div>
        <div class="mt-3">
            <p class="text-secondary mb-0">
                {{ trans('plugins/marketplace::marketplace.reports.total_fee') }}: {{ format_price($total_fee) }}
            </p>
        </div>
    </x-core::card.body>
    <div class="px-3 pb-4">
        @if ($result > 0)
            <span class="text-success">
                {{ trans('plugins/marketplace::marketplace.reports.increase', ['count' => number_format($result, 2) . '%']) }}
                <x-core::icon name="ti ti-trending-up" />
            </span>
        @elseif($result < 0)
            <span class="text-danger fw-semibold">
                {{ trans('plugins/marketplace::marketplace.reports.decrease', ['count' => number_format(abs($result), 2) . '%']) }}
                <x-core::icon name="ti ti-trending-down" />
            </span>
        @else
            <span class="text-danger fw-semibold" style="visibility: hidden">&mdash;</span>
        @endif
    </div>
</x-core::card>
