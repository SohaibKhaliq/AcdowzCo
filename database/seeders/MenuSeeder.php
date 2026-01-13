<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Menu\Database\Traits\HasMenuSeeder;
use Botble\Page\Models\Page;

class MenuSeeder extends BaseSeeder
{
    use HasMenuSeeder;

    public function run(): void
    {
        $data = [
            [
                'name' => 'Main menu',
                'slug' => 'main-menu',
                'location' => 'main-menu',
                'items' => [
                    [
                        'title' => 'Special Prices',
                        'url' => '/products/smart-watches',
                        'icon_font' => 'icon icon-tag',
                    ],
                    [
                        'title' => 'Pages',
                        'url' => '#',
                        'children' => [
                            [
                                'title' => 'About us',
                                'reference_id' => 2,
                                'reference_type' => Page::class,
                            ],
                            [
                                'title' => 'Terms Of Use',
                                'reference_id' => 3,
                                'reference_type' => Page::class,
                            ],
                            [
                                'title' => 'Terms & Conditions',
                                'reference_id' => 4,
                                'reference_type' => Page::class,
                            ],
                            [
                                'title' => 'Refund Policy',
                                'reference_id' => 5,
                                'reference_type' => Page::class,
                            ],
                            [
                                'title' => 'Coming soon',
                                'reference_id' => 12,
                                'reference_type' => Page::class,
                            ],
                        ],
                    ],
                    [
                        'title' => 'Shop',
                        'url' => '/products',
                        'children' => [
                            [
                                'title' => 'All products',
                                'url' => '/products',
                            ],
                            [
                                'title' => 'Products Of Category',
                                'reference_id' => 15,
                                'reference_type' => ProductCategory::class,
                            ],
                            [
                                'title' => 'Product Single',
                                'url' => '/products/beat-headphone',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Stores',
                        'url' => '/stores',
                    ],
                    [
                        'title' => 'Blog',
                        'reference_id' => 6,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'FAQs',
                        'reference_id' => 7,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Contact',
                        'reference_id' => 8,
                        'reference_type' => Page::class,
                    ],
                ],
            ],
            [
                'name' => 'Header menu',
                'slug' => 'header-menu',
                'location' => 'header-navigation',
                'items' => [
                    [
                        'title' => 'About Us',
                        'reference_id' => 2,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Wishlist',
                        'url' => 'wishlist',
                    ],
                    [
                        'title' => 'Order Tracking',
                        'url' => 'orders/tracking',
                    ],
                ],
            ],
            [
                'name' => 'Useful Links',
                'slug' => 'useful-links',
                'items' => [
                    [
                        'title' => 'Terms Of Use',
                        'reference_id' => 3,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Terms & Conditions',
                        'reference_id' => 4,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Refund Policy',
                        'reference_id' => 5,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'FAQs',
                        'reference_id' => 7,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => '404 Page',
                        'url' => '/nothing',
                    ],
                ],
            ],
            [
                'name' => 'Help Center',
                'slug' => 'help-center',
                'items' => [
                    [
                        'title' => 'About us',
                        'reference_id' => 2,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Affiliate',
                        'reference_id' => 10,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Career',
                        'reference_id' => 11,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Contact us',
                        'reference_id' => 8,
                        'reference_type' => Page::class,
                    ],
                ],
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'items' => [
                    [
                        'title' => 'Our blog',
                        'reference_id' => 6,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Cart',
                        'url' => '/cart',
                    ],
                    [
                        'title' => 'My account',
                        'url' => '/customer/overview',
                    ],
                    [
                        'title' => 'Shop',
                        'url' => '/products',
                    ],
                ],
            ],
        ];

        $this->createMenus($data);
    }
}
