<?php

namespace Botble\Ecommerce\Importers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\DataSynchronize\Contracts\Importer\WithMapping;
use Botble\DataSynchronize\Importer\ImportColumn;
use Botble\DataSynchronize\Importer\Importer;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Enums\StockStatusEnum;
use Botble\Ecommerce\Events\ProductQuantityUpdatedEvent;
use Botble\Ecommerce\Events\ProductVariationCreated;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Imports\ImportTrait;
use Botble\Ecommerce\Models\Brand;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductAttributeSet;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Models\ProductCollection;
use Botble\Ecommerce\Models\ProductLabel;
use Botble\Ecommerce\Models\ProductVariation;
use Botble\Ecommerce\Models\Tax;
use Botble\Ecommerce\Services\Products\StoreProductService;
use Botble\Ecommerce\Services\StoreProductTagService;
use Botble\Language\Facades\Language;
use Botble\Media\Facades\RvMedia;
use Botble\Slug\Facades\SlugHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductImporter extends Importer implements WithMapping
{
    use ImportTrait;

    protected Collection $brands;

    protected Collection $categories;

    protected Collection $tags;

    protected Collection $taxes;

    protected Collection $stores;

    protected Collection $labels;

    protected Collection $productCollections;

    protected Collection $productLabels;

    protected Collection|Model $productAttributeSets;

    protected string $importType = 'all';

    protected Collection $allTaxes;

    protected Collection $barcodes;

    protected array $supportedLocales = [];

    protected ?string $defaultLanguage = null;

    protected bool $updateExisting = false;

    protected bool $isMarketplaceActive;

    protected bool $isEnabledDigital;

    public function __construct()
    {
        $this->categories = collect();
        $this->brands = collect();
        $this->taxes = collect();
        $this->labels = collect();
        $this->productCollections = collect();
        $this->productLabels = collect();
        $this->productAttributeSets = ProductAttributeSet::query()
            ->with('attributes')
            ->get();
        $this->allTaxes = Tax::query()->get();
        $this->barcodes = collect();

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            $this->defaultLanguage = Language::getDefaultLanguage(['lang_code'])?->lang_code;
            $this->supportedLocales = Language::getSupportedLocales();
        }

        $this->updateExisting = request()->boolean('update_existing_products');

        $this->isMarketplaceActive = is_plugin_active('marketplace');
        $this->isEnabledDigital = EcommerceHelper::isEnabledSupportDigitalProducts();
    }

    public function setImportType(string $importType): self
    {
        $this->importType = $importType;

        return $this;
    }

    public function getImportType(): string
    {
        return $this->importType;
    }

    public function label(): string
    {
        return trans('plugins/ecommerce::products.name');
    }

    public function chunkSize(): int
    {
        return 10;
    }

    public function getDoneMessage(int $count): string
    {
        return $this->updateExisting ? trans('plugins/ecommerce::products.import.updated_message', [
            'count' => number_format($count),
        ]) : trans('packages/data-synchronize::data-synchronize.import.done_message', [
            'count' => number_format($count),
            'label' => strtolower($this->getLabel()),
        ]);
    }

    protected function getBarcodeValidationRules(): array
    {
        $rules = ['nullable', 'string', 'max:150'];

        if (! $this->updateExisting) {
            $rules[] = 'unique:ec_products,barcode';
        }

        return $rules;
    }

    public function columns(): array
    {
        $columns = [
            ImportColumn::make('id')
                ->label('ID')
                ->rules(['nullable', 'string'], trans('plugins/ecommerce::products.import.rules.nullable_string', ['attribute' => 'ID'])),
            ImportColumn::make('name')
                ->rules(['required', 'string', 'max:250'], trans('plugins/ecommerce::products.import.rules.required_string_max', ['attribute' => 'Name', 'max' => 250])),
            ImportColumn::make('description')
                ->rules(['nullable', 'string'], trans('plugins/ecommerce::products.import.rules.nullable_string', ['attribute' => 'Description'])),
            ImportColumn::make('slug')
                ->rules(['nullable', 'string', 'max:250'], trans('plugins/ecommerce::products.import.rules.nullable_string_max', ['attribute' => 'Slug', 'max' => 250])),
            ImportColumn::make('url')
                ->label('URL')
                ->rules(['nullable', 'string', 'max:250'], trans('plugins/ecommerce::products.import.rules.nullable_string_max', ['attribute' => 'URL', 'max' => 250])),
            ImportColumn::make('sku')
                ->label('SKU')
                ->rules(['nullable', 'string', 'max:50'], trans('plugins/ecommerce::products.import.rules.nullable_string_max', ['attribute' => 'SKU', 'max' => 50])),
            ImportColumn::make('categories')
                ->rules(['nullable', 'array'], trans('plugins/ecommerce::products.import.rules.nullable_array', ['attribute' => 'Categories'])),
            ImportColumn::make('status')
                ->rules([Rule::in(BaseStatusEnum::values())], trans('plugins/ecommerce::products.import.rules.in', ['attribute' => 'Status', 'values' => implode(', ', BaseStatusEnum::values())])),
            ImportColumn::make('is_featured')
                ->rules(['nullable', 'bool'], trans('plugins/ecommerce::products.import.rules.nullable_bool', ['attribute' => 'Is featured'])),
            ImportColumn::make('brand')
                ->rules(['nullable', 'string'], trans('plugins/ecommerce::products.import.rules.nullable_string', ['attribute' => 'Brand'])),
            ImportColumn::make('product_collections')
                ->rules(['nullable', 'array'], trans('plugins/ecommerce::products.import.rules.nullable_array', ['attribute' => 'Product collections'])),
            ImportColumn::make('labels')
                ->rules(['nullable', 'array'], trans('plugins/ecommerce::products.import.rules.nullable_array', ['attribute' => 'Labels'])),
            ImportColumn::make('taxes')
                ->rules(['nullable', 'array'], trans('plugins/ecommerce::products.import.rules.nullable_array', ['attribute' => 'Taxes'])),
            ImportColumn::make('image')
                ->rules(['nullable', 'string'], trans('plugins/ecommerce::products.import.rules.nullable_string', ['attribute' => 'Image'])),
            ImportColumn::make('images')
                ->rules(['sometimes', 'array'], trans('plugins/ecommerce::products.import.rules.nullable_array', ['attribute' => 'Images'])),
            ImportColumn::make('price')
                ->rules(['numeric', 'nullable', 'min:0'], trans('plugins/ecommerce::products.import.rules.nullable_numeric_min', ['attribute' => 'Price'])),
            ImportColumn::make('product_attributes')
                ->rules(['nullable', 'array'], trans('plugins/ecommerce::products.import.rules.nullable_array', ['attribute' => 'Product attributes'])),
            ImportColumn::make('import_type')
                ->rules([Rule::in(['product', 'variation'])], trans('plugins/ecommerce::products.import.rules.in', ['attribute' => 'Import type', 'values' => 'product, variation'])),
            ImportColumn::make('is_variation_default')
                ->rules(['nullable', 'bool'], trans('plugins/ecommerce::products.import.rules.nullable_bool', ['attribute' => 'Is variation default'])),
            ImportColumn::make('stock_status')
                ->rules([Rule::in(StockStatusEnum::values())], trans('plugins/ecommerce::products.import.rules.in', ['attribute' => 'Stock status', 'values' => implode(', ', StockStatusEnum::values())])),
            ImportColumn::make('with_storehouse_management')
                ->rules(['nullable', 'bool'], trans('plugins/ecommerce::products.import.rules.nullable_bool', ['attribute' => 'With storehouse management'])),
            ImportColumn::make('quantity')
                ->rules(['numeric', 'nullable', 'min:0', 'max:100000000'], trans('plugins/ecommerce::products.import.rules.nullable_numeric_min_max', ['attribute' => 'Quantity', 'min' => 0, 'max' => 100000000])),
            ImportColumn::make('sale_price')
                ->rules(['numeric', 'nullable', 'min:0'], trans('plugins/ecommerce::products.import.rules.nullable_numeric_min', ['attribute' => 'Sale price'])),
            ImportColumn::make('start_date')
                ->rules(['date', 'nullable', 'required_if:sale_type,1'], trans('plugins/ecommerce::products.import.rules.nullable_date_required_if', ['attribute' => 'Start date', 'required' => 'Sale type'])),
            ImportColumn::make('end_date')
                ->rules(['date', 'nullable', 'after:start_date'], trans('plugins/ecommerce::products.import.rules.nullable_date_after', ['attribute' => 'End date', 'after' => 'Start date'])),
            ImportColumn::make('weight')
                ->rules(['numeric', 'nullable', 'min:0', 'max:100000000'], trans('plugins/ecommerce::products.import.rules.nullable_numeric_min_max', ['attribute' => 'Weight', 'min' => 0, 'max' => 100000000])),
            ImportColumn::make('length')
                ->rules(['numeric', 'nullable', 'min:0', 'max:100000000'], trans('plugins/ecommerce::products.import.rules.nullable_numeric_min_max', ['attribute' => 'Length', 'min' => 0, 'max' => 100000000])),
            ImportColumn::make('wide')
                ->rules(['numeric', 'nullable', 'min:0', 'max:100000000'], trans('plugins/ecommerce::products.import.rules.nullable_numeric_min_max', ['attribute' => 'Wide', 'min' => 0, 'max' => 100000000])),
            ImportColumn::make('height')
                ->rules(['numeric', 'nullable', 'min:0', 'max:100000000'], trans('plugins/ecommerce::products.import.rules.nullable_numeric_min_max', ['attribute' => 'Height', 'min' => 0, 'max' => 100000000])),
            ImportColumn::make('cost_per_item')
                ->rules(['nullable', 'numeric', 'min:0'], trans('plugins/ecommerce::products.import.rules.nullable_numeric_min', ['attribute' => 'Cost per item'])),
            ImportColumn::make('barcode')
                ->rules($this->getBarcodeValidationRules(), trans('plugins/ecommerce::products.import.rules.nullable_string_max', ['attribute' => 'Barcode', 'max' => 150])),
            ImportColumn::make('content')
                ->rules(['nullable', 'string'], trans('plugins/ecommerce::products.import.rules.nullable_string', ['attribute' => 'Content'])),
            ImportColumn::make('tags')
                ->rules(['nullable', 'array'], trans('plugins/ecommerce::products.import.rules.nullable_array', ['attribute' => 'Tags'])),
            ImportColumn::make('product_type')
                ->rules([Rule::in(ProductTypeEnum::values())], trans('plugins/ecommerce::products.import.rules.in', ['attribute' => 'Product type', 'values' => implode(', ', ProductTypeEnum::values())])),
            ImportColumn::make('auto_generate_sku')
                ->rules(['nullable', 'bool'], trans('plugins/ecommerce::products.import.rules.nullable_bool', ['attribute' => 'Auto generate SKU'])),
            ImportColumn::make('generate_license_code')
                ->rules(['nullable', 'bool'], trans('plugins/ecommerce::products.import.rules.nullable_bool', ['attribute' => 'Generate license code'])),
            ImportColumn::make('minimum_order_quantity')
                ->rules(['nullable', 'numeric', 'min:0'], trans('plugins/ecommerce::products.import.rules.nullable_numeric_min', ['attribute' => 'Minimum order quantity'])),
            ImportColumn::make('maximum_order_quantity')
                ->rules(['nullable', 'numeric', 'min:0'], trans('plugins/ecommerce::products.import.rules.nullable_numeric_min', ['attribute' => 'Maximum order quantity'])),
            ImportColumn::make('order')
                ->rules(['nullable', 'integer', 'min:0'], trans('plugins/ecommerce::products.import.rules.nullable_numeric_min', ['attribute' => 'Order'])),
        ];

        if (is_plugin_active('marketplace')) {
            $columns[] = ImportColumn::make('vendor')
                ->rules(['nullable', 'string'], trans('plugins/ecommerce::products.import.rules.nullable_string', ['attribute' => 'Vendor']));
        }

        foreach ($this->supportedLocales as $properties) {
            if ($properties['lang_code'] != $this->defaultLanguage) {
                $langCode = strtolower($properties['lang_code']);

                $columns[] = ImportColumn::make("name_({$langCode})")
                    ->label('Name (' . strtoupper($langCode) . ')')
                    ->rules(['nullable', 'string', 'max:250'], trans('plugins/ecommerce::products.import.rules.nullable_string_max', ['attribute' => 'Name (' . strtoupper($langCode) . ')', 'max' => 250]));
                $columns[] = ImportColumn::make("description_({$langCode})")
                    ->label('Description (' . strtoupper($langCode) . ')')
                    ->rules(['nullable', 'string'], trans('plugins/ecommerce::products.import.rules.nullable_string', ['attribute' => 'Description (' . strtoupper($langCode) . ')']));
                $columns[] = ImportColumn::make("content_({$langCode})")
                    ->label('Content (' . strtoupper($langCode) . ')')
                    ->rules(['nullable', 'string'], trans('plugins/ecommerce::products.import.rules.nullable_string', ['attribute' => 'Content (' . strtoupper($langCode) . ')']));
            }
        }

        return $columns;
    }

    public function examples(): array
    {
        $with = [
            'categories',
            'slugable',
            'brand',
            'taxes',
            'productLabels',
            'productCollections',
            'variations',
            'variations.product',
            'variations.configurableProduct',
            'variations.productAttributes.productAttributeSet',
            'tags',
            'productAttributeSets',
        ];

        if ($this->isMarketplaceActive) {
            $with[] = 'store';
        }

        if (count($this->supportedLocales)) {
            $with[] = 'translations';
        }

        $products = $this->getProductQuery()
            ->where('is_variation', false)
            ->with($with)
            ->take(5)
            ->get();

        if ($products->isNotEmpty()) {
            return $this->productResults($products);
        }

        $examples = [
            [
                'id' => null,
                'name' => 'Product name',
                'description' => 'Product description',
                'slug' => 'product-slug',
                'url' => 'product-url',
                'sku' => 'product-sku',
                'categories' => 'category1,category2',
                'status' => 'publish',
                'is_featured' => 1,
                'brand' => 'brand-name',
                'product_collections' => 'collection1,collection2',
                'labels' => 'label1,label2',
                'taxes' => 'tax1,tax2',
                'image' => 'image-url',
                'images' => 'image-url1,image-url2',
                'price' => '100',
                'product_attributes' => 'attribute1,attribute2',
                'import_type' => 'product',
                'is_variation_default' => 1,
                'stock_status' => 'in_stock',
                'with_storehouse_management' => 1,
                'quantity' => '100',
                'sale_price' => '90',
                'start_date' => '2021-01-01',
                'end_date' => '2021-01-31',
                'weight' => 1,
                'length' => 1,
                'wide' => 1,
                'height' => 1,
                'cost_per_item' => 10,
                'barcode' => 'product-barcode',
                'content' => 'product-content',
                'tags' => 'tag1,tag2',
                'product_type' => 'physical',
                'vendor' => 'vendor-name',
                'auto_generate_sku' => 1,
                'generate_license_code' => 1,
                'minimum_order_quantity' => 1,
                'maximum_order_quantity' => 10,
                'order' => 0,
            ],
        ];

        foreach ($this->supportedLocales as $properties) {
            if ($properties['lang_code'] != $this->defaultLanguage) {
                $langCode = strtolower($properties['lang_code']);

                $examples[0]['name_(' . $langCode . ')'] = 'Product name (' . strtoupper($langCode) . ')';
                $examples[0]['description_(' . $langCode . ')'] = 'Product description (' . strtoupper($langCode) . ')';
                $examples[0]['content_(' . $langCode . ')'] = 'Product content (' . strtoupper($langCode) . ')';
            }
        }

        return $examples;
    }

    public function productResults(Collection $products): array
    {
        $results = [];

        foreach ($products as $product) {
            $productAttributes = [];

            if (! $product->is_variation) {
                $productAttributes = $product->productAttributeSets->pluck('title')->all();
            }

            $result = [
                'id' => '',
                'name' => $product->name,
                'description' => Str::limit($product->description),
                'content' => Str::limit($product->content),
                'slug' => $product->slug,
                'url' => $product->url,
                'sku' => $product->sku,
                'categories' => implode(',', $product->categories->pluck('name')->all()),
                'status' => $product->status->getValue(),
                'is_featured' => $product->is_featured,
                'brand' => $product->brand->name,
                'product_collections' => implode(',', $product->productCollections->pluck('name')->all()),
                'labels' => implode(',', $product->productLabels->pluck('name')->all()),
                'taxes' => implode(',', $product->taxes->pluck('title')->all()),
                'image' => RvMedia::getImageUrl($product->image),
                'images' => collect($product->images)->map(fn ($value) => RvMedia::getImageUrl($value))->implode(','),
                'price' => $product->price,
                'product_attributes' => implode(',', $productAttributes),
                'import_type' => 'product',
                'auto_generate_sku' => true,
                'is_variation_default' => $product->is_variation_default,
                'stock_status' => $product->stock_status->getValue(),
                'with_storehouse_management' => $product->with_storehouse_management,
                'quantity' => $product->quantity,
                'allow_checkout_when_out_of_stock' => $product->allow_checkout_when_out_of_stock,
                'sale_price' => $product->sale_price,
                'start_date' => $product->start_date,
                'end_date' => $product->end_date,
                'weight' => $product->weight,
                'length' => $product->length,
                'wide' => $product->wide,
                'height' => $product->height,
                'cost_per_item' => $product->cost_per_item,
                'barcode' => $product->barcode,
                'tags' => implode(',', $product->tags->pluck('name')->all()),
                'generate_license_code' => $product->generate_license_code,
                'minimum_order_quantity' => $product->minimum_order_quantity,
                'maximum_order_quantity' => $product->maximum_order_quantity,
                'order' => (int) $product->order ?: 0,
            ];

            if ($this->isEnabledDigital) {
                $result['product_type'] = $product->product_type;
            }

            if ($this->isMarketplaceActive) {
                $result['vendor'] = $product->store?->id ? $product->store->name : null;
            }

            foreach ($this->supportedLocales as $properties) {
                if ($properties['lang_code'] != $this->defaultLanguage) {
                    $translation = $product->translations->where('lang_code', $properties['lang_code'])->first();

                    $langCode = strtolower($properties['lang_code']);

                    $result['name_(' . $langCode . ')'] = $translation ? $translation->name : '';
                    $result['description_(' . $langCode . ')'] = $translation ? $translation->description : '';
                    $result['content_(' . $langCode . ')'] = $translation ? $translation->content : '';
                }
            }

            $results[] = $result;

            if ($product->variations->count()) {
                foreach ($product->variations as $variation) {
                    $productAttributes = $this->getProductAttributes($variation);

                    $data = [
                        'id' => '',
                        'name' => $variation->product->name,
                        'description' => '',
                        'slug' => '',
                        'url' => '',
                        'sku' => $variation->product->sku,
                        'categories' => '',
                        'status' => $variation->product->status->getValue(),
                        'is_featured' => '',
                        'brand' => '',
                        'product_collections' => '',
                        'labels' => '',
                        'taxes' => '',
                        'image' => RvMedia::getImageUrl($variation->product->image),
                        'images' => collect($variation->product->images)->map(fn ($value) => RvMedia::getImageUrl($value))->implode(','),
                        'price' => $variation->product->price,
                        'product_attributes' => implode(',', $productAttributes),
                        'import_type' => 'variation',
                        'auto_generate_sku' => true,
                        'is_variation_default' => $variation->is_default,
                        'stock_status' => $variation->product->stock_status->getValue(),
                        'with_storehouse_management' => $variation->product->with_storehouse_management,
                        'quantity' => $variation->product->quantity,
                        'allow_checkout_when_out_of_stock' => $variation->product->allow_checkout_when_out_of_stock,
                        'sale_price' => $variation->product->sale_price,
                        'start_date' => $variation->product->start_date,
                        'end_date' => $variation->product->end_date,
                        'weight' => $variation->product->weight,
                        'length' => $variation->product->length,
                        'wide' => $variation->product->wide,
                        'height' => $variation->product->height,
                        'cost_per_item' => $variation->product->cost_per_item,
                        'barcode' => $variation->product->barcode,
                        'content' => '',
                        'tags' => '',
                        'generate_license_code' => $variation->product->generate_license_code,
                        'minimum_order_quantity' => $variation->product->minimum_order_quantity,
                        'maximum_order_quantity' => $variation->product->maximum_order_quantity,
                        'order' => (int) $variation->product->order ?: 0,
                    ];

                    if ($this->isEnabledDigital) {
                        $data['product_type'] = ProductTypeEnum::PHYSICAL;
                    }

                    if ($this->isMarketplaceActive) {
                        $data['vendor'] = '';
                    }

                    foreach ($this->supportedLocales as $properties) {
                        if ($properties['lang_code'] != $this->defaultLanguage) {
                            $translation = $variation->product->translations->where('lang_code', $properties['lang_code'])->first();

                            $langCode = strtolower($properties['lang_code']);

                            $data['name_' . '(' . $langCode . ')'] = $translation ? $translation->name : '';
                            $data['description_' . '(' . $langCode . ')'] = $translation ? $translation->description : '';
                            $data['content_' . '(' . $langCode . ')'] = '';
                        }
                    }

                    $results[] = $data;
                }
            }
        }

        return $results;
    }

    public function getProductAttributes(Product|ProductVariation $product): array
    {
        $productAttributes = [];

        foreach ($product->productAttributes as $productAttribute) {
            if ($productAttribute->productAttributeSet) {
                $productAttributes[] = $productAttribute->productAttributeSet->title . ':' . $productAttribute->title;
            }
        }

        return $productAttributes;
    }

    public function getValidateUrl(): string
    {
        return route('tools.data-synchronize.import.products.validate');
    }

    public function getImportUrl(): string
    {
        return route('tools.data-synchronize.import.products.store');
    }

    public function getDownloadExampleUrl(): ?string
    {
        return route('tools.data-synchronize.import.products.download-example');
    }

    public function getExportUrl(): ?string
    {
        return Auth::user()->hasPermission('ecommerce.export.products.index')
            ? route('tools.data-synchronize.export.products.index')
            : null;
    }

    public function handle(array $data): int
    {
        foreach ($data as $row) {
            $importType = $this->getImportType();

            if ($importType === 'products' && $row['import_type'] === 'product') {
                $this->storeProduct($row);

                continue;
            }

            if ($importType === 'variations' && $row['import_type'] === 'variation') {
                $product = $this->getProduct($row['name'], $row['slug']);

                $this->storeVariant($row, $product);

                continue;
            }

            if ($row['import_type'] === 'variation') {
                if ($slug = $row['slug']) {
                    $collection = $this->successes()
                        ->where('import_type', 'product')
                        ->where('slug', $slug)
                        ->last();
                } else {
                    $collection = $this->successes()
                        ->where('import_type', 'product')
                        ->where('name', $row['name'])
                        ->last();
                }

                if ($collection) {
                    $product = $collection['model'];
                } else {
                    $product = $this->getProduct($row['name'], $slug);
                }

                $this->storeVariant($row, $product);
            } else {
                $this->storeProduct($row);
            }
        }

        return $this->successes()->count();
    }

    protected function getProduct(string $name, ?string $slug): Model|Builder|null
    {
        $product = null;

        if ($slug) {
            $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Product::class), Product::class);

            if ($slug) {
                $product = $this->getProductQuery()
                    ->where([
                        'id' => $slug->reference_id,
                        'is_variation' => 0,
                    ])
                    ->first();
            }
        }

        if ($product) {
            return $product;
        }

        return $this->getProductQuery()
            ->where(function ($query) use ($name): void {
                $query
                    ->where('name', $name)
                    ->orWhere('id', $name);
            })
            ->where('is_variation', 0)
            ->first();
    }

    public function map(mixed $row): array
    {
        $this->currentRow++;
        $row = $this->mapLocalization($row);
        $row = $this->setCategoriesToRow($row);
        $row = $this->setBrandToRow($row);
        $row = $this->setTaxToRow($row);
        $row = $this->setProductCollectionsToRow($row);
        $row = $this->setProductLabelsToRow($row);

        return apply_filters('ecommerce_import_product_row_data', $row);
    }

    public function storeProduct(array $row): Product|Model|null
    {
        $request = new Request();
        $request->merge($row);

        $existingProduct = null;

        if (Arr::get($row, 'id')) {
            $existingProduct = $this->getProductQuery()
                ->where('id', $row['id'])
                ->first();
        }

        if (! $existingProduct && ($sku = $request->input('sku'))) {
            $existingProduct = $this->getProductQuery()
                ->where('sku', $sku)
                ->first();
        }

        if ($existingProduct) {
            if ($this->updateExisting) {
                return $this->updateProduct($existingProduct, $row, $request);
            }

            return $existingProduct;
        }

        return $this->updateProduct(new Product(), $row, $request);
    }

    public function storeVariant(array $row, ?Product $product): ProductVariation|Model|null
    {
        $request = new Request();
        $request->merge($row);

        if (! $product) {
            $this->onFailure(
                $this->currentRow,
                'Name',
                [__('Product name ":name" does not exists', ['name' => $request->input('name')])]
            );

            return null;
        }

        $addedAttributes = $request->input('attribute_sets', []);

        $existingVariationProduct = null;
        $existingVariation = null;

        if ($sku = $request->input('sku')) {
            $existingVariationProduct = $this->getProductQuery()
                ->where('is_variation', true)
                ->where('sku', $sku)
                ->first();

            if ($existingVariationProduct) {
                $existingVariation = ProductVariation::query()
                    ->where('product_id', $existingVariationProduct->id)
                    ->first();

                if ($existingVariation && $existingVariation->configurable_product_id != $product->getKey()) {
                    $this->onFailure(
                        $this->currentRow,
                        'SKU',
                        [__('SKU ":sku" already exists for a variation of another product', ['sku' => $sku])]
                    );

                    return null;
                }
            }
        }

        if ($existingVariation && ! $this->updateExisting) {
            return $existingVariation;
        }

        $existingVariationByAttributes = ProductVariation::getVariationByAttributes($product->getKey(), $addedAttributes);

        if ($existingVariation && $this->updateExisting) {
            if ($existingVariationByAttributes && $existingVariationByAttributes->id !== $existingVariation->id) {
                $this->onFailure(
                    $this->currentRow,
                    'SKU/Attributes',
                    [__('SKU ":sku" belongs to a different variation than the one with matching attributes', ['sku' => $request->input('sku')])]
                );

                return null;
            }
            $variation = $existingVariation;
            $result = ['variation' => $variation, 'created' => false];
        } elseif ($existingVariationByAttributes) {
            if ($this->updateExisting) {
                $variation = $existingVariationByAttributes;
                $result = ['variation' => $variation, 'created' => false];
            } else {
                return $existingVariationByAttributes;
            }
        } else {
            $result = ProductVariation::getVariationByAttributesOrCreate($product->getKey(), $addedAttributes);
            $variation = $result['variation'];
        }

        $version = array_merge($variation->toArray(), $request->toArray());

        $version['variation_default_id'] = Arr::get($version, 'is_variation_default') ? $version['id'] : null;
        $version['attribute_sets'] = $addedAttributes;

        if ($version['description']) {
            $version['description'] = BaseHelper::clean($version['description']);
        }

        if ($version['content']) {
            $version['content'] = BaseHelper::clean($version['content']);
        }

        if ($existingVariationProduct && $this->updateExisting) {
            $productRelatedToVariation = $existingVariationProduct;
            $allowedFields = [
                'price', 'sale_price', 'quantity', 'weight', 'length', 'wide', 'height',
                'cost_per_item', 'stock_status', 'with_storehouse_management',
                'allow_checkout_when_out_of_stock', 'sale_type', 'start_date', 'end_date',
                'description', 'content', 'images',
            ];

            if (isset($version['barcode']) && $version['barcode'] !== $productRelatedToVariation->barcode) {
                $allowedFields[] = 'barcode';
            }

            $productRelatedToVariation->fill(array_filter($version, function ($key) use ($allowedFields) {
                return in_array($key, $allowedFields);
            }, ARRAY_FILTER_USE_KEY));
        } elseif ($variation->product_id) {
            $productRelatedToVariation = Product::query()->find($variation->product_id);
            if ($productRelatedToVariation && $this->updateExisting) {
                $allowedFields = [
                    'price', 'sale_price', 'quantity', 'weight', 'length', 'wide', 'height',
                    'cost_per_item', 'stock_status', 'with_storehouse_management',
                    'allow_checkout_when_out_of_stock', 'sale_type', 'start_date', 'end_date',
                    'description', 'content', 'images',
                ];

                if (isset($version['barcode']) && $version['barcode'] !== $productRelatedToVariation->barcode) {
                    $allowedFields[] = 'barcode';
                }

                $productRelatedToVariation->fill(array_filter($version, function ($key) use ($allowedFields) {
                    return in_array($key, $allowedFields);
                }, ARRAY_FILTER_USE_KEY));
            } elseif ($productRelatedToVariation && ! $this->updateExisting) {
                return $variation;
            } else {
                $productRelatedToVariation = new Product();
                $productRelatedToVariation->fill($version);
            }
        } else {
            $productRelatedToVariation = new Product();
            $productRelatedToVariation->fill($version);
        }

        $productRelatedToVariation->name = $product->name;
        $productRelatedToVariation->status = $product->status;
        $productRelatedToVariation->brand_id = $product->brand_id;
        $productRelatedToVariation->is_variation = 1;

        $productRelatedToVariation->sku = Arr::get($version, 'sku');
        if (! $productRelatedToVariation->sku && Arr::get($version, 'auto_generate_sku')) {
            $productRelatedToVariation->sku = $product->sku;
            foreach ($version['attribute_sets'] as $setId => $attributeId) {
                $attributeSet = $this->productAttributeSets->firstWhere('id', $setId);
                if ($attributeSet) {
                    $attribute = $attributeSet->attributes->firstWhere('id', $attributeId);
                    if ($attribute) {
                        $productRelatedToVariation->sku .= '-' . Str::upper($attribute->slug);
                    }
                }
            }
        }

        $productRelatedToVariation->price = Arr::get($version, 'price', $product->price);
        $productRelatedToVariation->sale_price = Arr::get($version, 'sale_price', $product->sale_price);

        if (Arr::get($version, 'description')) {
            $productRelatedToVariation->description = BaseHelper::clean($version['description']);
        }

        if (Arr::get($version, 'content')) {
            $productRelatedToVariation->content = BaseHelper::clean($version['content']);
        }

        $productRelatedToVariation->length = Arr::get($version, 'length', $product->length);
        $productRelatedToVariation->wide = Arr::get($version, 'wide', $product->wide);
        $productRelatedToVariation->height = Arr::get($version, 'height', $product->height);
        $productRelatedToVariation->weight = Arr::get($version, 'weight', $product->weight);

        $productRelatedToVariation->sale_type = (int) Arr::get($version, 'sale_type', $product->sale_type);

        if ($productRelatedToVariation->sale_type == 0) {
            $productRelatedToVariation->start_date = null;
            $productRelatedToVariation->end_date = null;
        } else {
            $productRelatedToVariation->start_date = Carbon::parse(
                Arr::get($version, 'start_date', $product->start_date)
            )->toDateTimeString();
            $productRelatedToVariation->end_date = Carbon::parse(
                Arr::get($version, 'end_date', $product->end_date)
            )->toDateTimeString();
        }

        $productRelatedToVariation->images = json_encode(
            $this->getImageURLs((array) Arr::get($version, 'images', []) ?: [])
        );

        $productRelatedToVariation->status = strtolower(Arr::get($version, 'status', $product->status));

        $productRelatedToVariation->product_type = $product->product_type;
        $productRelatedToVariation->save();

        if ($variation->product) {
            event(new ProductQuantityUpdatedEvent($variation->product));
        }

        ProductVariationCreated::dispatch($productRelatedToVariation);

        $variation->product_id = $productRelatedToVariation->getKey();

        $isVariationDefault = (bool) Arr::get($version, 'is_variation_default', false);

        if ($isVariationDefault) {
            ProductVariation::query()
                ->where('configurable_product_id', $product->getKey())
                ->where('id', '!=', $variation->id)
                ->update(['is_default' => false]);

            $variation->is_default = true;
        } else {
            if ($this->updateExisting || ! $result['created']) {
                $variation->is_default = false;
            }
        }

        $variation->save();

        if ($version['attribute_sets']) {
            $variation->productAttributes()->sync($version['attribute_sets']);
        }

        $this->createTranslations($productRelatedToVariation, $row);

        $this->onSuccess([
            'name' => $variation->name,
            'slug' => '',
            'import_type' => 'variation',
            'attribute_sets' => [],
            'model' => $variation,
        ]);

        return $variation;
    }

    protected function updateProduct(Product $product, array $row, Request $request): Product
    {
        $images = $this->getImageURLs((array) $request->input('images', []));

        $request->merge(['images' => $images]);

        $image = Arr::first($images);

        if ($request->input('image')) {
            $imageFromRequest = $this->getImageURLs([$request->input('image')]);

            if ($imageFromRequest) {
                $image = Arr::first($imageFromRequest);
            }
        }

        $request->merge(['image' => $image]);

        if ($description = $request->input('description')) {
            $request->merge(['description' => BaseHelper::clean($description)]);
        }

        if ($content = $request->input('content')) {
            $request->merge(['content' => BaseHelper::clean($content)]);
        }

        $prevStatus = $product->status;
        $prevStockStatus = $product->stock_status;
        $product->status = strtolower($request->input('status'));

        if (! $product->exists && $request->input('name')) {
            $product->name = $request->input('name');
        }

        $product = $this->assignProductData($request, $product);

        $product = (new StoreProductService())->execute($request, $product);

        $this->createTranslations($product, $row);

        $tagsInput = (array) $request->input('tags', []);
        if ($tagsInput) {
            $tags = [];
            foreach ($tagsInput as $tag) {
                $tags[] = ['value' => $tag];
            }
            $request->merge(['tag' => json_encode($tags)]);
            app(StoreProductTagService::class)->execute($request, $product);
        }

        $attributeSets = $request->input('attribute_sets', []);
        $preserveVariationAttributes = $request->input('preserve_variation_attributes', false);

        // For products with variations, preserve attribute sets that are used by variations
        // when the CSV doesn't specify any attributes or explicitly wants to preserve them
        if ($product->exists && $product->has_variation && (empty($attributeSets) || $preserveVariationAttributes)) {
            // Get all attribute sets used by variations
            $variationAttributeSetIds = [];
            foreach ($product->variations as $variation) {
                $variationAttributes = $variation->productAttributes()->with('productAttributeSet')->get();
                foreach ($variationAttributes as $attr) {
                    if ($attr->productAttributeSet) {
                        $variationAttributeSetIds[] = $attr->productAttributeSet->id;
                    }
                }
            }

            if (! empty($variationAttributeSetIds)) {
                // Merge with any explicitly provided attribute sets
                $attributeSets = array_unique(array_merge($attributeSets, $variationAttributeSetIds));
            }
        }

        $product->productAttributeSets()->sync($attributeSets);

        $product->refresh();

        if ($product->wasChanged($product->getFillable()) && ($prevStatus != $product->status || $prevStockStatus != $product->stock_status)) {
            $this->onSuccess([
                'name' => $product->name,
                'slug' => $request->input('slug'),
                'import_type' => 'product',
                'attribute_sets' => $attributeSets,
                'model' => $product,
            ]);
        }

        return $product;
    }

    protected function createTranslations(Product $product, array $row): void
    {
        if (! defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            return;
        }

        foreach ($this->supportedLocales as $language) {
            if ($language['lang_is_default']) {
                continue;
            }

            $translation = DB::table('ec_products_translations')->where([
                'lang_code' => $language['lang_code'],
                'ec_products_id' => $product->getKey(),
            ]);

            $languageCode = strtolower($language['lang_code']);

            $translationData = [
                'name' => $row["name_({$languageCode})"] ?? $row['name'],
                'description' => $row["description_({$languageCode})"] ?? $row['description'],
                'content' => $row["content_({$languageCode})"] ?? $row['content'],
            ];

            foreach ($translationData as $key => $translationItem) {
                $translationData[$key] = mb_convert_encoding($translationItem, 'UTF-8');
            }

            if ($translation->exists()) {
                $translation->update($translationData);
            } else {
                $translationData['ec_products_id'] = $product->getKey();
                $translationData['lang_code'] = $language['lang_code'];

                DB::table('ec_products_translations')->insert($translationData);
            }
        }
    }

    public function mapLocalization(array $row): array
    {
        $row['generate_license_code'] = (bool) Arr::get($row, 'generate_license_code', false);
        $row['minimum_order_quantity'] = (int) Arr::get($row, 'minimum_order_quantity', 0);
        $row['maximum_order_quantity'] = (int) Arr::get($row, 'maximum_order_quantity', 0);
        $row['stock_status'] = (string) Arr::get($row, 'stock_status');
        if (! in_array($row['stock_status'], StockStatusEnum::toArray())) {
            $row['stock_status'] = StockStatusEnum::IN_STOCK;
        }

        $row['status'] = Arr::get($row, 'status');
        if (! in_array($row['status'], BaseStatusEnum::toArray())) {
            $row['status'] = BaseStatusEnum::PENDING;
        }

        $row['product_type'] = Arr::get($row, 'product_type');
        if (! in_array($row['product_type'], ProductTypeEnum::toArray())) {
            $row['product_type'] = ProductTypeEnum::PHYSICAL;
        }

        $row['import_type'] = Arr::get($row, 'import_type');
        if ($row['import_type'] != 'variation') {
            $row['import_type'] = 'product';
        }

        $row['is_slug_editable'] = true;

        $row['barcode'] = (string) Arr::get($row, 'barcode');

        if (Arr::get($row, 'id')) {
            $row['id'] = (string) Arr::get($row, 'id');
        }

        $this->setValues($row, [
            ['key' => 'slug', 'type' => 'string', 'default' => 'name'],
            ['key' => 'sku', 'type' => 'string'],
            ['key' => 'price', 'type' => 'number'],
            ['key' => 'weight', 'type' => 'number'],
            ['key' => 'length', 'type' => 'number'],
            ['key' => 'wide', 'type' => 'number'],
            ['key' => 'height', 'type' => 'number'],
            ['key' => 'cost_per_item', 'type' => 'number'],
            ['key' => 'barcode', 'type' => 'string'],
            ['key' => 'is_featured', 'type' => 'bool'],
            ['key' => 'product_labels', 'type' => 'array'],
            ['key' => 'labels', 'type' => 'array'],
            ['key' => 'images', 'type' => 'array'],
            ['key' => 'categories', 'type' => 'array'],
            ['key' => 'product_collections', 'type' => 'array'],
            ['key' => 'product_attributes', 'type' => 'array'],
            ['key' => 'is_variation_default', 'type' => 'bool'],
            ['key' => 'auto_generate_sku', 'type' => 'bool'],
            ['key' => 'with_storehouse_management', 'type' => 'bool'],
            ['key' => 'allow_checkout_when_out_of_stock', 'type' => 'bool'],
            ['key' => 'quantity', 'type' => 'number'],
            ['key' => 'sale_price', 'type' => 'number'],
            ['key' => 'start_date', 'type' => 'datetime'],
            ['key' => 'end_date', 'type' => 'datetime'],
            ['key' => 'tags', 'type' => 'array'],
            ['key' => 'taxes', 'type' => 'array'],
            ['key' => 'order', 'type' => 'number'],
        ]);

        $row['product_labels'] = $row['labels'];

        if ($row['import_type'] == 'product' && ! $row['sku'] && $row['auto_generate_sku']) {
            $row['sku'] = (new Product())->generateSKU();
        }

        $row['sale_type'] = 0;
        if ($row['start_date'] || $row['end_date']) {
            $row['sale_type'] = 1;
        }

        if (! $row['with_storehouse_management']) {
            $row['quantity'] = null;
            $row['allow_checkout_when_out_of_stock'] = false;
        }

        $attributeSets = Arr::get($row, 'product_attributes');
        $row['attribute_sets'] = [];
        $row['product_attributes'] = [];

        if ($row['import_type'] == 'variation') {
            foreach ($attributeSets as $attrSet) {
                $attrSet = explode(':', $attrSet);
                $title = Arr::get($attrSet, 0);
                $valueX = Arr::get($attrSet, 1);

                $attribute = $this->productAttributeSets->filter(function ($value) use ($title) {
                    return strtolower($value['title']) == strtolower($title);
                })->first();

                if ($attribute) {
                    $attr = $attribute->attributes->filter(function ($value) use ($valueX) {
                        return strtolower($value['title']) == strtolower($valueX);
                    })->first();

                    if (! $attr) {
                        $attr = $attribute->attributes()->create([
                            'title' => $valueX,
                            'slug' => Str::slug($valueX),
                        ]);
                    }

                    if ($attr) {
                        $row['attribute_sets'][$attribute->id] = $attr->id;
                    }
                }
            }
        }

        if ($row['import_type'] == 'product') {
            if (! empty($attributeSets)) {
                foreach ($attributeSets as $attrSet) {
                    $attribute = $this->productAttributeSets->filter(function ($value) use ($attrSet) {
                        return strtolower($value['title']) == strtolower($attrSet);
                    })->first();

                    if (! $attribute) {
                        $attribute = ProductAttributeSet::query()->create([
                            'title' => $attrSet,
                            'slug' => Str::slug($attrSet),
                        ]);
                    }

                    if ($attribute) {
                        $row['attribute_sets'][] = $attribute->id;
                    }
                }
            } else {
                // Mark that attributes were empty in CSV so we know to preserve variation attributes
                $row['preserve_variation_attributes'] = true;
            }
        }

        $row['order'] = (int) Arr::get($row, 'order');

        return $row;
    }

    protected function setBrandToRow(array $row): array
    {
        $row['brand_id'] = 0;

        if (! empty($row['brand'])) {
            $row['brand'] = trim($row['brand']);

            $brand = $this->brands->firstWhere('keyword', $row['brand']);
            if ($brand) {
                $brandId = $brand['brand_id'];
            } else {
                if (is_numeric($row['brand'])) {
                    $brand = Brand::query()->find($row['brand']);
                } else {
                    $brand = Brand::query()->where('name', $row['brand'])->first();
                }

                $brandId = $brand ? $brand->getKey() : 0;
                $this->brands->push([
                    'keyword' => $row['brand'],
                    'brand_id' => $brandId,
                ]);
            }

            $row['brand_id'] = $brandId;
        }

        return $row;
    }

    protected function setTaxToRow(array $row): array
    {
        $row['tax_id'] = null;

        $taxIds = [];
        if (! empty($row['tax'])) {
            $tax = $this->getTaxByKeyword(trim($row['tax']));
            if ($tax) {
                $taxIds[] = $tax->getKey();
            }
        }

        if ($row['taxes']) {
            foreach ($row['taxes'] as $value) {
                $tax = $this->getTaxByKeyword(trim($value));
                if ($tax) {
                    $taxIds[] = $tax->getKey();
                }
            }

            $row['taxes'] = array_filter($taxIds);
        }

        return $row;
    }

    protected function setCategoriesToRow(array $row): array
    {
        if ($row['categories']) {
            $categories = $row['categories'];
            $categoryIds = [];
            foreach ($categories as $value) {
                $value = trim($value);

                $category = $this->categories->firstWhere('keyword', $value);
                if ($category) {
                    $categoryId = $category['category_id'];
                } else {
                    if (is_numeric($value)) {
                        $category = ProductCategory::query()->find($value);
                    } else {
                        $category = ProductCategory::query()->where('name', $value)->first();
                    }

                    $categoryId = $category ? $category->getKey() : 0;
                    $this->categories->push([
                        'keyword' => $value,
                        'category_id' => $categoryId,
                    ]);
                }
                $categoryIds[] = $categoryId;
            }

            $row['categories'] = array_filter($categoryIds);
        }

        return $row;
    }

    protected function setProductCollectionsToRow(array $row): array
    {
        if ($row['product_collections']) {
            $productCollections = $row['product_collections'];
            $collectionIds = [];
            foreach ($productCollections as $value) {
                $value = trim($value);

                $collection = $this->productCollections->firstWhere('keyword', $value);
                if ($collection) {
                    $collectionId = $collection['collection_id'];
                } else {
                    if (is_numeric($value)) {
                        $collection = ProductCollection::query()->find($value);
                    } else {
                        $collection = ProductCollection::query()->where('name', $value)->first();
                    }

                    $collectionId = $collection ? $collection->getKey() : 0;
                    $this->productCollections->push([
                        'keyword' => $value,
                        'collection_id' => $collectionId,
                    ]);
                }
                $collectionIds[] = $collectionId;
            }

            $row['product_collections'] = array_filter($collectionIds);
        }

        return $row;
    }

    protected function setProductLabelsToRow(array $row): array
    {
        if ($row['product_labels']) {
            $productLabels = $row['product_labels'];
            $productLabelIds = [];
            foreach ($productLabels as $value) {
                $value = trim($value);

                $productLabel = $this->productLabels->firstWhere('keyword', $value);
                if ($productLabel) {
                    $productLabelId = $productLabel['product_label_id'];
                } else {
                    if (is_numeric($value)) {
                        $productLabel = ProductLabel::query()->find($value);
                    } else {
                        $productLabel = ProductLabel::query()->where('name', $value)->first();
                    }

                    $productLabelId = $productLabel ? $productLabel->getKey() : 0;
                    $this->productLabels->push([
                        'keyword' => $value,
                        'product_label_id' => $productLabelId,
                    ]);
                }
                $productLabelIds[] = $productLabelId;
            }

            $row['product_labels'] = array_filter($productLabelIds);
        }

        return $row;
    }

    protected function getTaxByKeyword(string|int $keyword): ?Tax
    {
        return $this->allTaxes->filter(function ($item) use ($keyword) {
            if (is_numeric($keyword)) {
                return $item->id == $keyword;
            }

            return $item->title == $keyword;
        })->first();
    }

    protected function setValues(array &$row, array $attributes = []): self
    {
        foreach ($attributes as $attribute) {
            $this->setValue(
                $row,
                Arr::get($attribute, 'key'),
                Arr::get($attribute, 'type', 'array'),
                Arr::get($attribute, 'default'),
                Arr::get($attribute, 'from')
            );
        }

        return $this;
    }

    protected function setValue(array &$row, string $key, string $type = 'array', $default = null, $from = null): self
    {
        $value = Arr::get($row, $from ?: $key, $default);

        switch ($type) {
            case 'array':
                $value = $value ? explode(',', $value) : [];

                if (! is_array($value)) {
                    $value = [];
                }

                break;
            case 'bool':
                if (is_string($value) && (Str::lower($value) == 'false' || $value == '0' || Str::lower($value) == 'no')) {
                    $value = false;
                }
                $value = (bool) $value;

                break;
            case 'datetime':
                if ($value) {
                    if (in_array(gettype($value), ['integer', 'double'])) {
                        $value = $this->transformDate($value);
                    } else {
                        $value = $this->getDate($value);
                    }
                }

                break;

            case 'number':
                $value = is_numeric($value) ? $value : null;

                break;
        }

        Arr::set($row, $key, $value);

        if ($value && $key == 'barcode') {
            if (! $this->updateExisting) {
                if ($barcode = $this->barcodes->firstWhere('value', $value)) {
                    $this->onFailure(
                        $this->currentRow,
                        'Barcode',
                        [
                            __(
                                'Barcode ":value" has been duplicated on row #:row',
                                ['value' => $value, 'row' => Arr::get($barcode, 'row')]
                            ),
                        ],
                        [$value]
                    );
                } else {
                    $this->barcodes->push(['row' => $this->currentRow, 'value' => $value]);
                }
            }
        }

        return $this;
    }

    protected function getImageURLs(array $images): array
    {
        $images = array_values(array_filter($images));

        foreach ($images as $key => $image) {
            $images[$key] = str_replace(RvMedia::getUploadURL() . '/', '', trim($image));

            if (Str::startsWith($images[$key], ['http://', 'https://'])) {
                $images[$key] = $this->uploadImageFromURL($images[$key]);
            }
        }

        return $images;
    }

    protected function uploadImageFromURL(?string $url): ?string
    {
        $result = RvMedia::uploadFromUrl($url, 0, 'products');

        if (! $result['error']) {
            $url = $result['data']->url;
        } else {
            Log::error($result['message']);
        }

        return $url;
    }

    protected function getProductQuery(): Builder
    {
        return Product::query();
    }

    protected function assignProductData(Request $request, Product $product): Product
    {
        return $product;
    }
}
