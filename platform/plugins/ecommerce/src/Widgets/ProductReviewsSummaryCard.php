<?php

namespace Botble\Ecommerce\Widgets;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Widgets\Card;
use Botble\Ecommerce\Models\Review;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class ProductReviewsSummaryCard extends Card
{
    public function getColumns(): int
    {
        return 6; // Half width for better layout
    }

    public function getOptions(): array
    {
        $data = Review::query()
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->selectRaw('DATE(created_at) as date, AVG(star) as average_rating')
            ->groupBy('date')
            ->pluck('average_rating')
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
        $startDate = clone $this->startDate;
        $endDate = clone $this->endDate;

        $currentPeriod = CarbonPeriod::create($startDate, $endDate);
        $previousPeriod = CarbonPeriod::create(
            $startDate->subDays($currentPeriod->count()),
            $endDate->subDays($currentPeriod->count())
        );

        // Current period data
        $currentData = Review::query()
            ->whereDate('created_at', '>=', $currentPeriod->getStartDate())
            ->whereDate('created_at', '<=', $currentPeriod->getEndDate())
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->select([
                DB::raw('COUNT(*) as total_reviews'),
                DB::raw('AVG(star) as average_rating'),
            ])
            ->first();

        // Previous period data
        $previousData = Review::query()
            ->whereDate('created_at', '>=', $previousPeriod->getStartDate())
            ->whereDate('created_at', '<=', $previousPeriod->getEndDate())
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->select([
                DB::raw('COUNT(*) as total_reviews'),
                DB::raw('AVG(star) as average_rating'),
            ])
            ->first();

        $currentRating = $currentData->average_rating ?? 0;
        $previousRating = $previousData->average_rating ?? 0;
        $totalReviews = $currentData->total_reviews ?? 0;

        $result = ($previousRating > 0) ? (($currentRating - $previousRating) / $previousRating) * 100 : 0;

        $result > 0 ? $this->chartColor = '#4ade80' : $this->chartColor = '#ff5b5b';

        return array_merge(parent::getViewData(), [
            'content' => view(
                'plugins/ecommerce::reports.widgets.product-reviews-summary-card',
                [
                    'rating' => $currentRating,
                    'total' => $totalReviews,
                    'result' => $result,
                ]
            )->render(),
        ]);
    }

    public function getLabel(): string
    {
        return trans('plugins/ecommerce::reports.product_reviews');
    }
}
