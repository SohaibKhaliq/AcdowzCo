<?php

namespace Botble\Ecommerce\Widgets;

use Botble\Base\Widgets\Card;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\ProductView;
use Botble\Payment\Enums\PaymentStatusEnum;
use Carbon\CarbonPeriod;

class ConversionRateCard extends Card
{
    public function getOptions(): array
    {
        $data = [];
        $period = CarbonPeriod::create($this->startDate, $this->endDate);

        foreach ($period as $date) {
            $dateString = $date->toDateString();

            // Get total views for the day
            $totalViews = ProductView::query()
                ->whereDate('date', $dateString)
                ->sum('views');

            // Get total orders for the day
            if (is_plugin_active('payment')) {
                $totalOrders = Order::query()
                    ->whereDate('ec_orders.created_at', $dateString)
                    ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
                    ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
                    ->whereDate('payments.created_at', $dateString)
                    ->where('ec_orders.is_finished', true)
                    ->count();
            } else {
                $totalOrders = Order::query()
                    ->whereDate('created_at', $dateString)
                    ->where('is_finished', true)
                    ->count();
            }

            // Calculate conversion rate (avoid division by zero)
            $conversionRate = $totalViews > 0 ? ($totalOrders / $totalViews) * 100 : 0;

            $data[] = $conversionRate;
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
        $startDate = clone $this->startDate;
        $endDate = clone $this->endDate;

        $currentPeriod = CarbonPeriod::create($startDate, $endDate);
        $previousPeriod = CarbonPeriod::create(
            $startDate->subDays($currentPeriod->count()),
            $endDate->subDays($currentPeriod->count())
        );

        // Current period data
        $currentTotalViews = ProductView::query()
            ->whereDate('date', '>=', $currentPeriod->getStartDate())
            ->whereDate('date', '<=', $currentPeriod->getEndDate())
            ->sum('views');

        if (is_plugin_active('payment')) {
            $currentTotalOrders = Order::query()
                ->whereDate('ec_orders.created_at', '>=', $currentPeriod->getStartDate())
                ->whereDate('ec_orders.created_at', '<=', $currentPeriod->getEndDate())
                ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
                ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
                ->whereDate('payments.created_at', '>=', $currentPeriod->getStartDate())
                ->whereDate('payments.created_at', '<=', $currentPeriod->getEndDate())
                ->where('ec_orders.is_finished', true)
                ->count();
        } else {
            $currentTotalOrders = Order::query()
                ->whereDate('created_at', '>=', $currentPeriod->getStartDate())
                ->whereDate('created_at', '<=', $currentPeriod->getEndDate())
                ->where('is_finished', true)
                ->count();
        }

        $currentConversionRate = $currentTotalViews > 0 ? ($currentTotalOrders / $currentTotalViews) * 100 : 0;

        // Previous period data
        $previousTotalViews = ProductView::query()
            ->whereDate('date', '>=', $previousPeriod->getStartDate())
            ->whereDate('date', '<=', $previousPeriod->getEndDate())
            ->sum('views');

        if (is_plugin_active('payment')) {
            $previousTotalOrders = Order::query()
                ->whereDate('ec_orders.created_at', '>=', $previousPeriod->getStartDate())
                ->whereDate('ec_orders.created_at', '<=', $previousPeriod->getEndDate())
                ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
                ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
                ->whereDate('payments.created_at', '>=', $previousPeriod->getStartDate())
                ->whereDate('payments.created_at', '<=', $previousPeriod->getEndDate())
                ->where('ec_orders.is_finished', true)
                ->count();
        } else {
            $previousTotalOrders = Order::query()
                ->whereDate('created_at', '>=', $previousPeriod->getStartDate())
                ->whereDate('created_at', '<=', $previousPeriod->getEndDate())
                ->where('is_finished', true)
                ->count();
        }

        $previousConversionRate = $previousTotalViews > 0 ? ($previousTotalOrders / $previousTotalViews) * 100 : 0;

        // Calculate percentage change
        $result = ($previousConversionRate > 0) ? (($currentConversionRate - $previousConversionRate) / $previousConversionRate) * 100 : 0;

        $result > 0 ? $this->chartColor = '#4ade80' : $this->chartColor = '#ff5b5b';

        return array_merge(parent::getViewData(), [
            'content' => view(
                'plugins/ecommerce::reports.widgets.conversion-rate-card',
                [
                    'rate' => $currentConversionRate,
                    'result' => $result,
                ]
            )->render(),
        ]);
    }

    public function getLabel(): string
    {
        return trans('plugins/ecommerce::reports.conversion_rate');
    }
}
