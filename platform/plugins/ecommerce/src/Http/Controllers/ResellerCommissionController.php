<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\ResellerOrder;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Services\ResellerService;
use Illuminate\Http\Request;

class ResellerCommissionController extends BaseController
{
    public function __construct(protected ResellerService $resellerService)
    {
    }

    public function index(Request $request)
    {
        $query = ResellerOrder::query()
            ->with(['reseller', 'order'])
            ->when($request->input('reseller_id'), function ($q, $resellerId) {
                return $q->where('reseller_id', $resellerId);
            })
            ->when($request->input('status'), function ($q, $status) {
                return $q->where('status', $status);
            });

        $commissions = $query->latest()->paginate(50);
        
        $resellers = Customer::where('is_reseller_active', true)
            ->pluck('name', 'id');

        $stats = [
            'total_pending' => ResellerOrder::where('status', 'pending')->sum('commission_earned'),
            'total_approved' => ResellerOrder::where('status', 'approved')->sum('commission_earned'),
            'total_paid' => ResellerOrder::where('status', 'paid')->sum('commission_earned'),
        ];

        return view('plugins/ecommerce::reseller-commissions.index', compact('commissions', 'resellers', 'stats'));
    }

    public function approve(int $id): BaseHttpResponse
    {
        $success = $this->resellerService->approveResellerCommission($id);

        if (!$success) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Unable to approve commission'));
        }

        return $this
            ->httpResponse()
            ->setMessage(__('Commission approved successfully'));
    }

    public function pay(int $id): BaseHttpResponse
    {
        $success = $this->resellerService->payResellerCommission($id);

        if (!$success) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Unable to mark commission as paid'));
        }

        return $this
            ->httpResponse()
            ->setMessage(__('Commission marked as paid'));
    }

    public function bulkApprove(Request $request): BaseHttpResponse
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('No commissions selected'));
        }

        $count = $this->resellerService->bulkApproveCommissions($ids);

        return $this
            ->httpResponse()
            ->setMessage(__(':count commissions approved', ['count' => $count]));
    }

    public function bulkPay(Request $request): BaseHttpResponse
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('No commissions selected'));
        }

        $count = $this->resellerService->bulkPayCommissions($ids);

        return $this
            ->httpResponse()
            ->setMessage(__(':count commissions paid', ['count' => $count]));
    }

    public function resellerDetails(int $resellerId)
    {
        $reseller = Customer::findOrFail($resellerId);
        
        if (!$reseller->is_reseller_active) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('This customer is not an active reseller'));
        }

        $stats = $this->resellerService->getResellerStats($reseller);
        $conversionRate = $this->resellerService->calculateConversionRate($reseller);
        $topProducts = $this->resellerService->getTopPerformingProducts($reseller);

        $recentOrders = $reseller->resellerOrders()
            ->with('order')
            ->latest()
            ->limit(20)
            ->get();

        return view('plugins/ecommerce::reseller-commissions.details', compact(
            'reseller',
            'stats',
            'conversionRate',
            'topProducts',
            'recentOrders'
        ));
    }
}
