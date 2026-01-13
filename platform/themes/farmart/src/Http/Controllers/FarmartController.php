<?php

namespace Theme\Farmart\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Marketplace\Models\Store;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Http\Controllers\PublicController;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Theme\Farmart\Http\Requests\ContactSellerRequest;
use Theme\Farmart\Supports\Wishlist;

class FarmartController extends PublicController
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (! $request->ajax()) {
                return $this->httpResponse()->setNextUrl(BaseHelper::getHomepageUrl());
            }

            return $next($request);
        })->only([
            'ajaxCart',
            'ajaxAddProductToWishlist',
            'ajaxSearchProducts',
            'ajaxGetRecentlyViewedProducts',
            'ajaxContactSeller',
            'ajaxGetProductsByCollection',
            'ajaxGetProductsByCategory',
        ]);
    }

    public function ajaxCart()
    {
        return $this->httpResponse()->setData([
            'count' => Cart::instance('cart')->count(),
            'total_price' => format_price(Cart::instance('cart')->rawSubTotal() + Cart::instance('cart')->rawTax()),
            'html' => Theme::partial('cart-mini.list'),
        ]);
    }

    public function ajaxGetRecentlyViewedProducts(ProductInterface $productRepository)
    {
        abort_unless(EcommerceHelper::isEnabledCustomerRecentlyViewedProducts(), 404);

        $queryParams = [
            'with' => ['slugable'],
            'take' => 12,
        ] + EcommerceHelper::withReviewsParams();

        if (auth('customer')->check()) {
            $products = $productRepository->getProductsRecentlyViewed(auth('customer')->id(), $queryParams);
        } else {
            $products = collect();

            $itemIds = collect(Cart::instance('recently_viewed')->content())
                ->sortBy([['updated_at', 'desc']])
                ->take(12)
                ->pluck('id')
                ->all();

            if ($itemIds) {
                $products = $productRepository->getProductsByIds($itemIds, $queryParams);
            }
        }

        return $this->httpResponse()
            ->setData(Theme::partial('ecommerce.recently-viewed-products', compact('products')));
    }

    public function ajaxContactSeller(ContactSellerRequest $request, BaseHttpResponse $response)
    {
        $store = Store::query()->findOrFail($request->input('store_id'));

        EmailHandler::setModule(Theme::getThemeName())
            ->setVariableValues([
                'contact_message' => $request->input('content'),
                'customer_name' => $request->input('name'),
                'customer_email' => $request->input('email'),
                'store_name' => $store->name,
                'store_phone' => $store->phone,
                'store_address' => $store->full_address,
                'store_link' => $store->url,
                'store' => $store->toArray(),
            ])
            ->sendUsingTemplate('contact-seller', $store->email, [], false, 'themes');

        return $response->setMessage(__('Send message successfully!'));
    }

    public function ajaxGetProductsByCollection(int|string $id, Request $request, BaseHttpResponse $response)
    {
        if (! $request->expectsJson()) {
            return $response->setNextUrl(BaseHelper::getHomepageUrl());
        }

        $products = get_products_by_collections(array_merge([
            'collections' => [
                'by' => 'id',
                'value_in' => [$id],
            ],
            'take' => $request->integer('limit') ?: 8,
            'with' => EcommerceHelper::withProductEagerLoadingRelations(),
        ], EcommerceHelper::withReviewsParams()));

        $wishlistIds = Wishlist::getWishlistIds($products->pluck('id')->all());

        $data = [];
        foreach ($products as $product) {
            $data[] = '<div class="product-inner">' . Theme::partial('ecommerce.product-item', compact('product', 'wishlistIds')) . '</div>';
        }

        return $response->setData($data);
    }

    public function ajaxGetProductsByCategory(
        int|string $id,
        Request $request,
        BaseHttpResponse $response,
        ProductInterface $productRepository
    ) {
        if (! $request->expectsJson()) {
            return $response->setNextUrl(BaseHelper::getHomepageUrl());
        }

        $category = ProductCategory::query()
            ->where('id', $id)
            ->wherePublished()
            ->with([
                'activeChildren' => function (HasMany $query) {
                    return $query->limit(3);
                },
            ])
            ->first();

        if (! $category) {
            return $response->setData([]);
        }

        $products = $productRepository->getProductsByCategories(array_merge([
            'categories' => [
                'by' => 'id',
                'value_in' => array_merge([$category->id], $category->activeChildren->pluck('id')->all()),
            ],
            'take' => $request->integer('limit', 8) ?: 8,
        ], EcommerceHelper::withReviewsParams()));

        $wishlistIds = Wishlist::getWishlistIds($products->pluck('id')->all());

        $data = [];
        foreach ($products as $product) {
            $data[] = '<div class="product-inner">' . Theme::partial('ecommerce.product-item', compact('product', 'wishlistIds')) . '</div>';
        }

        return $response->setData($data);
    }
}
