<?php

namespace Botble\Marketplace\Widgets;

use Botble\Base\Widgets\Chart;
use Botble\Ecommerce\Widgets\Traits\HasCategory;
use Botble\Marketplace\Models\Store;

class StoreGrowthChart extends Chart
{
    use HasCategory;

    protected int $columns = 6;

    public function getLabel(): string
    {
        return trans('plugins/marketplace::marketplace.reports.store_growth');
    }

    public function getOptions(): array
    {
        $data = Store::query()
            ->selectRaw('count(id) as total, date_format(created_at, "' . $this->dateFormat . '") as period')
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->groupBy('period')
            ->pluck('total', 'period')
            ->all();

        return [
            'series' => [
                [
                    'name' => trans('plugins/marketplace::marketplace.reports.number_of_stores'),
                    'data' => array_values($data),
                ],
            ],
            'xaxis' => [
                'categories' => $this->translateCategories($data),
            ],
        ];
    }
}
