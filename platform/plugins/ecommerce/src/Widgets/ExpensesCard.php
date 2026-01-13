<?php

namespace Botble\Ecommerce\Widgets;

use Botble\Base\Widgets\Card;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Models\OrderProduct;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class ExpensesCard extends Card
{
    public function getOptions(): array
    {
        $data = OrderProduct::query()
            ->join('ec_orders', 'ec_orders.id', '=', 'ec_order_product.order_id')
            ->join('ec_products', 'ec_products.id', '=', 'ec_order_product.product_id')
            ->whereDate('ec_orders.created_at', '>=', $this->startDate)
            ->whereDate('ec_orders.created_at', '<=', $this->endDate)
            ->where('ec_orders.is_finished', true)
            ->where('ec_orders.status', OrderStatusEnum::COMPLETED)
            ->select([
                DB::raw('SUM(COALESCE(ec_products.cost_per_item, 0) * ec_order_product.qty) as expenses'),
                DB::raw('date_format(ec_orders.created_at, "' . $this->dateFormat . '") as period'),
            ])
            ->groupBy('period')
            ->pluck('expenses')
            ->toArray();

        // Ensure we have at least 2 data points for the chart to render
        if (empty($data)) {
            $data = [0, 0];
        } elseif (count($data) === 1) {
            $data[] = 0;
        }

        return [
            'series' => [
                [
                    'data' => $data,
                ],
            ],
        ];
    }

    public function getViewData(): array
    {
        // Calculate current period expenses
        $currentExpenses = OrderProduct::query()
            ->join('ec_orders', 'ec_orders.id', '=', 'ec_order_product.order_id')
            ->join('ec_products', 'ec_products.id', '=', 'ec_order_product.product_id')
            ->whereDate('ec_orders.created_at', '>=', $this->startDate)
            ->whereDate('ec_orders.created_at', '<=', $this->endDate)
            ->where('ec_orders.is_finished', true)
            ->where('ec_orders.status', OrderStatusEnum::COMPLETED)
            ->select([
                DB::raw('SUM(COALESCE(ec_products.cost_per_item, 0) * ec_order_product.qty) as expenses'),
            ])
            ->value('expenses') ?? 0;

        // Calculate previous period for comparison
        $startDate = clone $this->startDate;
        $endDate = clone $this->endDate;

        $currentPeriod = CarbonPeriod::create($startDate, $endDate);
        $previousPeriod = CarbonPeriod::create($startDate->subDays($currentPeriod->count()), $endDate->subDays($currentPeriod->count()));

        $previousExpenses = OrderProduct::query()
            ->join('ec_orders', 'ec_orders.id', '=', 'ec_order_product.order_id')
            ->join('ec_products', 'ec_products.id', '=', 'ec_order_product.product_id')
            ->whereDate('ec_orders.created_at', '>=', $previousPeriod->getStartDate())
            ->whereDate('ec_orders.created_at', '<=', $previousPeriod->getEndDate())
            ->where('ec_orders.is_finished', true)
            ->where('ec_orders.status', OrderStatusEnum::COMPLETED)
            ->select([
                DB::raw('SUM(COALESCE(ec_products.cost_per_item, 0) * ec_order_product.qty) as expenses'),
            ])
            ->value('expenses') ?? 0;

        $result = $currentExpenses - $previousExpenses;

        // For expenses, higher is typically worse (red), lower is better (green)
        $result > 0 ? $this->chartColor = '#ff5b5b' : $this->chartColor = '#4ade80';

        return array_merge(parent::getViewData(), [
            'content' => view(
                'plugins/ecommerce::reports.widgets.expenses-card',
                compact('currentExpenses', 'result')
            )->render(),
        ]);
    }
}
