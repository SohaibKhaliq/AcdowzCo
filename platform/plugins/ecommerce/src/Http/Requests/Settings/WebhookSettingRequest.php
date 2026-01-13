<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Support\Http\Requests\Request;

class WebhookSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'order_placed_webhook_url' => ['nullable', 'url'],
            'order_updated_webhook_url' => ['nullable', 'url'],
            'shipping_status_updated_webhook_url' => ['nullable', 'url'],
            'order_completed_webhook_url' => ['nullable', 'url'],
            'order_cancelled_webhook_url' => ['nullable', 'url'],
            'payment_status_updated_webhook_url' => ['nullable', 'url'],
            'abandoned_cart_webhook_url' => ['nullable', 'url'],
        ];
    }
}
