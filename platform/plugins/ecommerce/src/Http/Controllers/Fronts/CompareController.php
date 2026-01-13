<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;

class CompareController extends BaseController
{
    public function __construct(protected ProductInterface $productRepository)
    {
    }

    public function index()
    {
        $title = __('Compare');

        SeoHelper::setTitle(theme_option('ecommerce_compare_seo_title') ?: $title)
            ->setDescription(theme_option('ecommerce_compare_seo_description'));

        Theme::breadcrumb()
            ->add($title, route('public.compare'));

        $itemIds = collect(Cart::instance('compare')->content())
            ->sortBy([['updated_at', 'desc']])
            ->pluck('id');

        $products = collect();
        $attributeSets = collect();
        if ($itemIds->isNotEmpty()) {
            $productIds = $itemIds->all();

            $products = $this->productRepository
                ->getProductsByIds($productIds, [
                    'take' => 10,
                    'with' => EcommerceHelper::withProductEagerLoadingRelations(),
                ]);

            $attributeSets = collect();

            foreach ($products->load('productAttributeSets.attributes') as $product) {
                $attributeSets = $attributeSets->merge($product->productAttributeSets);
            }
        }

        return Theme::scope(
            'ecommerce.compare',
            compact('products', 'attributeSets'),
            'plugins/ecommerce::themes.compare'
        )->render();
    }

    public function store(int|string $productId)
    {
        $product = Product::query()->findOrFail($productId);

        if ($product->is_variation) {
            $product = $product->original_product;
            $productId = $product->getKey();
        }

        $duplicates = Cart::instance('compare')->search(function ($cartItem) use ($productId) {
            return $cartItem->id == $productId;
        });

        if (! $duplicates->isEmpty()) {
            return $this
                ->httpResponse()
                ->setMessage(__(':product is already in your compare list!', ['product' => $product->name]))
                ->setError();
        }

        Cart::instance('compare')
            ->add($productId, $product->name, 1, $product->front_sale_price)
            ->associate(Product::class);

        return $this
            ->httpResponse()
            ->setMessage(__('Added product :product to compare list successfully!', ['product' => $product->name]))
            ->setData(['count' => Cart::instance('compare')->count()]);
    }

    public function destroy(int|string $productId)
    {
        $product = Product::query()->findOrFail($productId);

        Cart::instance('compare')->search(function ($cartItem, $rowId) use ($productId) {
            if ($cartItem->id == $productId) {
                Cart::instance('compare')->remove($rowId);

                return true;
            }

            return false;
        });

        return $this
            ->httpResponse()
            ->setMessage(__('Removed product :product from compare list successfully!', ['product' => $product->name]))
            ->setData(['count' => Cart::instance('compare')->count()]);
    }
}
