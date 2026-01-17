<?php

namespace Botble\Marketplace\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Models\VendorWarning;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MarketplaceApiController extends BaseApiController
{
    public function getVendorStats(Request $request): BaseHttpResponse
    {
        $stats = [
            'total_vendors' => Store::count(),
            'active_vendors' => Store::where('status', 'published')->count(),
            'pending_vendors' => Store::where('status', 'pending')->count(),
            'commission_vendors' => Store::where('agreement_type', 'commission')->count(),
            'flat_fee_vendors' => Store::where('agreement_type', 'flat_fee')->count(),
        ];

        return $this->httpResponse()->setData($stats);
    }

    public function getProductStats(Request $request): BaseHttpResponse
    {
        $stats = [
            'total_products' => Product::whereNotNull('store_id')->count(),
            'pending_approval' => Product::whereNull('approved_by')->where('status', 'draft')->count(),
            'approved_products' => Product::where('status', 'published')->whereNotNull('approved_by')->count(),
            'rejected_products' => Product::where('status', 'draft')->whereNotNull('approved_by')->count(),
        ];

        return $this->httpResponse()->setData($stats);
    }

    public function getResellerStats(Request $request): BaseHttpResponse
    {
        $stats = [
            'total_resellers' => Customer::where('is_reseller_active', true)->count(),
            'active_resellers' => Customer::where('is_reseller_active', true)
                ->whereHas('resellerClicks', function ($q) {
                    $q->where('clicked_at', '>=', now()->subDays(30));
                })->count(),
            'total_clicks' => DB::table('ec_reseller_clicks')->count(),
            'total_commissions' => DB::table('ec_reseller_orders')->sum('commission_earned'),
            'pending_payouts' => DB::table('ec_reseller_orders')
                ->where('status', 'approved')
                ->sum('commission_earned'),
        ];

        return $this->httpResponse()->setData($stats);
    }

    public function getRevenueBreakdown(Request $request): BaseHttpResponse
    {
        $period = $request->input('period', 30); // days
        $breakdown = DB::table('mp_customer_revenues')
            ->join('mp_stores', 'mp_customer_revenues.customer_id', '=', 'mp_stores.customer_id')
            ->where('mp_customer_revenues.created_at', '>=', now()->subDays($period))
            ->select(
                'mp_stores.agreement_type',
                DB::raw('SUM(mp_customer_revenues.sub_amount) as total_revenue'),
                DB::raw('SUM(mp_customer_revenues.fee) as total_fees'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('AVG(mp_customer_revenues.sub_amount) as avg_order_value')
            )
            ->groupBy('mp_stores.agreement_type')
            ->get();

        return $this->httpResponse()->setData($breakdown);
    }

    public function getTopVendors(Request $request): BaseHttpResponse
    {
        $period = $request->input('period', 30);
        $limit = $request->input('limit', 10);

        $topVendors = DB::table('mp_customer_revenues')
            ->join('mp_stores', 'mp_customer_revenues.customer_id', '=', 'mp_stores.customer_id')
            ->join('ec_customers', 'mp_stores.customer_id', '=', 'ec_customers.id')
            ->where('mp_customer_revenues.created_at', '>=', now()->subDays($period))
            ->select(
                'mp_stores.name as store_name',
                'ec_customers.name as vendor_name',
                'mp_stores.agreement_type',
                'mp_stores.agreement_value',
                DB::raw('SUM(mp_customer_revenues.sub_amount) as total_revenue'),
                DB::raw('SUM(mp_customer_revenues.fee) as total_fees'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->groupBy('mp_stores.id', 'mp_stores.name', 'ec_customers.name', 'mp_stores.agreement_type', 'mp_stores.agreement_value')
            ->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->get();

        return $this->httpResponse()->setData($topVendors);
    }

    public function getWarningsStats(Request $request): BaseHttpResponse
    {
        $stats = [
            'total_warnings' => VendorWarning::count(),
            'recent_warnings' => VendorWarning::where('created_at', '>=', now()->subDays(30))->count(),
            'high_priority_warnings' => VendorWarning::whereIn('warning_level', ['high', 'severe'])
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            'warnings_by_level' => VendorWarning::select('warning_level', DB::raw('count(*) as count'))
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('warning_level')
                ->pluck('count', 'warning_level'),
        ];

        return $this->httpResponse()->setData($stats);
    }

    public function getResellerActivity(Request $request): BaseHttpResponse
    {
        $period = $request->input('period', 30);

        $activity = DB::table('ec_reseller_clicks')
            ->join('ec_customers', 'ec_reseller_clicks.reseller_id', '=', 'ec_customers.id')
            ->where('ec_reseller_clicks.clicked_at', '>=', now()->subDays($period))
            ->select(
                DB::raw('DATE(ec_reseller_clicks.clicked_at) as date'),
                DB::raw('COUNT(*) as total_clicks'),
                DB::raw('COUNT(DISTINCT ec_reseller_clicks.reseller_id) as unique_resellers')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $this->httpResponse()->setData($activity);
    }

    public function getDashboardMetrics(Request $request): BaseHttpResponse
    {
        $period = $request->input('period', 30);

        $metrics = [
            'revenue' => [
                'current_period' => DB::table('mp_customer_revenues')
                    ->where('created_at', '>=', now()->subDays($period))
                    ->sum('sub_amount'),
                'previous_period' => DB::table('mp_customer_revenues')
                    ->whereBetween('created_at', [
                        now()->subDays($period * 2),
                        now()->subDays($period)
                    ])
                    ->sum('sub_amount'),
            ],
            'orders' => [
                'current_period' => DB::table('mp_customer_revenues')
                    ->where('created_at', '>=', now()->subDays($period))
                    ->count(),
                'previous_period' => DB::table('mp_customer_revenues')
                    ->whereBetween('created_at', [
                        now()->subDays($period * 2),
                        now()->subDays($period)
                    ])
                    ->count(),
            ],
            'vendors' => [
                'new_this_period' => Store::where('created_at', '>=', now()->subDays($period))->count(),
                'total_active' => Store::where('status', 'published')->count(),
            ],
            'products' => [
                'pending_approval' => Product::whereNull('approved_by')
                    ->where('status', 'draft')
                    ->whereNotNull('store_id')
                    ->count(),
                'approved_this_period' => Product::whereNotNull('approved_by')
                    ->where('updated_at', '>=', now()->subDays($period))
                    ->whereNotNull('store_id')
                    ->count(),
            ]
        ];

        return $this->httpResponse()->setData($metrics);
    }
}
