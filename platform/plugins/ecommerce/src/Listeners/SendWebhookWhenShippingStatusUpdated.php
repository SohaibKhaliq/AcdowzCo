<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Events\ShippingStatusChanged;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class SendWebhookWhenShippingStatusUpdated
{
    public function handle(ShippingStatusChanged $event): void
    {
        $webhookURL = get_ecommerce_setting('shipping_status_updated_webhook_url');

        if (! $webhookURL || ! URL::isValidUrl($webhookURL) || BaseHelper::hasDemoModeEnabled()) {
            return;
        }

        try {
            $shipment = $event->shipment;
            $order = $shipment->order;

            $data = [
                'id' => $order->id,
                'status' => [
                    'value' => $order->status->getValue(),
                    'text' => $order->status->label(),
                ],
                'shipping_status' => [
                    'value' => $shipment->status->getValue(),
                    'text' => $shipment->status->label(),
                ],
                'previous_shipping_status' => ! empty($event->previousShipment['status']) ? [
                    'value' => $event->previousShipment['status'],
                    'text' => $event->previousShipment['status']->label(),
                ] : null,
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

            $data = apply_filters('ecommerce_shipping_status_updated_webhook_data', $data, $order, $shipment);

            Http::withoutVerifying()
                ->acceptJson()
                ->post($webhookURL, $data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
