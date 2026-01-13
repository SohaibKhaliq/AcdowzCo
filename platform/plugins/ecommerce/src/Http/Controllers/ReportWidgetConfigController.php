<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportWidgetConfigController extends BaseController
{
    public function index()
    {
        $this->pageTitle(trans('plugins/ecommerce::reports.widget_configuration'));

        $availableWidgets = $this->getAvailableWidgets();
        $userPreferences = $this->getUserWidgetPreferences();

        return view('plugins/ecommerce::reports.widget-config', compact('availableWidgets', 'userPreferences'));
    }

    public function store(Request $request): BaseHttpResponse
    {
        $request->validate([
            'widgets' => 'array',
            'widgets.*' => 'string',
        ]);

        $widgets = $request->input('widgets', []);

        // Store user preferences in settings
        $userId = Auth::id();
        $settingKey = "ecommerce_report_widgets_user_{$userId}";

        setting()->set($settingKey, $widgets)->save();

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/ecommerce::reports.widget_configuration_saved'));
    }

    public function getConfiguration(): BaseHttpResponse
    {
        $availableWidgets = $this->getAvailableWidgets();
        $userPreferences = $this->getUserWidgetPreferences();

        return $this
            ->httpResponse()
            ->setData([
                'availableWidgets' => $availableWidgets,
                'userPreferences' => $userPreferences,
            ]);
    }

    protected function getAvailableWidgets(): array
    {
        return [
            'Botble\Ecommerce\Widgets\RevenueCard' => [
                'name' => trans('plugins/ecommerce::reports.revenue'),
                'description' => trans('plugins/ecommerce::reports.revenue_description'),
                'category' => 'financial',
            ],
            'Botble\Ecommerce\Widgets\ProfitCard' => [
                'name' => trans('plugins/ecommerce::reports.profit'),
                'description' => trans('plugins/ecommerce::reports.profit_description'),
                'category' => 'financial',
            ],
            'Botble\Ecommerce\Widgets\ExpensesCard' => [
                'name' => trans('plugins/ecommerce::reports.expenses'),
                'description' => trans('plugins/ecommerce::reports.expenses_description'),
                'category' => 'financial',
            ],
            'Botble\Ecommerce\Widgets\AverageOrderValueCard' => [
                'name' => trans('plugins/ecommerce::reports.average_order_value'),
                'description' => trans('plugins/ecommerce::reports.average_order_value_description'),
                'category' => 'financial',
            ],
            'Botble\Ecommerce\Widgets\NewOrderCard' => [
                'name' => trans('plugins/ecommerce::reports.orders'),
                'description' => trans('plugins/ecommerce::reports.new_orders_description'),
                'category' => 'activity',
            ],
            'Botble\Ecommerce\Widgets\NewCustomerCard' => [
                'name' => trans('plugins/ecommerce::reports.customers'),
                'description' => trans('plugins/ecommerce::reports.new_customers_description'),
                'category' => 'activity',
            ],
            'Botble\Ecommerce\Widgets\NewProductCard' => [
                'name' => trans('plugins/ecommerce::reports.products'),
                'description' => trans('plugins/ecommerce::reports.new_products_description'),
                'category' => 'activity',
            ],
            'Botble\Ecommerce\Widgets\ConversionRateCard' => [
                'name' => trans('plugins/ecommerce::reports.conversion_rate'),
                'description' => trans('plugins/ecommerce::reports.conversion_rate_description'),
                'category' => 'activity',
            ],
            'Botble\Ecommerce\Widgets\TaxCollectionSummaryCard' => [
                'name' => trans('plugins/ecommerce::reports.tax_collection'),
                'description' => trans('plugins/ecommerce::reports.tax_collection_description'),
                'category' => 'additional',
            ],
            'Botble\Ecommerce\Widgets\ProductReviewsSummaryCard' => [
                'name' => trans('plugins/ecommerce::reports.product_reviews'),
                'description' => trans('plugins/ecommerce::reports.product_reviews_description'),
                'category' => 'additional',
            ],
            'Botble\Ecommerce\Widgets\ReportGeneralHtml' => [
                'name' => trans('plugins/ecommerce::reports.detailed_analytics'),
                'description' => trans('plugins/ecommerce::reports.detailed_analytics_description'),
                'category' => 'analytics',
            ],
            'Botble\Ecommerce\Widgets\CustomerChart' => [
                'name' => trans('plugins/ecommerce::reports.customers_chart'),
                'description' => trans('plugins/ecommerce::reports.customers_chart_description'),
                'category' => 'charts',
            ],
            'Botble\Ecommerce\Widgets\OrderChart' => [
                'name' => trans('plugins/ecommerce::reports.orders_chart'),
                'description' => trans('plugins/ecommerce::reports.orders_chart_description'),
                'category' => 'charts',
            ],
            'Botble\Ecommerce\Widgets\CustomerRetentionChart' => [
                'name' => trans('plugins/ecommerce::reports.customer_retention'),
                'description' => trans('plugins/ecommerce::reports.customer_retention_description'),
                'category' => 'charts',
            ],
            'Botble\Ecommerce\Widgets\ProductCategoryDistributionChart' => [
                'name' => trans('plugins/ecommerce::reports.product_category_distribution'),
                'description' => trans('plugins/ecommerce::reports.product_category_distribution_description'),
                'category' => 'distribution',
            ],
            'Botble\Ecommerce\Widgets\OrderStatusDistributionChart' => [
                'name' => trans('plugins/ecommerce::reports.order_status_distribution'),
                'description' => trans('plugins/ecommerce::reports.order_status_distribution_description'),
                'category' => 'distribution',
            ],
            'Botble\Ecommerce\Widgets\PaymentMethodDistributionChart' => [
                'name' => trans('plugins/ecommerce::reports.payment_method_distribution'),
                'description' => trans('plugins/ecommerce::reports.payment_method_distribution_description'),
                'category' => 'distribution',
            ],
            'Botble\Ecommerce\Widgets\ShippingMethodUsageChart' => [
                'name' => trans('plugins/ecommerce::reports.shipping_method_usage'),
                'description' => trans('plugins/ecommerce::reports.shipping_method_usage_description'),
                'category' => 'distribution',
            ],
            'Botble\Ecommerce\Widgets\RecentOrdersTable' => [
                'name' => trans('plugins/ecommerce::reports.recent_orders'),
                'description' => trans('plugins/ecommerce::reports.recent_orders_description'),
                'category' => 'tables',
            ],
            'Botble\Ecommerce\Widgets\TopSellingProductsTable' => [
                'name' => trans('plugins/ecommerce::reports.top_selling_products'),
                'description' => trans('plugins/ecommerce::reports.top_selling_products_description'),
                'category' => 'tables',
            ],
            'Botble\Ecommerce\Widgets\TrendingProductsTable' => [
                'name' => trans('plugins/ecommerce::reports.trending_products'),
                'description' => trans('plugins/ecommerce::reports.trending_products_description'),
                'category' => 'tables',
            ],
        ];
    }

    protected function getUserWidgetPreferences(): array
    {
        $userId = Auth::id();
        $settingKey = "ecommerce_report_widgets_user_{$userId}";

        $preferences = setting($settingKey, []);

        // If no preferences set, show all widgets by default
        if (empty($preferences)) {
            $preferences = array_keys($this->getAvailableWidgets());
        }

        return $preferences;
    }
}
