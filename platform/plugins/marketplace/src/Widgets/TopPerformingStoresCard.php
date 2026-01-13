<?php

namespace Botble\Marketplace\Widgets;

use Botble\Base\Widgets\Card;
use Botble\Marketplace\Models\Store;
use Illuminate\Support\Facades\DB;

class TopPerformingStoresCard extends Card
{
    protected int $columns = 6;

    public function getOptions(): array
    {
        $data = Store::query()
            ->join('ec_orders', 'ec_orders.store_id', '=', 'mp_stores.id')
            ->whereDate('ec_orders.created_at', '>=', $this->startDate)
            ->whereDate('ec_orders.created_at', '<=', $this->endDate)
            ->where('ec_orders.is_finished', true)
            ->select([
                'mp_stores.id',
                'mp_stores.name',
                DB::raw('COUNT(ec_orders.id) as total_orders'),
                DB::raw('SUM(ec_orders.amount) as total_revenue'),
            ])
            ->groupBy('mp_stores.id', 'mp_stores.name')->latest('total_revenue')
            ->limit(5)
            ->get();

        return [
            'series' => [
                [
                    'data' => $data->pluck('total_revenue')->toArray(),
                ],
            ],
        ];
    }

    public function getViewData(): array
    {
        $stores = Store::query()
            ->join('ec_orders', 'ec_orders.store_id', '=', 'mp_stores.id')
            ->whereDate('ec_orders.created_at', '>=', $this->startDate)
            ->whereDate('ec_orders.created_at', '<=', $this->endDate)
            ->where('ec_orders.is_finished', true)
            ->select([
                'mp_stores.id',
                'mp_stores.name',
                'mp_stores.logo',
                DB::raw('COUNT(ec_orders.id) as total_orders'),
                DB::raw('SUM(ec_orders.amount) as total_revenue'),
            ])
            ->groupBy('mp_stores.id', 'mp_stores.name', 'mp_stores.logo')->latest('total_revenue')
            ->limit(5)
            ->get();

        return array_merge(parent::getViewData(), [
            'content' => view(
                'plugins/marketplace::reports.widgets.top-performing-stores-card',
                compact('stores')
            )->render(),
        ]);
    }

    public function getLabel(): string
    {
        return trans('plugins/marketplace::marketplace.reports.top_performing_stores');
    }
}
