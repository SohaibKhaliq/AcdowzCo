<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Http\Requests\API\AddCompareRequest;
use Botble\Ecommerce\Http\Requests\API\DeleteCompareRequest;
use Botble\Ecommerce\Http\Resources\API\CompareItemResource;
use Botble\Ecommerce\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CompareController extends BaseApiController
{
    /**
     * Get compare items
     *
     * @group Compare
     * @bodyParam id string required ID of the compare list. Example: e70c6c88dae8344b03e39bb147eba66a
     *
     * @return JsonResponse
     */
    public function index(string $id)
    {
        $identifier = $id;

        Cart::instance('compare')->restore($identifier);

        $compareItems = $this->getCompareItems();

        Cart::instance('compare')->storeOrIgnore($identifier);

        return response()->json([
            'id' => $identifier,
            'data' => [
                'count' => Cart::instance('compare')->count(),
                'items' => $compareItems,
            ],
        ]);
    }

    /**
     * Get compare items
     *
     * @return array
     */
    private function getCompareItems(): array
    {
        $cartItems = Cart::instance('compare')->content();

        return CompareItemResource::collection($cartItems)->resolve();
    }

    /**
     * Add product to compare
     *
     * @group Compare
     * @param AddCompareRequest $request
     * @param string|null $id Optional compare ID to add product to existing compare list
     * @return JsonResponse
     * @bodyParam product_id integer required ID of the product. Example: 1
     */
    public function store(AddCompareRequest $request, ?string $id = null)
    {
        // Use provided compare ID or generate a new one
        $identifier = $id ?: (string) Str::uuid();

        Cart::instance('compare')->restore($identifier);

        /**
         * @var Product $product
         */
        $product = Product::query()->find($request->input('product_id'));

        if (! $product) {
            return response()->json(['error' => __('Product not found')], 404);
        }

        // Check if the product is already in the compare list
        $cartItems = Cart::instance('compare')->content();
        $existingItem = null;

        foreach ($cartItems as $item) {
            if ($item->id == $product->id) {
                $existingItem = $item;

                break;
            }
        }

        $isAdded = false;

        if ($existingItem) {
            // Product already in compare list, so we'll remove it
            Cart::instance('compare')->remove($existingItem->rowId);
        } else {
            // Add the product to compare list with image and other options
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

            Cart::instance('compare')
                ->add($product->id, $product->name, 1, $product->price, $options)
                ->associate(Product::class);
            $isAdded = true;
        }

        Cart::instance('compare')->store($identifier);

        $compareItems = $this->getCompareItems();

        return response()->json([
            'id' => $identifier,
            'message' => $isAdded
                ? __('Added product :product to compare list successfully!', ['product' => $product->name])
                : __('Removed product :product from compare list successfully!', ['product' => $product->name]),
            'data' => [
                'count' => Cart::instance('compare')->count(),
                'added' => $isAdded,
                'items' => $compareItems,
            ],
        ]);
    }

    /**
     * Remove a product from compare list
     *
     * @group Compare
     *
     * @param DeleteCompareRequest $request
     *
     * @param string $id The ID of the compare list
     * @return JsonResponse Returns a JSON response with the operation status
     */
    public function destroy(DeleteCompareRequest $request, string $id)
    {
        $identifier = $id;

        $productId = $request->input('product_id');

        Cart::instance('compare')->restore($identifier);

        /**
         * @var Product $product
         */
        $product = Product::query()->find($productId);

        if (! $product) {
            return response()->json(['error' => __('Product not found')], 404);
        }

        // Find the cart item with the matching product ID
        $cartItems = Cart::instance('compare')->content();
        $rowId = null;

        foreach ($cartItems as $item) {
            if ($item->id == $productId) {
                $rowId = $item->rowId;

                break;
            }
        }

        if ($rowId) {
            // Remove the item from the cart
            Cart::instance('compare')->remove($rowId);
        } else {
            return response()->json(['error' => __('Product not found in compare list')], 404);
        }

        Cart::instance('compare')->store($identifier);

        $compareItems = $this->getCompareItems();

        return response()->json([
            'id' => $identifier,
            'message' => __('Removed product :product from compare list successfully!', ['product' => $product->name]),
            'data' => [
                'count' => Cart::instance('compare')->count(),
                'items' => $compareItems,
            ],
        ]);
    }
}
