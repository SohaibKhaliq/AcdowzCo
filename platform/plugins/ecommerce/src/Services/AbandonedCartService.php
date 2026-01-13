<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Models\AbandonedCart;
use Botble\Ecommerce\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AbandonedCartService
{
    public function trackCart(): void
    {
        if (Cart::count() === 0) {
            return;
        }

        $customer = Auth::guard('customer')->user();
        $sessionId = Session::getId();

        $cartData = [];
        foreach (Cart::content() as $item) {
            $cartData[] = [
                'id' => $item->id,
                'name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
                'options' => $item->options->toArray(),
            ];
        }

        $abandonedCart = AbandonedCart::updateOrCreate(
            [
                'customer_id' => $customer?->id,
                'session_id' => $customer ? null : $sessionId,
                'is_recovered' => false,
            ],
            [
                'cart_data' => $cartData,
                'total_amount' => Cart::rawTotal(),
                'items_count' => Cart::count(),
                'email' => $customer?->email ?? session('abandoned_cart_email'),
                'phone' => $customer?->phone ?? session('abandoned_cart_phone'),
                'customer_name' => $customer?->name ?? session('abandoned_cart_name'),
                'updated_at' => now(),
            ]
        );

        if (! $abandonedCart->abandoned_at) {
            $abandonedCart->update(['abandoned_at' => now()]);
        }
    }

    public function markCartAsRecovered(Order $order): void
    {
        $customer = $order->user;
        $sessionId = Session::getId();

        $abandonedCart = AbandonedCart::query()
            ->where('is_recovered', false)
            ->where(function ($query) use ($customer, $sessionId) {
                if ($customer) {
                    $query->where('customer_id', $customer->id);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->latest()
            ->first();

        if ($abandonedCart) {
            $abandonedCart->markAsRecovered($order);
        }
    }

    public function identifyAbandonedCarts(int $hoursThreshold = 1): Collection
    {
        return AbandonedCart::query()
            ->abandoned()
            ->where('abandoned_at', '<=', now()->subHours($hoursThreshold))
            ->where(function ($query) {
                $query->whereNull('reminder_sent_at')
                    ->orWhere('reminder_sent_at', '<=', now()->subHours(24));
            })
            ->where('reminders_sent', '<', 3)
            ->get();
    }

    public function cleanupOldAbandonedCarts(int $daysToKeep = 30): int
    {
        return AbandonedCart::query()
            ->where('created_at', '<=', now()->subDays($daysToKeep))
            ->where('is_recovered', false)
            ->delete();
    }

    public function getAbandonmentRate(): float
    {
        $total = AbandonedCart::count();
        if ($total === 0) {
            return 0;
        }

        $recovered = AbandonedCart::where('is_recovered', true)->count();

        return round((($total - $recovered) / $total) * 100, 2);
    }

    public function getRecoveryRate(): float
    {
        $total = AbandonedCart::count();
        if ($total === 0) {
            return 0;
        }

        $recovered = AbandonedCart::where('is_recovered', true)->count();

        return round(($recovered / $total) * 100, 2);
    }

    public function updateCustomerInfo(array $data): void
    {
        $sessionId = Session::getId();

        $abandonedCart = AbandonedCart::query()
            ->where('session_id', $sessionId)
            ->where('is_recovered', false)
            ->latest()
            ->first();

        if ($abandonedCart) {
            $abandonedCart->update([
                'email' => $data['email'] ?? $abandonedCart->email,
                'phone' => $data['phone'] ?? $abandonedCart->phone,
                'customer_name' => $data['name'] ?? $abandonedCart->customer_name,
            ]);
        }

        session([
            'abandoned_cart_email' => $data['email'] ?? null,
            'abandoned_cart_phone' => $data['phone'] ?? null,
            'abandoned_cart_name' => $data['name'] ?? null,
        ]);
    }
}
