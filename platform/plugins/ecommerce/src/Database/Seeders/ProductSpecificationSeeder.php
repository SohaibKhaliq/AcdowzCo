<?php

namespace Botble\Ecommerce\Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Enums\SpecificationAttributeFieldType;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\SpecificationAttribute;
use Botble\Ecommerce\Models\SpecificationGroup;
use Botble\Ecommerce\Models\SpecificationTable;
use Botble\Setting\Facades\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductSpecificationSeeder extends BaseSeeder
{
    public function run(): void
    {
        SpecificationTable::query()->truncate();
        SpecificationAttribute::query()->truncate();
        SpecificationGroup::query()->truncate();
        DB::table('ec_specification_table_group')->truncate();
        DB::table('ec_specification_attributes_translations')->truncate();
        DB::table('ec_specification_groups_translations')->truncate();
        DB::table('ec_specification_tables_translations')->truncate();
        DB::table('ec_product_specification_attribute')->truncate();

        Setting::set('ecommerce_enable_product_specification', true)->save();

        $data = [
            'Dimensions' => [
                [
                    'name' => 'Height',
                    'type' => SpecificationAttributeFieldType::TEXT,
                ],
                [
                    'name' => 'Width',
                    'type' => SpecificationAttributeFieldType::TEXT,
                ],
                [
                    'name' => 'Weight',
                    'type' => SpecificationAttributeFieldType::TEXT,
                ],
            ],
            'Performance' => [
                [
                    'name' => 'Power',
                    'type' => SpecificationAttributeFieldType::TEXT,
                ],
                [
                    'name' => 'Speed',
                    'type' => SpecificationAttributeFieldType::TEXT,
                ],
            ],
            'Battery' => [
                [
                    'name' => 'Battery Life',
                    'type' => SpecificationAttributeFieldType::TEXT,
                ],
            ],
            'Display' => [
                [
                    'name' => 'Screen Size',
                    'type' => SpecificationAttributeFieldType::TEXT,
                ],
                [
                    'name' => 'Resolution',
                    'type' => SpecificationAttributeFieldType::SELECT,
                    'options' => ['1920x1080', '2560x1440', '3840x2160'],
                ],
            ],
        ];

        foreach ($data as $group => $attributes) {
            /**
             * @var SpecificationGroup $group
             */
            $group = SpecificationGroup::query()->create([
                'name' => $group,
            ]);

            foreach ($attributes as $attribute) {
                $group->specificationAttributes()->create($attribute);
            }
        }

        /**
         * @var SpecificationTable $specificationTable
         */
        $specificationTable = SpecificationTable::query()
            ->create(['name' => 'General Specification']);

        $specificationTable
            ->groups()
            ->attach(SpecificationGroup::query()->whereIn('name', ['Dimensions', 'Performance'])->pluck('id'));

        /**
         * @var SpecificationTable $specificationTable
         */
        $specificationTable = SpecificationTable::query()
            ->create(['name' => 'Technical Specification']);

        $specificationTable
            ->groups()
            ->attach(SpecificationGroup::query()->whereIn('name', ['Battery', 'Display'])->pluck('id'));

        $tables = SpecificationTable::query()->with('groups.specificationAttributes')->get();
        $products = Product::query()->where('is_variation', false)->get();

        $products->each(function (Model|Product $product) use ($tables): void {
            $table = $tables->random();

            $product->update([
                'specification_table_id' => $table->id,
            ]);

            $table->groups->each(function ($group) use ($product): void {
                /**
                 * @var Product $product
                 */
                $group->specificationAttributes->each(function ($attribute) use ($product): void {
                    $value = $this->generateAttributeValue($attribute);

                    $product->specificationAttributes()->attach($attribute->id, [
                        'value' => $value,
                        'hidden' => false,
                        'order' => 0,
                    ]);
                });
            });
        });
    }

    protected function generateAttributeValue(SpecificationAttribute $attribute)
    {
        return match ($attribute->type->getValue()) {
            SpecificationAttributeFieldType::TEXT => $this->fake()->randomFloat(2, 1, 100) . ' cm',
            SpecificationAttributeFieldType::SELECT, SpecificationAttributeFieldType::RADIO => $this->fake()->randomElement($attribute->options),
            SpecificationAttributeFieldType::CHECKBOX => $this->fake()->boolean(),
            default => null,
        };
    }
}
