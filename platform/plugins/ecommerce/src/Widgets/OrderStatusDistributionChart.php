<?php

namespace Botble\Ecommerce\Widgets;

use Botble\Base\Widgets\Chart;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderStatusDistributionChart extends Chart
{
    protected int $columns = 6;

    protected string $type = 'pie';

    public function getLabel(): string
    {
        return trans('plugins/ecommerce::reports.order_status_distribution');
    }

    public function getOptions(): array
    {
        $orders = Order::query()
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->select([
                'status',
                DB::raw('count(*) as total'),
                DB::raw('sum(amount) as amount'),
            ])
            ->groupBy('status')
            ->get();

        $series = [];
        $labels = [];

        $statusColors = [
            OrderStatusEnum::PENDING => '#f59e0b',
            OrderStatusEnum::PROCESSING => '#3b82f6',
            OrderStatusEnum::COMPLETED => '#4ade80',
            OrderStatusEnum::CANCELED => '#ef4444',
            OrderStatusEnum::PARTIAL_RETURNED => '#8b5cf6',
            OrderStatusEnum::RETURNED => '#06b6d4',
        ];

        // Convert enum constants to their string values for array keys
        $statusColors = array_combine(
            array_map(fn ($status) => is_object($status) ? $status->getValue() : $status, array_keys($statusColors)),
            array_values($statusColors)
        );

        $colors = [];

        foreach ($orders as $order) {
            $series[] = $order->total;

            $statusLabel = OrderStatusEnum::getLabel($order->status);
            $labels[] = $statusLabel . ' (' . format_price($order->amount) . ')';

            $status = is_object($order->status) ? $order->status->getValue() : $order->status;
            $colors[] = $statusColors[$status] ?? '#6b7280';
        }

        return [
            'series' => $series,
            'chart' => [
                'height' => 350,
                'type' => 'pie',
            ],
            'colors' => $colors,
            'labels' => $labels,
            'legend' => [
                'position' => 'bottom',
            ],
        ];
    }
}
