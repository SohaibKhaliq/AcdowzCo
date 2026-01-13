<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebhookTestController extends BaseController
{
    public function test(Request $request, BaseHttpResponse $response)
    {
        $request->validate([
            'webhook_url' => 'required|url',
            'webhook_type' => 'required|in:order_placed,order_updated,order_completed,order_cancelled,shipping_status_updated,payment_status_updated,abandoned_cart',
        ]);

        $webhookUrl = $request->input('webhook_url');
        $webhookType = $request->input('webhook_type');

        try {
            $sampleData = $this->getSampleDataForType($webhookType);

            $httpResponse = Http::timeout(10)
                ->withoutVerifying()
                ->acceptJson()
                ->post($webhookUrl, $sampleData);

            if ($httpResponse->successful()) {
                return $response
                    ->setMessage(trans('plugins/ecommerce::setting.webhook.test_success'))
                    ->setData([
                        'status_code' => $httpResponse->status(),
                        'response_body' => $httpResponse->body(),
                    ]);
            }

            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::setting.webhook.test_failed'))
                ->setData([
                    'status_code' => $httpResponse->status(),
                    'response_body' => $httpResponse->body(),
                ]);
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::setting.webhook.test_error', ['error' => $exception->getMessage()]));
        }
    }

    protected function getSampleDataForType(string $type): array
    {
        $baseData = [
            'id' => 12345,
            'customer' => [
                'id' => 1,
                'name' => 'Test Customer',
            ],
            'sub_total' => 99.99,
            'tax_amount' => 9.99,
            'shipping_method' => 'standard',
            'shipping_option' => 'Standard Shipping (5-7 days)',
            'shipping_amount' => 5.00,
            'amount' => 114.98,
            'coupon_code' => 'TEST10',
            'discount_amount' => 10.00,
            'discount_description' => '10% off discount',
            'note' => 'Test webhook delivery',
            'is_confirmed' => true,
            'test_webhook' => true,
        ];

        switch ($type) {
            case 'order_placed':
                return array_merge($baseData, [
                    'status' => [
                        'value' => 'pending',
                        'text' => 'Pending',
                    ],
                    'shipping_status' => [
                        'value' => 'not_shipped',
                        'text' => 'Not Shipped',
                    ],
                    'payment_method' => [
                        'value' => 'stripe',
                        'text' => 'Stripe',
                    ],
                    'payment_status' => [
                        'value' => 'pending',
                        'text' => 'Pending',
                    ],
                ]);

            case 'order_updated':
                return array_merge($baseData, [
                    'status' => [
                        'value' => 'processing',
                        'text' => 'Processing',
                    ],
                    'shipping_status' => [
                        'value' => 'arranging_shipment',
                        'text' => 'Arranging Shipment',
                    ],
                    'payment_method' => [
                        'value' => 'stripe',
                        'text' => 'Stripe',
                    ],
                    'payment_status' => [
                        'value' => 'paid',
                        'text' => 'Paid',
                    ],
                ]);

            case 'order_completed':
                return array_merge($baseData, [
                    'status' => [
                        'value' => 'completed',
                        'text' => 'Completed',
                    ],
                    'shipping_status' => [
                        'value' => 'delivered',
                        'text' => 'Delivered',
                    ],
                    'payment_method' => [
                        'value' => 'stripe',
                        'text' => 'Stripe',
                    ],
                    'payment_status' => [
                        'value' => 'paid',
                        'text' => 'Paid',
                    ],
                ]);

            case 'order_cancelled':
                return array_merge($baseData, [
                    'status' => [
                        'value' => 'cancelled',
                        'text' => 'Cancelled',
                    ],
                    'shipping_status' => [
                        'value' => 'cancelled',
                        'text' => 'Cancelled',
                    ],
                    'payment_method' => [
                        'value' => 'stripe',
                        'text' => 'Stripe',
                    ],
                    'payment_status' => [
                        'value' => 'refunded',
                        'text' => 'Refunded',
                    ],
                    'cancellation_reason' => 'Test cancellation',
                ]);

            case 'shipping_status_updated':
                return array_merge($baseData, [
                    'status' => [
                        'value' => 'processing',
                        'text' => 'Processing',
                    ],
                    'shipping_status' => [
                        'value' => 'delivered',
                        'text' => 'Delivered',
                    ],
                    'previous_shipping_status' => [
                        'value' => 'shipped',
                        'text' => 'Shipped',
                    ],
                    'payment_method' => [
                        'value' => 'stripe',
                        'text' => 'Stripe',
                    ],
                    'payment_status' => [
                        'value' => 'paid',
                        'text' => 'Paid',
                    ],
                ]);

            case 'payment_status_updated':
                return array_merge($baseData, [
                    'status' => [
                        'value' => 'processing',
                        'text' => 'Processing',
                    ],
                    'shipping_status' => [
                        'value' => 'arranging_shipment',
                        'text' => 'Arranging Shipment',
                    ],
                    'payment_method' => [
                        'value' => 'stripe',
                        'text' => 'Stripe',
                    ],
                    'payment_status' => [
                        'value' => 'paid',
                        'text' => 'Paid',
                    ],
                ]);

            case 'abandoned_cart':
                return [
                    'id' => 123,
                    'customer' => [
                        'id' => 1,
                        'name' => 'Test Customer',
                        'email' => 'test@example.com',
                        'phone' => '+1234567890',
                    ],
                    'cart_items' => [
                        [
                            'product_id' => 10,
                            'product_name' => 'Test Product 1',
                            'product_sku' => 'TEST-001',
                            'quantity' => 2,
                            'price' => 25.00,
                            'total' => 50.00,
                        ],
                        [
                            'product_id' => 15,
                            'product_name' => 'Test Product 2',
                            'product_sku' => 'TEST-002',
                            'quantity' => 1,
                            'price' => 75.00,
                            'total' => 75.00,
                        ],
                    ],
                    'total_amount' => 125.00,
                    'items_count' => 3,
                    'abandoned_at' => now()->toIso8601String(),
                    'hours_since_abandoned' => 2,
                    'reminders_sent' => 0,
                    'recovery_url' => url('/cart/recover?token=test123'),
                    'test_webhook' => true,
                ];

            default:
                return $baseData;
        }
    }
}
