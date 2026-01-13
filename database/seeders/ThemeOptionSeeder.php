<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Page\Models\Page;
use Botble\Theme\Database\Traits\HasThemeOptionSeeder;

class ThemeOptionSeeder extends BaseSeeder
{
    use HasThemeOptionSeeder;

    public function run(): void
    {
        $this->uploadFiles('general');

        $this->createThemeOptions([
            'site_title' => 'Farmart - Laravel Ecommerce system',
            'seo_description' => 'Farmart is a modern and flexible Multipurpose Marketplace Laravel script. This script is suited for electronic, organic and grocery store, furniture store, clothing store, hitech store and accessories store… With the theme, you can create your own marketplace and allow vendors to sell just like Amazon, Envato, eBay…',
            'copyright' => '© %Y Farmart. All Rights Reserved.',
            'favicon' => 'general/favicon.png',
            'logo' => 'general/logo.png',
            'seo_og_image' => 'general/open-graph-image.png',
            'image-placeholder' => 'general/placeholder.png',
            'address' => '502 New Street, Brighton VIC, Australia',
            'hotline' => '8 800 332 65-66',
            'email' => 'contact@fartmart.co',
            'working_time' => 'Mon - Fri: 07AM - 06PM',
            'payment_methods_image' => 'general/footer-payments.png',
            'homepage_id' => Page::query()->value('id'),
            'blog_page_id' => Page::query()->skip(5)->value('id'),
            'cookie_consent_message' => 'Your experience on this site will be improved by allowing cookies ',
            'cookie_consent_learn_more_url' => '/cookie-policy',
            'cookie_consent_learn_more_text' => 'Cookie Policy',
            'number_of_products_per_page' => 40,
            'number_of_cross_sale_product' => 6,
            'logo_in_the_checkout_page' => 'general/logo.png',
            'logo_in_invoices' => 'general/logo.png',
            'logo_vendor_dashboard' => 'general/logo.png',
            '404_page_image' => 'general/404.png',
            'social_links' => [
                [
                    ['key' => 'name', 'value' => 'Facebook'],
                    ['key' => 'icon', 'value' => 'ti ti-brand-facebook'],
                    ['key' => 'url', 'value' => 'https://www.facebook.com'],
                    ['key' => 'icon_image', 'value' => null],
                    ['key' => 'color', 'value' => '#fff'],
                    ['key' => 'background-color', 'value' => '#3b5999'],
                ],
                [
                    ['key' => 'name', 'value' => 'X (Twitter)'],
                    ['key' => 'icon', 'value' => 'ti ti-brand-x'],
                    ['key' => 'url', 'value' => 'https://x.com'],
                    ['key' => 'icon_image', 'value' => null],
                    ['key' => 'color', 'value' => '#fff'],
                    ['key' => 'background-color', 'value' => '#000'],
                ],
                [
                    ['key' => 'name', 'value' => 'linkedin'],
                    ['key' => 'icon', 'value' => 'ti ti-brand-linkedin'],
                    ['key' => 'url', 'value' => 'https://www.linkedin.com'],
                    ['key' => 'icon_image', 'value' => null],
                    ['key' => 'color', 'value' => '#fff'],
                    ['key' => 'background-color', 'value' => '#0a66c2'],
                ],
            ],
            'social_sharing' => [
                [
                    ['key' => 'name', 'value' => 'Facebook'],
                    ['key' => 'social', 'value' => 'facebook'],
                    ['key' => 'icon', 'value' => 'ti ti-brand-facebook'],
                    ['key' => 'icon_image', 'value' => null],
                    ['key' => 'color', 'value' => '#fff'],
                    ['key' => 'background_color', 'value' => '#3b5999'],
                ],
                [
                    ['key' => 'name', 'value' => 'X (Twitter)'],
                    ['key' => 'social', 'value' => 'x'],
                    ['key' => 'icon', 'value' => 'ti ti-brand-x'],
                    ['key' => 'icon_image', 'value' => null],
                    ['key' => 'color', 'value' => '#fff'],
                    ['key' => 'background_color', 'value' => '#55acee'],
                ],
                [
                    ['key' => 'name', 'value' => 'Pinterest'],
                    ['key' => 'social', 'value' => 'pinterest'],
                    ['key' => 'icon', 'value' => 'ti ti-brand-pinterest'],
                    ['key' => 'icon_image', 'value' => null],
                    ['key' => 'color', 'value' => '#fff'],
                    ['key' => 'background_color', 'value' => '#b10c0c'],
                ],
                [
                    ['key' => 'name', 'value' => 'Linkedin'],
                    ['key' => 'social', 'value' => 'linkedin'],
                    ['key' => 'icon', 'value' => 'ti ti-brand-linkedin'],
                    ['key' => 'icon_image', 'value' => null],
                    ['key' => 'color', 'value' => '#fff'],
                    ['key' => 'background_color', 'value' => '#0271ae'],
                ],
                [
                    ['key' => 'name', 'value' => 'Whatsapp'],
                    ['key' => 'social', 'value' => 'whatsapp'],
                    ['key' => 'icon', 'value' => 'ti ti-brand-whatsapp'],
                    ['key' => 'icon_image', 'value' => null],
                    ['key' => 'color', 'value' => '#fff'],
                    ['key' => 'background_color', 'value' => '#25d366'],
                ],
            ],
            'primary_font' => 'Mulish',
            'newsletter_popup_enable' => true,
            'newsletter_popup_image' => $this->filePath('general/newsletter-popup.png'),
            'newsletter_popup_title' => 'Subscribe Now',
            'newsletter_popup_subtitle' => 'Newsletter',
            'newsletter_popup_description' => 'Subscribe to our newsletter and get 10% off your first purchase',
        ]);
    }
}
