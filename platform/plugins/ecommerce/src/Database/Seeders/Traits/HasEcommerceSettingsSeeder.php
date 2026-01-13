<?php

namespace Botble\Ecommerce\Database\Seeders\Traits;

use Botble\Media\Facades\RvMedia;
use Botble\Setting\Facades\Setting;
use Illuminate\Support\Facades\File;

trait HasEcommerceSettingsSeeder
{
    protected function saveEcommerceSettings(array $data = []): void
    {
        $settings = [
            'payment_cod_status' => true,
            'payment_cod_description' => 'Please pay money directly to the postman, if you choose cash on delivery method (COD).',
            'payment_bank_transfer_status' => true,
            'payment_bank_transfer_description' => 'Please send money to our bank account: ACB - 69270 213 19.',
            'payment_stripe_payment_type' => 'stripe_checkout',
            'plugins_ecommerce_customer_new_order_status' => false,
            'plugins_ecommerce_admin_new_order_status' => false,
            'ecommerce_is_enabled_support_digital_products' => true,
            'ecommerce_enable_license_codes_for_digital_products' => true,
            'ecommerce_auto_complete_digital_orders_after_payment' => true,
            'ecommerce_load_countries_states_cities_from_location_plugin' => false,
            'ecommerce_product_sku_format' => 'SF-2443-%s%s%s%s',
            'ecommerce_store_order_prefix' => 'SF',
            'ecommerce_enable_product_specification' => true,
            'payment_bank_transfer_display_bank_info_at_the_checkout_success_page' => true,
            ...$data,
        ];

        $files = [
            'cod' => plugin_path('payment/public/images/cod.png'),
            'bank_transfer' => plugin_path('payment/public/images/bank-transfer.png'),
            'stripe' => plugin_path('stripe/public/images/stripe.webp'),
            'paypal' => plugin_path('paypal/public/images/paypal.png'),
            'mollie' => plugin_path('mollie/public/images/mollie.png'),
            'paystack' => plugin_path('paystack/public/images/paystack.png'),
            'razorpay' => plugin_path('razorpay/public/images/razorpay.png'),
            'sslcommerz' => plugin_path('sslcommerz/public/images/sslcommerz.png'),
        ];

        foreach ($files as $key => $file) {
            if (! File::exists($file)) {
                continue;
            }

            RvMedia::uploadFromPath($file, 0, 'payments');

            $settings['payment_' . $key . '_logo'] = 'payments/' . pathinfo($file, PATHINFO_BASENAME);
        }

        Setting::delete(array_keys($settings));

        Setting::set($settings)->save();
    }
}
