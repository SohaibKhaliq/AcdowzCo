<?php

namespace Botble\Marketplace\Widgets;

use Botble\Base\Widgets\Chart;
use Botble\Marketplace\Enums\WithdrawalStatusEnum;
use Botble\Marketplace\Models\Withdrawal;
use Illuminate\Support\Facades\DB;

class WithdrawalStatusChart extends Chart
{
    protected int $columns = 6;

    protected string $type = 'pie';

    public function getLabel(): string
    {
        return trans('plugins/marketplace::marketplace.reports.withdrawal_status_chart');
    }

    public function getOptions(): array
    {
        $data = Withdrawal::query()
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->select([
                'status',
                DB::raw('count(*) as total'),
                DB::raw('sum(amount) as amount'),
            ])
            ->groupBy('status')
            ->get();

        $statuses = WithdrawalStatusEnum::labels();
        $colors = ['#4ade80', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6'];

        $series = [];
        $labels = [];

        foreach ($data as $item) {
            $series[] = $item->total;
            $labels[] = $statuses[$item->status->getValue()] . ' (' . format_price($item->amount) . ')';
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
