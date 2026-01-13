<?php

namespace Botble\Marketplace\Widgets;

use Botble\Base\Widgets\Card;
use Botble\Marketplace\Enums\RevenueTypeEnum;
use Botble\Marketplace\Models\Revenue;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class AverageCommissionCard extends Card
{
    protected int $columns = 3;

    public function getOptions(): array
    {
        $data = Revenue::query()
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->where(function ($query): void {
                $query->whereNull('type')
                    ->orWhere('type', RevenueTypeEnum::ADD_AMOUNT);
            })
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('AVG(CASE WHEN amount > 0 THEN fee / amount * 100 ELSE 0 END) as commission_rate'),
            ])
            ->groupBy('date')
            ->pluck('commission_rate')
            ->toArray();

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
        // Current period
        $commission = Revenue::query()
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->where(function ($query): void {
                $query->whereNull('type')
                    ->orWhere('type', RevenueTypeEnum::ADD_AMOUNT);
            })
            ->select([
                DB::raw('SUM(fee) as total_fee'),
                DB::raw('SUM(amount) as total_amount'),
            ])
            ->first();

        $rate = 0;
        if ($commission->total_amount > 0) {
            $rate = ($commission->total_fee / $commission->total_amount) * 100;
        }

        // Previous period
        $previousPeriod = CarbonPeriod::create(
            $this->startDate->clone()->subDays($this->startDate->diffInDays($this->endDate)),
            $this->startDate->clone()->subDay()
        );

        $previousCommission = Revenue::query()
            ->whereDate('created_at', '>=', $previousPeriod->getStartDate())
            ->whereDate('created_at', '<=', $previousPeriod->getEndDate())
            ->where(function ($query): void {
                $query->whereNull('type')
                    ->orWhere('type', RevenueTypeEnum::ADD_AMOUNT);
            })
            ->select([
                DB::raw('SUM(fee) as total_fee'),
                DB::raw('SUM(amount) as total_amount'),
            ])
            ->first();

        $previousRate = 0;
        if ($previousCommission->total_amount > 0) {
            $previousRate = ($previousCommission->total_fee / $previousCommission->total_amount) * 100;
        }

        $result = $rate - $previousRate;
        $result > 0 ? $this->chartColor = '#4ade80' : $this->chartColor = '#ff5b5b';

        return array_merge(parent::getViewData(), [
            'content' => view(
                'plugins/marketplace::reports.widgets.average-commission-card',
                [
                    'rate' => $rate,
                    'total_fee' => $commission->total_fee,
                    'result' => $result,
                ]
            )->render(),
        ]);
    }

    public function getLabel(): string
    {
        return trans('plugins/marketplace::marketplace.reports.average_commission');
    }
}
