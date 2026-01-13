<?php

namespace Botble\Ecommerce\Widgets;

use Botble\Base\Widgets\Chart;
use Botble\Ecommerce\Models\Order;
use Botble\Payment\Enums\PaymentStatusEnum;
use Carbon\CarbonPeriod;

class CustomerRetentionChart extends Chart
{
    protected int $columns = 6;

    protected string $type = 'line';

    public function getLabel(): string
    {
        return trans('plugins/ecommerce::reports.customer_retention');
    }

    public function getOptions(): array
    {
        $period = CarbonPeriod::create($this->startDate, $this->endDate);
        $dates = [];
        $newCustomers = [];
        $returningCustomers = [];

        foreach ($period as $date) {
            $dateString = $date->toDateString();
            $dates[] = $dateString;

            // Get all orders for this date
            $query = Order::query()
                ->whereDate('ec_orders.created_at', $dateString)
                ->where('ec_orders.is_finished', true);

            if (is_plugin_active('payment')) {
                $query->join('payments', 'payments.order_id', '=', 'ec_orders.id')
                    ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
                    ->whereDate('payments.created_at', $dateString);
            }

            // Get unique customer IDs for this date
            $customerIds = $query->pluck('ec_orders.user_id')->filter()->unique()->toArray();

            // For each customer, check if they have previous orders
            $new = 0;
            $returning = 0;

            foreach ($customerIds as $customerId) {
                $previousOrders = Order::query()
                    ->where('ec_orders.user_id', $customerId)
                    ->where('ec_orders.is_finished', true)
                    ->whereDate('ec_orders.created_at', '<', $dateString);

                if (is_plugin_active('payment')) {
                    $previousOrders = $previousOrders->join('payments', 'payments.order_id', '=', 'ec_orders.id')
                        ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
                        ->whereDate('payments.created_at', '<', $dateString);
                }

                if ($previousOrders->exists()) {
                    $returning++;
                } else {
                    $new++;
                }
            }

            $newCustomers[] = $new;
            $returningCustomers[] = $returning;
        }

        return [
            'series' => [
                [
                    'name' => trans('plugins/ecommerce::reports.new_customers'),
                    'data' => $newCustomers,
                ],
                [
                    'name' => trans('plugins/ecommerce::reports.returning_customers'),
                    'data' => $returningCustomers,
                ],
            ],
            'chart' => [
                'height' => 350,
                'type' => 'line',
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'stroke' => [
                'curve' => 'smooth',
            ],
            'xaxis' => [
                'type' => 'datetime',
                'categories' => $dates,
            ],
            'tooltip' => [
                'x' => [
                    'format' => 'dd/MM/yy',
                ],
            ],
            'colors' => ['#4ade80', '#f59e0b'],
        ];
    }
}
