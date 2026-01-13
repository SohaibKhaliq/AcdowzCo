<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Events\OrderCreated;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\ResellerOrder;
use Illuminate\Http\Request;

class TrackResellerOrderListener
{
    public function __construct(protected Request $request)
    {
    }

    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        
        // Check if there's a referral code in session
        $referralCode = session('referral_code');
        
        if (!$referralCode) {
            return;
        }

        // Find the reseller
        $reseller = Customer::where('reseller_id', $referralCode)
            ->where('is_reseller_active', true)
            ->first();

        if (!$reseller) {
            return;
        }

        // Don't track if customer is ordering from their own referral
        if ($order->user_id == $reseller->id) {
            return;
        }

        // Calculate commission
        $commissionRate = $reseller->reseller_commission_rate;
        $orderAmount = $order->amount;
        $commissionEarned = ($orderAmount * $commissionRate) / 100;

        // Create reseller order tracking
        ResellerOrder::create([
            'reseller_id' => $reseller->id,
            'order_id' => $order->id,
            'order_amount' => $orderAmount,
            'commission_rate' => $commissionRate,
            'commission_earned' => $commissionEarned,
            'status' => 'pending',
        ]);

        // Update reseller balance
        $reseller->increment('reseller_balance', $commissionEarned);

        // Clear referral code from session after tracking
        session()->forget('referral_code');
    }
}
