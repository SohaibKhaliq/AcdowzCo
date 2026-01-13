<?php

namespace Botble\Ecommerce\Importers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\DataSynchronize\Contracts\Importer\WithMapping;
use Botble\DataSynchronize\Importer\ImportColumn;
use Botble\DataSynchronize\Importer\Importer;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Slug\Facades\SlugHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductCategoryImporter extends Importer implements WithMapping
{
    public function getLabel(): string
    {
        return trans('plugins/ecommerce::product-categories.name');
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function getValidateUrl(): string
    {
        return route('tools.data-synchronize.import.product-categories.validate');
    }

    public function getImportUrl(): string
    {
        return route('tools.data-synchronize.import.product-categories.store');
    }

    public function getDownloadExampleUrl(): ?string
    {
        return route('tools.data-synchronize.import.product-categories.download-example');
    }

    public function getExportUrl(): ?string
    {
        return Auth::user()->hasPermission('product-categories.export')
            ? route('tools.data-synchronize.export.product-categories.store')
            : null;
    }

    public function columns(): array
    {
        return [
            ImportColumn::make('name')->rules(['required', 'string', 'max:250']),
            ImportColumn::make('slug')->rules(['nullable', 'string', 'max:250']),
            ImportColumn::make('parent')->rules(['nullable', 'string', 'max:250']),
            ImportColumn::make('description')->rules(['nullable', 'string', 'max:100000']),
            ImportColumn::make('status')->rules([Rule::in(BaseStatusEnum::values())]),
            ImportColumn::make('order')->rules(['nullable', 'integer', 'min:0', 'max:10000']),
            ImportColumn::make('image')->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('is_featured')
                ->rules(['sometimes', 'boolean'])
                ->boolean(),
            ImportColumn::make('icon')->rules(['nullable', 'string', 'max:50']),
            ImportColumn::make('icon_image')->rules(['nullable', 'string', 'max:255']),
        ];
    }

    public function examples(): array
    {
        return [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'parent' => null,
                'description' => 'All kinds of electronic items including gadgets, home appliances, and more.',
                'status' => 'published',
                'order' => 1,
                'image' => 'electronics.jpg',
                'is_featured' => true,
                'icon' => null,
                'icon_image' => 'electronics-icon.png',
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'parent' => null,
                'description' => 'Wide variety of books across different genres and languages.',
                'status' => 'draft',
                'order' => 2,
                'image' => 'books.jpg',
                'is_featured' => false,
                'icon' => 'ti ti-book',
                'icon_image' => null,
            ],
            [
                'name' => 'Clothing',
                'slug' => 'clothing',
                'parent' => null,
                'description' => 'Fashionable and comfortable clothing for men, women, and children.',
                'status' => 'published',
                'order' => 3,
                'image' => 'clothing.jpg',
                'is_featured' => true,
                'icon' => null,
                'icon_image' => 'clothing-icon.png',
            ],
            [
                'name' => 'Home & Kitchen',
                'slug' => 'home-kitchen',
                'parent' => null,
                'description' => 'Essentials for your home and kitchen needs.',
                'status' => 'published',
                'order' => 4,
                'image' => 'home-kitchen.jpg',
                'is_featured' => false,
                'icon' => null,
                'icon_image' => 'home-kitchen-icon.png',
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'parent' => null,
                'description' => 'Equipment and gear for sports and outdoor activities.',
                'status' => 'draft',
                'order' => 5,
                'image' => 'sports-outdoors.jpg',
                'is_featured' => true,
                'icon' => 'ti ti-motorbike',
                'icon_image' => null,
            ],
        ];
    }

    public function handle(array $data): int
    {
        $total = 0;

        foreach ($data as $row) {
            $slug = Arr::pull($row, 'slug');

            $parentName = Arr::pull($row, 'parent');

            if ($parentName) {
                $row['parent_id'] = ProductCategory::query()->where('name', $parentName)->value('id');
            }

            /**
             * @var ProductCategory $category
             */
            $category = ProductCategory::query()->create($row);

            if ($category->wasRecentlyCreated) {
                SlugHelper::createSlug($category, $slug);

                $total++;
            }
        }

        return $total;
    }

    public function map(mixed $row): array
    {
        return [
            ...$row,
            'order' => Arr::get($row, 'order') ?: 0,
        ];
    }
}
