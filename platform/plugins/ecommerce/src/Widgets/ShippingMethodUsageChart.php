<?php

namespace Botble\Ecommerce\Widgets;

use Botble\Base\Widgets\Chart;
use Botble\Ecommerce\Models\Shipment;
use Illuminate\Support\Facades\DB;

class ShippingMethodUsageChart extends Chart
{
    protected int $columns = 6;

    protected string $type = 'bar';

    public function getLabel(): string
    {
        return trans('plugins/ecommerce::reports.shipping_method_usage');
    }

    public function getOptions(): array
    {
        $shipments = Shipment::query()
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->select([
                'shipping_company_name',
                DB::raw('count(*) as total'),
            ])
            ->groupBy('shipping_company_name')
            ->latest('total')
            ->get();

        $data = [];
        $categories = [];

        foreach ($shipments as $shipment) {
            $data[] = $shipment->total;
            $categories[] = $shipment->shipping_company_name ?: trans('plugins/ecommerce::reports.unknown');
        }

        return [
            'series' => [
                [
                    'name' => trans('plugins/ecommerce::reports.shipments'),
                    'data' => $data,
                ],
            ],
            'chart' => [
                'height' => 350,
                'type' => 'bar',
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                    'columnWidth' => '55%',
                    'endingShape' => 'rounded',
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'stroke' => [
                'show' => true,
                'width' => 2,
                'colors' => ['transparent'],
            ],
            'xaxis' => [
                'categories' => $categories,
            ],
            'yaxis' => [
                'title' => [
                    'text' => trans('plugins/ecommerce::reports.shipments'),
                ],
            ],
            'fill' => [
                'opacity' => 1,
            ],
            'tooltip' => [
                'y' => [
                    'formatter' => 'function (val) {
                        return val + " ' . trans('plugins/ecommerce::reports.shipments') . '"
                    }',
                ],
            ],
            'colors' => ['#4ade80'],
        ];
    }
}
