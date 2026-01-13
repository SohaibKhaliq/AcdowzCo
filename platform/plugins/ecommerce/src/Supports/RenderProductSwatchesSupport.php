<?php

namespace Botble\Ecommerce\Supports;

use Botble\Ecommerce\Facades\EcommerceHelper as EcommerceHelperFacade;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductVariation;
use Botble\Ecommerce\Models\ProductVariationItem;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class RenderProductSwatchesSupport
{
    protected Product $product;

    public function __construct(protected ProductInterface $productRepository)
    {
    }

    public function setProduct(Product $product): RenderProductSwatchesSupport
    {
        $this->product = $product;

        return $this;
    }

    public function render(array $params = []): string
    {
        $params = array_merge([
            'selected' => [],
            'view' => EcommerceHelperFacade::viewPath('attributes.swatches-renderer'),
        ], $params);

        $product = $this->product;
        $cacheKey = 'product_variations_' . $product->getKey() . '_' . md5(json_encode($params['selected']));

        $data = Cache::remember($cacheKey, 300, function () use ($product) {
            $attributeSets = $product->productAttributeSets()->oldest('order')->latest()->get();
            $attributes = $this->productRepository->getRelatedProductAttributes($this->product)->sortBy('order');

            $productVariations = ProductVariation::query()
                ->where('configurable_product_id', $product->getKey())
                ->with(['product:id,stock_status,quantity,with_storehouse_management,allow_checkout_when_out_of_stock,images'])
                ->select('id', 'product_id', 'configurable_product_id', 'is_default')
                ->get();

            $variationIds = $productVariations->pluck('id')->all();
            $productVariationsInfo = collect();

            foreach (array_chunk($variationIds, 100) as $chunk) {
                $productVariationsInfo = $productVariationsInfo->merge(
                    ProductVariationItem::getVariationsInfo($chunk)
                );
            }

            if ($productVariationsInfo->isNotEmpty()) {
                $outOfStockProductIds = $productVariations
                    ->filter(function ($variation) {
                        return $variation->product && $variation->product->isOutOfStock();
                    })
                    ->pluck('id')
                    ->toArray();

                $productVariationsInfo = $productVariationsInfo
                    ->reject(function ($item) use ($outOfStockProductIds) {
                        return in_array($item->variation_id, $outOfStockProductIds);
                    });
            }

            return compact('attributeSets', 'attributes', 'productVariations', 'productVariationsInfo');
        });

        extract($data);

        $selected = $params['selected'];

        if (is_array($selected)) {
            $selected = collect($selected);
        }

        return view(
            $params['view'],
            [
                ...compact(
                    'attributeSets',
                    'attributes',
                    'product',
                    'selected',
                    'productVariationsInfo',
                    'productVariations'
                ),
                ...Arr::except($params, ['view', 'selected']),
            ]
        )->render();
    }
}
