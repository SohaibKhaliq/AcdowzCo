<?php

namespace Botble\Marketplace\Listeners;

use Botble\Base\Events\RenderingAdminWidgetEvent;
use Botble\Marketplace\Widgets\AverageCommissionCard;
use Botble\Marketplace\Widgets\ProductDistributionChart;
use Botble\Marketplace\Widgets\RecentWithdrawalsTable;
use Botble\Marketplace\Widgets\SaleCommissionHtml;
use Botble\Marketplace\Widgets\StoreGrowthChart;
use Botble\Marketplace\Widgets\StoreRevenueTable;
use Botble\Marketplace\Widgets\TopPerformingStoresCard;
use Botble\Marketplace\Widgets\WithdrawalStatusChart;

class RegisterMarketplaceWidget
{
    public function handle(RenderingAdminWidgetEvent $event): void
    {
        $event
            ->widget
            ->register([
                AverageCommissionCard::class,
                SaleCommissionHtml::class,
                StoreGrowthChart::class,
                ProductDistributionChart::class,
                TopPerformingStoresCard::class,
                WithdrawalStatusChart::class,
                StoreRevenueTable::class,
                RecentWithdrawalsTable::class,
            ], MARKETPLACE_MODULE_SCREEN_NAME);
    }
}
