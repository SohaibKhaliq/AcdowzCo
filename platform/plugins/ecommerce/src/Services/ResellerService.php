<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\ResellerOrder;
use Carbon\Carbon;

class ResellerService
{
    public function getResellerStats(Customer $reseller, int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);

        return [
            'total_clicks' => $reseller->resellerClicks()
                ->where('clicked_at', '>=', $startDate)
                ->count(),
            
            'total_orders' => $reseller->resellerOrders()
                ->where('created_at', '>=', $startDate)
                ->count(),
            
            'pending_commission' => $reseller->resellerOrders()
                ->where('status', 'pending')
                ->sum('commission_earned'),
            
            'approved_commission' => $reseller->resellerOrders()
                ->where('status', 'approved')
                ->sum('commission_earned'),
            
            'paid_commission' => $reseller->resellerOrders()
                ->where('status', 'paid')
                ->sum('commission_earned'),
            
            'lifetime_clicks' => $reseller->resellerClicks()->count(),
            'lifetime_orders' => $reseller->resellerOrders()->count(),
            'lifetime_earnings' => $reseller->resellerOrders()
                ->where('status', 'paid')
                ->sum('commission_earned'),
        ];
    }

    public function approveResellerCommission(int $orderId): bool
    {
        $resellerOrder = ResellerOrder::find($orderId);
        
        if (!$resellerOrder || $resellerOrder->status !== 'pending') {
            return false;
        }

        return $resellerOrder->approve();
    }

    public function payResellerCommission(int $orderId): bool
    {
        $resellerOrder = ResellerOrder::find($orderId);
        
        if (!$resellerOrder || $resellerOrder->status !== 'approved') {
            return false;
        }

        return $resellerOrder->markAsPaid();
    }

    public function bulkApproveCommissions(array $orderIds): int
    {
        return ResellerOrder::whereIn('id', $orderIds)
            ->where('status', 'pending')
            ->update(['status' => 'approved']);
    }

    public function bulkPayCommissions(array $orderIds): int
    {
        return ResellerOrder::whereIn('id', $orderIds)
            ->where('status', 'approved')
            ->update(['status' => 'paid']);
    }

    public function calculateConversionRate(Customer $reseller): float
    {
        $totalClicks = $reseller->resellerClicks()->count();
        $totalOrders = $reseller->resellerOrders()->count();

        if ($totalClicks === 0) {
            return 0;
        }

        return ($totalOrders / $totalClicks) * 100;
    }

    public function getTopPerformingProducts(Customer $reseller, int $limit = 10): array
    {
        return $reseller->resellerClicks()
            ->whereNotNull('product_id')
            ->selectRaw('product_id, COUNT(*) as clicks')
            ->groupBy('product_id')
            ->orderByDesc('clicks')
            ->limit($limit)
            ->with('product')
            ->get()
            ->toArray();
    }
}
