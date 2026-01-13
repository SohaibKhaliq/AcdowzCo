<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\SharedWishlist;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Services\ProductWishlistService;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class WishlistController extends BaseController
{
    public function index(Request $request, ProductInterface $productRepository, ?string $code = null)
    {
        abort_if($code && ! EcommerceHelper::isWishlistSharingEnabled(), 404);

        $title = __('Wishlist');

        SeoHelper::setTitle(theme_option('ecommerce_wishlist_seo_title') ?: $title)
            ->setDescription(theme_option('ecommerce_wishlist_seo_description'));

        Theme::breadcrumb()->add($title, route('public.wishlist'));

        $queryParams = [
            'paginate' => [
                'per_page' => 100,
                'current_paged' => $request->integer('page', 1) ?: 1,
            ],
            'with' => ['slugable'],
        ];

        if ($code && EcommerceHelper::isWishlistSharingEnabled()) {
            $sharedWishlist = SharedWishlist::query()->where('code', $code)->firstOrFail();

            $products = $productRepository->getProductsByIds($sharedWishlist->product_ids, $queryParams);
        } else {
            if (auth('customer')->check()) {
                $products = $productRepository->getProductsWishlist(auth('customer')->id(), $queryParams);
            } else {
                $products = new LengthAwarePaginator([], 0, 10);

                $itemIds = Cart::instance('wishlist')
                    ->content()
                    ->sortBy([['updated_at', 'desc']])
                    ->pluck('id')
                    ->unique()
                    ->all();

                if ($itemIds) {
                    $products = $productRepository->getProductsByIds($itemIds, $queryParams);
                }
            }
        }

        $canRemoveWishlist = ! $code || (EcommerceHelper::getWishlistCode() === $code);

        return Theme::scope('ecommerce.wishlist', compact('products', 'canRemoveWishlist'), 'plugins/ecommerce::themes.wishlist')->render();
    }

    public function store(int|string $productId, Request $request)
    {
        if (! $productId) {
            $productId = $request->input('product_id');
        }

        if (! $productId) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('This product is not available.'));
        }

        /**
         * @var Product $product
         */
        $product = Product::query()->findOrFail($productId);

        $isAdded = app(ProductWishlistService::class)->handle($product);

        return $this
            ->httpResponse()
            ->setMessage(
                $isAdded
                ? __('Added product :product successfully!', ['product' => $product->name])
                : __('Removed product :product from wishlist successfully!', ['product' => $product->name])
            )
            ->setData([
                'count' => $this->wishlistCount(),
                'added' => $isAdded,
            ]);
    }

    public function destroy(int|string $productId)
    {
        /**
         * @var Product $product
         */
        $product = Product::query()->findOrFail($productId);

        app(ProductWishlistService::class)->handle($product);

        return $this
            ->httpResponse()
            ->setMessage(__('Removed product :product from wishlist successfully!', ['product' => $product->name]))
            ->setData(['count' => $this->wishlistCount()]);
    }

    protected function wishlistCount(): int
    {
        if (! auth('customer')->check()) {
            return Cart::instance('wishlist')->count();
        }

        return auth('customer')->user()->wishlist()->count();
    }
}
