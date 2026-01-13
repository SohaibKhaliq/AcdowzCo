<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Database\Traits\HasBlogSeeder;
use Botble\Ecommerce\Database\Seeders\Traits\HasEcommerceSettingsSeeder;

class SettingSeeder extends BaseSeeder
{
    use HasBlogSeeder;
    use HasEcommerceSettingsSeeder;

    public function run(): void
    {
        $settings = [
            'admin_favicon' => 'general/favicon.png',
            'admin_logo' => 'general/logo-light.png',
            'language_switcher_display' => 'dropdown',
        ];

        $this->saveSettings($settings);

        $this->saveEcommerceSettings();

        $this->setPostSlugPrefix();
    }
}
