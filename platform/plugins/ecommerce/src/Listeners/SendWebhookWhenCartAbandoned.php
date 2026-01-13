<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Events\AbandonedCartReminderEvent;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class SendWebhookWhenCartAbandoned
{
    public function handle(AbandonedCartReminderEvent $event): void
    {
        $webhookURL = get_ecommerce_setting('abandoned_cart_webhook_url');

        if (! $webhookURL || ! URL::isValidUrl($webhookURL) || BaseHelper::hasDemoModeEnabled()) {
            return;
        }

        try {
            $abandonedCart = $event->abandonedCart;
            $customer = $abandonedCart->customer;

            $cartItems = [];
            foreach ($abandonedCart->cart_data as $item) {
                $cartItems[] = [
                    'product_id' => $item['id'] ?? null,
                    'product_name' => $item['name'] ?? '',
                    'product_sku' => $item['options']['sku'] ?? '',
                    'quantity' => $item['qty'] ?? 1,
                    'price' => $item['price'] ?? 0,
                    'total' => ($item['price'] ?? 0) * ($item['qty'] ?? 1),
                ];
            }

            $data = [
                'id' => $abandonedCart->id,
                'customer' => [
                    'id' => $customer?->id ?? null,
                    'name' => $abandonedCart->customer_name,
                    'email' => $abandonedCart->email,
                    'phone' => $abandonedCart->phone,
                ],
                'cart_items' => $cartItems,
                'total_amount' => $abandonedCart->total_amount,
                'items_count' => $abandonedCart->items_count,
                'abandoned_at' => $abandonedCart->abandoned_at->toIso8601String(),
                'hours_since_abandoned' => $abandonedCart->abandoned_at->diffInHours(now()),
                'reminders_sent' => $abandonedCart->reminders_sent,
                'recovery_url' => route('public.cart', ['token' => encrypt($abandonedCart->id)]),
            ];

            $data = apply_filters('ecommerce_abandoned_cart_webhook_data', $data, $abandonedCart);

            Http::withoutVerifying()
                ->acceptJson()
                ->post($webhookURL, $data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
