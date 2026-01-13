<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Http\Requests\API\AddWishlistRequest;
use Botble\Ecommerce\Http\Requests\API\DeleteWishlistRequest;
use Botble\Ecommerce\Http\Resources\API\WishlistItemResource;
use Botble\Ecommerce\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class WishlistController extends BaseApiController
{
    /**
     * Get wishlist items
     *
     * @group Wishlist
     * @bodyParam id string required ID of the wishlist. Example: e70c6c88dae8344b03e39bb147eba66a
     *
     * @return JsonResponse
     */
    public function index(string $id)
    {
        $identifier = $id;

        Cart::instance('wishlist')->restore($identifier);

        $wishlistItems = $this->getSimplifiedWishlistItems();

        Cart::instance('wishlist')->storeOrIgnore($identifier);

        return response()->json([
            'id' => $identifier,
            'data' => [
                'count' => Cart::instance('wishlist')->count(),
                'items' => $wishlistItems,
            ],
        ]);
    }

    /**
     * Get wishlist items using WishlistItemResource
     *
     * @return array
     */
    private function getSimplifiedWishlistItems(): array
    {
        $cartItems = Cart::instance('wishlist')->content();

        return WishlistItemResource::collection($cartItems)->resolve();
    }

    /**
     * Add product to wishlist
     *
     * @group Wishlist
     * @param AddWishlistRequest $request
     * @param string|null $id Optional wishlist ID to add product to existing wishlist
     * @return JsonResponse
     * @bodyParam product_id integer required ID of the product. Example: 1
     */
    public function store(AddWishlistRequest $request, ?string $id = null)
    {
        // Use provided wishlist ID or generate a new one
        $identifier = $id ?: (string) Str::uuid();

        Cart::instance('wishlist')->restore($identifier);

        /**
         * @var Product $product
         */
        $product = Product::query()->find($request->input('product_id'));

        if (! $product) {
            return response()->json(['error' => __('Product not found')], 404);
        }

        // Check if the product is already in the wishlist
        $cartItems = Cart::instance('wishlist')->content();
        $existingItem = null;

        foreach ($cartItems as $item) {
            if ($item->id == $product->id) {
                $existingItem = $item;

                break;
            }
        }

        $isAdded = false;

        if ($existingItem) {
            // Product already in wishlist, so we'll remove it
            Cart::instance('wishlist')->remove($existingItem->rowId);
        } else {
            // Add the product to wishlist with image and other options
            $options = [
                'image' => $product->image,
            ];

            // Add store information if marketplace plugin is active
            if (is_plugin_active('marketplace')) {
                if ($store = $product->original_product->store) {
                    $options['store'] = [
                        'id' => $store?->id,
                        'slug' => $store?->slugable?->key,
                        'name' => $store?->name,
                    ];
                }
            }

            Cart::instance('wishlist')
                ->add($product->id, $product->name, 1, $product->price, $options)
                ->associate(Product::class);
            $isAdded = true;
        }

        Cart::instance('wishlist')->store($identifier);

        $wishlistItems = $this->getSimplifiedWishlistItems();

        return response()->json([
            'id' => $identifier,
            'message' => $isAdded
                ? __('Added product :product successfully!', ['product' => $product->name])
                : __('Removed product :product from wishlist successfully!', ['product' => $product->name]),
            'data' => [
                'count' => Cart::instance('wishlist')->count(),
                'added' => $isAdded,
                'items' => $wishlistItems,
            ],
        ]);
    }

    /**
     * Remove a product from wishlist
     *
     * @group Wishlist
     *
     * @param DeleteWishlistRequest $request
     *
     * @param string $id The ID of the wishlist
     * @return JsonResponse Returns a JSON response with the operation status
     */
    public function destroy(DeleteWishlistRequest $request, string $id)
    {
        $identifier = $id;

        $productId = $request->input('product_id');

        Cart::instance('wishlist')->restore($identifier);

        /**
         * @var Product $product
         */
        $product = Product::query()->find($productId);

        if (! $product) {
            return response()->json(['error' => __('Product not found')], 404);
        }

        // Find the cart item with the matching product ID
        $cartItems = Cart::instance('wishlist')->content();
        $rowId = null;

        foreach ($cartItems as $item) {
            if ($item->id == $productId) {
                $rowId = $item->rowId;

                break;
            }
        }

        if ($rowId) {
            // Remove the item from the cart
            Cart::instance('wishlist')->remove($rowId);
        } else {
            return response()->json(['error' => __('Product not found in wishlist')], 404);
        }

        Cart::instance('wishlist')->store($identifier);

        $wishlistItems = $this->getSimplifiedWishlistItems();

        return response()->json([
            'id' => $identifier,
            'message' => __('Removed product :product from wishlist successfully!', ['product' => $product->name]),
            'data' => [
                'count' => Cart::instance('wishlist')->count(),
                'items' => $wishlistItems,
            ],
        ]);
    }
}
