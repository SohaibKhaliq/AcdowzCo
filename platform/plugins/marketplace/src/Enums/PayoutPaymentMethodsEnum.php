<?php

namespace Botble\Marketplace\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rule;

/**
 * @method static PayoutPaymentMethodsEnum BANK_TRANSFER()
 * @method static PayoutPaymentMethodsEnum PAYPAL()
 * @method static PayoutPaymentMethodsEnum CASH()
 */
class PayoutPaymentMethodsEnum extends Enum
{
    public const BANK_TRANSFER = 'bank_transfer';

    public const PAYPAL = 'paypal';

    public const CASH = 'cash';

    public static $langPath = 'plugins/marketplace::marketplace.payout_payment_methods';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::BANK_TRANSFER => 'info',
            self::CASH => 'success',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }

    public static function payoutMethodsEnabled(): array
    {
        return Arr::where(static::payoutMethods(), fn ($item) => $item['is_enabled']);
    }

    public static function payoutMethods(): array
    {
        $data = [
            self::BANK_TRANSFER => [
                'is_enabled' => (bool) Arr::get(MarketplaceHelper::getSetting('payout_methods'), self::BANK_TRANSFER, true),
                'key' => self::BANK_TRANSFER,
                'label' => self::BANK_TRANSFER()->label(),
                'fields' => [
                    'name' => [
                        'title' => __('Bank Name'),
                        'rules' => 'max:120',
                    ],
                    'code' => [
                        'title' => __('Bank Code/IFSC'),
                        'rules' => 'max:120',
                    ],
                    'full_name' => [
                        'title' => __('Account Holder Name'),
                        'rules' => 'max:120',
                    ],
                    'number' => [
                        'title' => __('Account Number'),
                        'rules' => 'max:50',
                    ],
                    'upi_id' => [
                        'title' => __('UPI ID'),
                        'rules' => 'max:120',
                        'helper_text' => __('It is optional. If you have UPI ID, you can provide it here. Learn more: https://support.google.com/pay/india/answer/10331134?hl=en'),
                    ],
                    'description' => [
                        'title' => __('Description'),
                        'rules' => 'max:500',
                    ],
                ],
            ],
            self::PAYPAL => [
                'is_enabled' => (bool) Arr::get(MarketplaceHelper::getSetting('payout_methods'), self::PAYPAL, true),
                'key' => self::PAYPAL,
                'label' => self::PAYPAL()->label(),
                'fields' => [
                    'paypal_id' => [
                        'title' => __('PayPal ID'),
                        'rules' => 'max:120',
                    ],
                ],
            ],
            self::CASH => [
                'is_enabled' => (bool) Arr::get(MarketplaceHelper::getSetting('payout_methods'), self::CASH, false),
                'key' => self::CASH,
                'label' => self::CASH()->label(),
                'fields' => [
                    'pickup_location' => [
                        'title' => __('Pickup Location'),
                        'rules' => 'max:500',
                        'helper_text' => __('Where would you like to collect your cash payout?'),
                    ],
                    'contact_name' => [
                        'title' => __('Contact Name'),
                        'rules' => 'max:120',
                    ],
                    'contact_phone' => [
                        'title' => __('Contact Phone'),
                        'rules' => 'max:20',
                    ],
                ],
            ],
        ];

        return apply_filters('marketplace_payout_methods', $data);
    }

    public static function getFields(?string $channel): array
    {
        if (! $channel || ! in_array($channel, array_keys(static::payoutMethods()))) {
            $channel = self::BANK_TRANSFER;
        }

        return Arr::get(static::payoutMethods(), $channel . '.fields');
    }

    public static function getRules(?string $prefix): array
    {
        $payoutMethodsEnabled = static::payoutMethodsEnabled();
        $rules = [
            'payout_payment_method' => Rule::in(array_keys($payoutMethodsEnabled)),
        ];

        if ($prefix) {
            $prefix = rtrim($prefix, '.');
            $rules[$prefix] = 'nullable|array:' . implode(',', array_keys($payoutMethodsEnabled));
            $prefix = $prefix . '.';
        }

        foreach ($payoutMethodsEnabled as $method) {
            if (empty($method['fields'])) {
                continue;
            }
            $rules[$prefix . $method['key']] = 'array:' . implode(',', array_keys($method['fields']));
            foreach ($method['fields'] as $key => $field) {
                $rules[$prefix . $method['key'] . '.' . $key] = Arr::get($field, 'rules', 'nullable');
            }
        }

        return $rules;
    }

    public static function getAttributes(?string $prefix): array
    {
        $attributes = [];
        if ($prefix) {
            $prefix = rtrim($prefix, '.');
            $attributes[$prefix] = __('Payout info');
            $prefix = $prefix . '.';
        }

        foreach (static::payoutMethodsEnabled() as $method) {
            $attributes[$prefix . $method['key']] = $method['label'];
            foreach ($method['fields'] as $key => $field) {
                $attributes[$prefix . $method['key'] . '.' . $key] = __('Payout info') . ' (' . Arr::get($field, 'title') . ')';
            }
        }

        return $attributes;
    }
}
