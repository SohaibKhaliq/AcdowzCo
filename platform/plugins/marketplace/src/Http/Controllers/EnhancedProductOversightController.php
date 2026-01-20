<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\Product;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Models\VendorWarning;
use Botble\Marketplace\Enums\StoreStatusEnum;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EnhancedProductOversightController extends BaseController
{
    public function index(Request $request)
    {
        PageTitle::setTitle(__('Product Oversight Dashboard'));

        // Get filter parameters
        $vendorFilter = $request->input('vendor_id');
        $statusFilter = $request->input('status');
        $categoryFilter = $request->input('category_id');
        $agreementFilter = $request->input('agreement_type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Build query for products
        $productsQuery = Product::query()
            ->with(['store', 'store.customer', 'approvedBy', 'categories'])
            ->whereNotNull('store_id')
            ->when($vendorFilter, fn($q) => $q->where('store_id', $vendorFilter))
            ->when($statusFilter, fn($q) => $q->where('status', $statusFilter))
            ->when($categoryFilter, fn($q) => $q->whereHas('categories', fn($subQ) => $subQ->where('ec_product_categories.id', $categoryFilter)))
            ->when($agreementFilter, fn($q) => $q->whereHas('store', fn($subQ) => $subQ->where('agreement_type', $agreementFilter)))
            ->when($dateFrom, fn($q) => $q->where('created_at', '>=', Carbon::parse($dateFrom)))
            ->when($dateTo, fn($q) => $q->where('created_at', '<=', Carbon::parse($dateTo)->endOfDay()));

        $products = $productsQuery->orderBy('created_at', 'desc')->paginate(20);

        // Get summary statistics
        $stats = $this->getOverviewStats($vendorFilter, $statusFilter, $agreementFilter);

        // Get filters data
        $vendors = Store::with('customer')->get()->pluck('name', 'id');
        $categories = \Botble\Ecommerce\Models\ProductCategory::pluck('name', 'id');

        return view('plugins/marketplace::product-oversight.index', compact(
            'products',
            'stats',
            'vendors',
            'categories',
            'vendorFilter',
            'statusFilter',
            'categoryFilter',
            'agreementFilter',
            'dateFrom',
            'dateTo'
        ));
    }

    public function bulkApprove(Request $request): BaseHttpResponse
    {
        $productIds = $request->input('product_ids', []);

        if (empty($productIds)) {
            return $this->httpResponse()
                ->setError()
                ->setMessage(__('No products selected'));
        }

        $updated = Product::whereIn('id', $productIds)->get();

        foreach ($updated as $product) {
            $product->update([
                'status' => 'published',
                'approved_by' => auth()->id(),
            ]);
        }

        $count = $updated->count();

        return $this->httpResponse()
            ->setMessage(__(':count products approved successfully', ['count' => $count]));
    }

    public function bulkReject(Request $request): BaseHttpResponse
    {
        $productIds = $request->input('product_ids', []);

        if (empty($productIds)) {
            return $this->httpResponse()
                ->setError()
                ->setMessage(__('No products selected'));
        }

        $count = Product::whereIn('id', $productIds)
            ->update(['status' => 'draft']);

        return $this->httpResponse()
            ->setMessage(__(':count products rejected successfully', ['count' => $count]));
    }

    public function bulkDelete(Request $request): BaseHttpResponse
    {
        $productIds = $request->input('product_ids', []);

        if (empty($productIds)) {
            return $this->httpResponse()
                ->setError()
                ->setMessage(__('No products selected'));
        }

        $count = Product::whereIn('id', $productIds)->count();
        Product::whereIn('id', $productIds)->delete();

        return $this->httpResponse()
            ->setMessage(__(':count products deleted successfully', ['count' => $count]));
    }

    public function issueWarning(Request $request): BaseHttpResponse
    {
        $request->validate([
            'store_id' => 'required|exists:mp_stores,id',
            'warning_level' => 'required|in:low,medium,high,severe',
            'warning_text' => 'required|string|max:2000',
            'send_email' => 'boolean',
        ]);

        $store = Store::findOrFail($request->input('store_id'));

        $warning = VendorWarning::create([
            'store_id' => $store->id,
            'warning_level' => $request->input('warning_level'),
            'warning_text' => $request->input('warning_text'),
            'issued_by' => auth()->id(),
            'expires_at' => now()->addDays(30),
        ]);

        // Send email notification if requested
        if ($request->input('send_email')) {
            $this->sendWarningEmail($store, $warning);
        }

        return $this->httpResponse()
            ->setMessage(__('Warning issued successfully to :vendor', ['vendor' => $store->name]));
    }

    public function getRevenueBreakdown(Request $request): BaseHttpResponse
    {
        $vendorId = $request->input('vendor_id');
        $period = $request->input('period', '30'); // days

        $query = DB::table('mp_customer_revenues')
            ->join('mp_stores', 'mp_customer_revenues.customer_id', '=', 'mp_stores.customer_id')
            ->when($vendorId, fn($q) => $q->where('mp_stores.id', $vendorId))
            ->where('mp_customer_revenues.created_at', '>=', now()->subDays($period))
            ->select(
                DB::raw('DATE(mp_customer_revenues.created_at) as date'),
                'mp_stores.agreement_type',
                DB::raw('SUM(mp_customer_revenues.sub_amount) as revenue'),
                DB::raw('SUM(mp_customer_revenues.fee) as fees'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date', 'mp_stores.agreement_type')
            ->orderBy('date')
            ->get();

        return $this->httpResponse()->setData($query);
    }

    protected function getOverviewStats(?string $vendorFilter = null, ?string $statusFilter = null, ?string $agreementFilter = null): array
    {
        $baseQuery = Product::query()->whereNotNull('store_id');

        if ($vendorFilter) {
            $baseQuery->where('store_id', $vendorFilter);
        }
        if ($agreementFilter) {
            $baseQuery->whereHas('store', fn($q) => $q->where('agreement_type', $agreementFilter));
        }

        return [
            'total_products' => $baseQuery->count(),
            'pending_approval' => $baseQuery->clone()->where('status', 'draft')->whereNull('approved_by')->count(),
            'approved_products' => $baseQuery->clone()->where('status', 'published')->count(),
            'rejected_products' => $baseQuery->clone()->where('status', 'draft')->whereNotNull('approved_by')->count(),

            'commission_vendors' => Store::where('agreement_type', 'commission')->count(),
            'flat_fee_vendors' => Store::where('agreement_type', 'flat_fee')->count(),
            'active_vendors' => Store::where('status', StoreStatusEnum::PUBLISHED)->count(),
            'pending_vendors' => Store::where('status', StoreStatusEnum::PENDING)->count(),

            'recent_warnings' => VendorWarning::where('created_at', '>=', now()->subDays(30))->count(),
            'high_severity_warnings' => VendorWarning::where('warning_level', 'high')
                ->orWhere('warning_level', 'severe')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),

            'revenue_last_30_days' => DB::table('mp_customer_revenues')
                ->where('created_at', '>=', now()->subDays(30))
                ->sum('sub_amount'),

            'fees_collected_last_30_days' => DB::table('mp_customer_revenues')
                ->where('created_at', '>=', now()->subDays(30))
                ->sum('fee'),
        ];
    }

    protected function sendWarningEmail(Store $store, VendorWarning $warning): void
    {
        // Implementation for sending warning email
        // This would integrate with your email system
        try {
            \Illuminate\Support\Facades\Mail::send(
                'plugins.marketplace::emails.vendor-warning',
                [
                    'store' => $store,
                    'warning' => $warning,
                ],
                function ($message) use ($store) {
                    $message->to($store->email, $store->name)
                        ->subject(__('Important: Warning Notice for Your Store'));
                }
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send warning email: ' . $e->getMessage());
        }
    }
}
