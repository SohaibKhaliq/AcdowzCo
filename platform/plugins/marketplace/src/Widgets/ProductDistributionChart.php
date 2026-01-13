<?php

namespace Botble\Marketplace\Widgets;

use Botble\Base\Widgets\Chart;
use Botble\Marketplace\Models\Store;
use Illuminate\Support\Facades\DB;

class ProductDistributionChart extends Chart
{
    protected int $columns = 6;

    protected string $type = 'bar';

    public function getLabel(): string
    {
        return trans('plugins/marketplace::marketplace.reports.product_distribution');
    }

    public function getOptions(): array
    {
        $stores = Store::query()
            ->join('ec_products', 'ec_products.store_id', '=', 'mp_stores.id')
            ->whereDate('ec_products.created_at', '>=', $this->startDate)
            ->whereDate('ec_products.created_at', '<=', $this->endDate)
            ->where('ec_products.is_variation', false)
            ->select([
                'mp_stores.id',
                'mp_stores.name',
                DB::raw('COUNT(ec_products.id) as total_products'),
            ])
            ->groupBy('mp_stores.id', 'mp_stores.name')->latest('total_products')
            ->limit(10)
            ->get();

        return [
            'series' => [
                [
                    'name' => trans('plugins/marketplace::marketplace.reports.number_of_products'),
                    'data' => $stores->pluck('total_products')->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $stores->pluck('name')->toArray(),
            ],
            'colors' => ['#4ade80'],
        ];
    }
}
