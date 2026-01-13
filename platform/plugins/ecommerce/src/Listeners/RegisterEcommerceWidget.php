<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Base\Events\RenderingAdminWidgetEvent;
use Botble\Ecommerce\Widgets\AverageOrderValueCard;
use Botble\Ecommerce\Widgets\ConversionRateCard;
use Botble\Ecommerce\Widgets\CustomerChart;
use Botble\Ecommerce\Widgets\CustomerRetentionChart;
use Botble\Ecommerce\Widgets\ExpensesCard;
use Botble\Ecommerce\Widgets\NewCustomerCard;
use Botble\Ecommerce\Widgets\NewOrderCard;
use Botble\Ecommerce\Widgets\NewProductCard;
use Botble\Ecommerce\Widgets\OrderChart;
use Botble\Ecommerce\Widgets\OrderStatusDistributionChart;
use Botble\Ecommerce\Widgets\PaymentMethodDistributionChart;
use Botble\Ecommerce\Widgets\ProductCategoryDistributionChart;
use Botble\Ecommerce\Widgets\ProductReviewsSummaryCard;
use Botble\Ecommerce\Widgets\ProfitCard;
use Botble\Ecommerce\Widgets\RecentOrdersTable;
use Botble\Ecommerce\Widgets\ReportGeneralHtml;
use Botble\Ecommerce\Widgets\RevenueCard;
use Botble\Ecommerce\Widgets\ShippingMethodUsageChart;
use Botble\Ecommerce\Widgets\TaxCollectionSummaryCard;
use Botble\Ecommerce\Widgets\TopSellingProductsTable;
use Botble\Ecommerce\Widgets\TrendingProductsTable;
use Illuminate\Support\Facades\Auth;

class RegisterEcommerceWidget
{
    public function handle(RenderingAdminWidgetEvent $event): void
    {
        $allWidgets = [
            // Financial Metrics (Top Row)
            RevenueCard::class,
            ProfitCard::class,
            ExpensesCard::class,
            AverageOrderValueCard::class,

            // Activity Metrics (Second Row)
            NewOrderCard::class,
            NewCustomerCard::class,
            NewProductCard::class,
            ConversionRateCard::class,

            // Additional Metrics (Third Row)
            TaxCollectionSummaryCard::class,
            ProductReviewsSummaryCard::class,

            // Detailed Analytics (Full Width)
            ReportGeneralHtml::class,

            // Performance Charts (Two Columns Each)
            CustomerChart::class,
            OrderChart::class,
            CustomerRetentionChart::class,

            // Distribution Charts (Two Columns Each)
            ProductCategoryDistributionChart::class,
            OrderStatusDistributionChart::class,
            PaymentMethodDistributionChart::class,
            ShippingMethodUsageChart::class,

            // Data Tables (Two Columns Each)
            RecentOrdersTable::class,
            TopSellingProductsTable::class,
            TrendingProductsTable::class,
        ];

        // Filter widgets based on user preferences
        $enabledWidgets = $this->getEnabledWidgets($allWidgets);

        $event->widget->register($enabledWidgets, 'ecommerce');
    }

    protected function getEnabledWidgets(array $allWidgets): array
    {
        if (! Auth::check()) {
            return $allWidgets;
        }

        $userId = Auth::id();
        $settingKey = "ecommerce_report_widgets_user_{$userId}";

        $userPreferences = setting($settingKey, []);

        // If no preferences set, show all widgets by default
        if (empty($userPreferences)) {
            return $allWidgets;
        }

        // Filter widgets based on user preferences
        return array_filter($allWidgets, function ($widget) use ($userPreferences) {
            return in_array($widget, $userPreferences);
        });
    }
}
