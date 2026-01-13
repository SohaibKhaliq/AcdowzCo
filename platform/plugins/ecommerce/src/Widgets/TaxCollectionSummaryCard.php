<?php

namespace Botble\Ecommerce\Widgets;

use Botble\Base\Widgets\Card;
use Botble\Ecommerce\Models\Order;
use Botble\Payment\Enums\PaymentStatusEnum;
use Carbon\CarbonPeriod;

class TaxCollectionSummaryCard extends Card
{
    public function getColumns(): int
    {
        return 6; // Half width for better layout
    }

    public function getOptions(): array
    {
        $data = [];

        if (is_plugin_active('payment')) {
            $data = Order::query()
                ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
                ->whereDate('ec_orders.created_at', '>=', $this->startDate)
                ->whereDate('ec_orders.created_at', '<=', $this->endDate)
                ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
                ->whereDate('payments.created_at', '>=', $this->startDate)
                ->whereDate('payments.created_at', '<=', $this->endDate)
                ->where('ec_orders.is_finished', true)
                ->selectRaw('DATE(ec_orders.created_at) as date, SUM(ec_orders.tax_amount) as tax')
                ->groupBy('date')
                ->pluck('tax')
                ->toArray();
        } else {
            $data = Order::query()
                ->whereDate('created_at', '>=', $this->startDate)
                ->whereDate('created_at', '<=', $this->endDate)
                ->where('is_finished', true)
                ->selectRaw('DATE(created_at) as date, SUM(tax_amount) as tax')
                ->groupBy('date')
                ->pluck('tax')
                ->toArray();
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

        // Current period tax
        if (is_plugin_active('payment')) {
            $currentTax = Order::query()
                ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
                ->whereDate('ec_orders.created_at', '>=', $currentPeriod->getStartDate())
                ->whereDate('ec_orders.created_at', '<=', $currentPeriod->getEndDate())
                ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
                ->whereDate('payments.created_at', '>=', $currentPeriod->getStartDate())
                ->whereDate('payments.created_at', '<=', $currentPeriod->getEndDate())
                ->where('ec_orders.is_finished', true)
                ->sum('ec_orders.tax_amount');
        } else {
            $currentTax = Order::query()
                ->whereDate('created_at', '>=', $currentPeriod->getStartDate())
                ->whereDate('created_at', '<=', $currentPeriod->getEndDate())
                ->where('is_finished', true)
                ->sum('tax_amount');
        }

        // Previous period tax
        if (is_plugin_active('payment')) {
            $previousTax = Order::query()
                ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
                ->whereDate('ec_orders.created_at', '>=', $previousPeriod->getStartDate())
                ->whereDate('ec_orders.created_at', '<=', $previousPeriod->getEndDate())
                ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
                ->whereDate('payments.created_at', '>=', $previousPeriod->getStartDate())
                ->whereDate('payments.created_at', '<=', $previousPeriod->getEndDate())
                ->where('ec_orders.is_finished', true)
                ->sum('ec_orders.tax_amount');
        } else {
            $previousTax = Order::query()
                ->whereDate('created_at', '>=', $previousPeriod->getStartDate())
                ->whereDate('created_at', '<=', $previousPeriod->getEndDate())
                ->where('is_finished', true)
                ->sum('tax_amount');
        }

        $result = ($previousTax > 0) ? (($currentTax - $previousTax) / $previousTax) * 100 : 0;

        $result > 0 ? $this->chartColor = '#4ade80' : $this->chartColor = '#ff5b5b';

        return array_merge(parent::getViewData(), [
            'content' => view(
                'plugins/ecommerce::reports.widgets.tax-collection-summary-card',
                [
                    'tax' => $currentTax,
                    'result' => $result,
                ]
            )->render(),
        ]);
    }

    public function getLabel(): string
    {
        return trans('plugins/ecommerce::reports.tax_collection');
    }
}
