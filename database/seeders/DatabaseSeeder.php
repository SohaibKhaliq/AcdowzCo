<?php

namespace Database\Seeders;

use Botble\ACL\Database\Seeders\UserSeeder;
use Botble\Base\Supports\BaseSeeder;
use Botble\Contact\Database\Seeders\ContactSeeder;
use Botble\Ecommerce\Database\Seeders\CurrencySeeder;
use Botble\Ecommerce\Database\Seeders\DiscountSeeder;
use Botble\Ecommerce\Database\Seeders\ProductSpecificationSeeder;
use Botble\Ecommerce\Database\Seeders\ReviewSeeder;
use Botble\Ecommerce\Database\Seeders\ShippingSeeder;
use Botble\Ecommerce\Database\Seeders\TaxSeeder;
use Botble\Language\Database\Seeders\LanguageSeeder;

class DatabaseSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->prepareRun();

        $this->call([
            UserSeeder::class,
            LanguageSeeder::class,
            FaqSeeder::class,
            BrandSeeder::class,
            CurrencySeeder::class,
            ProductCategorySeeder::class,
            ProductCollectionSeeder::class,
            ProductLabelSeeder::class,
            ProductAttributeSeeder::class,
            ProductOptionSeeder::class,
            CustomerSeeder::class,
            TaxSeeder::class,
            ProductTagSeeder::class,
            ShippingSeeder::class,
            ProductSeeder::class,
            FlashSaleSeeder::class,
            DiscountSeeder::class,
            ReviewSeeder::class,
            StoreLocatorSeeder::class,
            MarketplaceSeeder::class,
            ContactSeeder::class,
            BlogSeeder::class,
            SimpleSliderSeeder::class,
            PageSeeder::class,
            AdsSeeder::class,
            SettingSeeder::class,
            ProductSpecificationSeeder::class,
            MenuSeeder::class,
            ThemeOptionSeeder::class,
            WidgetSeeder::class,
        ]);

        $this->finished();
    }
}
