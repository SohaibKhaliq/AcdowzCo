<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Events\OrderCompletedEvent;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class SendWebhookWhenOrderCompleted
{
    public function handle(OrderCompletedEvent $event): void
    {
        $webhookURL = get_ecommerce_setting('order_completed_webhook_url');

        if (! $webhookURL || ! URL::isValidUrl($webhookURL) || BaseHelper::hasDemoModeEnabled()) {
            return;
        }

        try {
            $order = $event->order;

            $data = [
                'id' => $order->id,
                'status' => [
                    'value' => $order->status->getValue(),
                    'text' => $order->status->label(),
                ],
                'shipping_status' => $order->shipment->id ? [
                    'value' => $order->shipment->status->getValue(),
                    'text' => $order->shipment->status->label(),
                ] : [],
                'payment_method' => is_plugin_active('payment') && $order->payment->id ? [
                    'value' => $order->payment->payment_channel->getValue(),
                    'text' => $order->payment->payment_channel->label(),
                ] : [],
                'payment_status' => is_plugin_active('payment') && $order->payment->id ? [
                    'value' => $order->payment->status->getValue(),
                    'text' => $order->payment->status->label(),
                ] : [],
                'customer' => [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                ],
                'sub_total' => $order->sub_total,
                'tax_amount' => $order->tax_amount,
                'shipping_method' => $order->shipping_method->getValue(),
                'shipping_option' => $order->shipping_option,
                'shipping_amount' => $order->shipping_amount,
                'amount' => $order->amount,
                'coupon_code' => $order->coupon_code,
                'discount_amount' => $order->discount_amount,
                'discount_description' => $order->discount_description,
                'note' => $order->description,
                'is_confirmed' => $order->is_confirmed,
            ];

            $data = apply_filters('ecommerce_order_completed_webhook_data', $data, $order);

            Http::withoutVerifying()
                ->acceptJson()
                ->post($webhookURL, $data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
