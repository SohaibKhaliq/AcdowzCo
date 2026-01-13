<?php

namespace Database\Seeders;

use Botble\Ecommerce\Database\Seeders\ProductOptionSeeder as BaseProductOptionSeeder;
use Botble\Ecommerce\Option\OptionType\Dropdown;
use Botble\Ecommerce\Option\OptionType\RadioButton;

class ProductOptionSeeder extends BaseProductOptionSeeder
{
    public function run(): void
    {
        $options = [
            [
                'name' => 'Warranty',
                'option_type' => RadioButton::class,
                'required' => true,
                'values' => [
                    [
                        'option_value' => '1 Year',
                        'affect_price' => 0,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => '2 Year',
                        'affect_price' => 10,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => '3 Year',
                        'affect_price' => 20,
                        'affect_type' => 0,
                    ],
                ],
            ],
            [
                'name' => 'RAM',
                'option_type' => RadioButton::class,
                'required' => true,
                'values' => [
                    [
                        'option_value' => '4GB',
                        'affect_price' => 0,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => '8GB',
                        'affect_price' => 10,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => '16GB',
                        'affect_price' => 20,
                        'affect_type' => 0,
                    ],
                ],
            ],
            [
                'name' => 'CPU',
                'option_type' => RadioButton::class,
                'required' => true,
                'values' => [
                    [
                        'option_value' => 'Core i5',
                        'affect_price' => 0,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => 'Core i7',
                        'affect_price' => 10,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => 'Core i9',
                        'affect_price' => 20,
                        'affect_type' => 0,
                    ],
                ],
            ],
            [
                'name' => 'HDD',
                'option_type' => Dropdown::class,
                'required' => false,
                'values' => [
                    [
                        'option_value' => '128GB',
                        'affect_price' => 0,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => '256GB',
                        'affect_price' => 10,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => '512GB',
                        'affect_price' => 20,
                        'affect_type' => 0,
                    ],
                ],
            ],
        ];

        $this->saveGlobalOption($options);
    }
}
