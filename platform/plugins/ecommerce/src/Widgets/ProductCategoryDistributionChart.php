<?php

namespace Botble\Ecommerce\Widgets;

use Botble\Base\Widgets\Chart;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;

class ProductCategoryDistributionChart extends Chart
{
    protected int $columns = 6;

    protected string $type = 'pie';

    public function getLabel(): string
    {
        return trans('plugins/ecommerce::reports.product_category_distribution');
    }

    public function getOptions(): array
    {
        $categories = ProductCategory::query()
            ->wherePublished()
            ->with(['products' => function ($query): void {
                $query->wherePublished()
                    ->where('is_variation', false)
                    ->whereDate('created_at', '>=', $this->startDate)
                    ->whereDate('created_at', '<=', $this->endDate);
            }])
            ->withCount(['products' => function ($query): void {
                $query->wherePublished()
                    ->where('is_variation', false)
                    ->whereDate('created_at', '>=', $this->startDate)
                    ->whereDate('created_at', '<=', $this->endDate);
            }])
            ->latest('products_count')
            ->limit(6)
            ->get();

        $series = [];
        $labels = [];

        foreach ($categories as $category) {
            if ($category->products_count > 0) {
                $series[] = $category->products_count;
                $labels[] = $category->name . ' (' . $category->products_count . ')';
            }
        }

        // If we have less than 6 categories with products, add an "Others" category
        $totalProducts = Product::query()
            ->wherePublished()
            ->where('is_variation', false)
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->count();

        $categorizedProducts = array_sum($series);

        if ($totalProducts > $categorizedProducts) {
            $series[] = $totalProducts - $categorizedProducts;
            $labels[] = trans('plugins/ecommerce::reports.others') . ' (' . ($totalProducts - $categorizedProducts) . ')';
        }

        $colors = ['#4ade80', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6', '#ec4899', '#6366f1'];

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
