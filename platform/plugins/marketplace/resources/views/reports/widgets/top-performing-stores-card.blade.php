<x-core::card class="analytic-card">
    <x-core::card.body class="p-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <x-core::icon
                    class="text-white bg-success rounded p-1"
                    name="ti ti-building-store"
                    size="md"
                />
            </div>
            <div class="col mt-0">
                <p class="text-secondary mb-0 fs-4">
                    {{ trans('plugins/marketplace::marketplace.reports.top_performing_stores') }}
                </p>
            </div>
        </div>

        <div class="mt-3">
            @if ($stores->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>{{ trans('plugins/marketplace::store.store') }}</th>
                                <th>{{ trans('plugins/marketplace::marketplace.reports.orders') }}</th>
                                <th>{{ trans('plugins/marketplace::marketplace.reports.revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stores as $store)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $store->logo_url }}" alt="{{ $store->name }}" class="rounded-circle me-2" width="30">
                                            <a href="{{ route('marketplace.store.view', $store->id) }}">{{ $store->name }}</a>
                                        </div>
                                    </td>
                                    <td>{{ number_format($store->total_orders) }}</td>
                                    <td>{{ format_price($store->total_revenue) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                @include('core/dashboard::partials.no-data')
            @endif
        </div>
    </x-core::card.body>
</x-core::card>
