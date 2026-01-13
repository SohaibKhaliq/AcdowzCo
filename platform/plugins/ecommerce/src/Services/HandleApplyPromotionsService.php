<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Enums\DiscountTargetEnum;
use Botble\Ecommerce\Enums\DiscountTypeOptionEnum;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\Discount;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Models\Discount as DiscountModel;
use Illuminate\Support\Arr;

class HandleApplyPromotionsService
{
    protected PromotionCacheService $cacheService;

    public function __construct()
    {
        $this->cacheService = new PromotionCacheService();
    }

    public function execute($token = null, array $data = [], ?string $prefix = ''): float|int
    {
        $promotionDiscountAmount = $this->getPromotionDiscountAmount($data);

        if (! $token) {
            $token = OrderHelper::getOrderSessionToken();
        }

        $sessionData = OrderHelper::getOrderSessionData($token);
        Arr::set($sessionData, $prefix . 'promotion_discount_amount', $promotionDiscountAmount);
        OrderHelper::setOrderSessionData($token, $sessionData);

        return $promotionDiscountAmount;
    }

    public function getPromotionDiscountAmount(array $data = [])
    {
        $cacheKey = $this->cacheService->getCacheKey($data);

        return $this->cacheService->remember($cacheKey, function () use ($data) {
            $promotionDiscountAmount = 0;

            $cartInstance = Cart::instance('cart');

            $rawTotal = Arr::get($data, 'rawTotal', $cartInstance->rawTotal());
            $cartItems = Arr::get($data, 'cartItems', $cartInstance->content());
            $countCart = Arr::get($data, 'countCart', $cartInstance->count());
            $productItems = Arr::get($data, 'productItems', $cartInstance->products());

            $promotionsCacheKey = 'available_promotions_' . md5(serialize([
                'customer_id' => auth('customer')->id(),
                'date' => now()->format('Y-m-d'),
            ]));

            $availablePromotions = $this->cacheService->remember($promotionsCacheKey, function () {
                return Discount::getAvailablePromotions(false)
                    ->reject(fn (DiscountModel $item) => in_array($item->target, [
                        DiscountTargetEnum::SPECIFIC_PRODUCT,
                        DiscountTargetEnum::PRODUCT_VARIANT,
                    ]) || ($item->product_quantity <= 1 && $item->target !== DiscountTargetEnum::MINIMUM_ORDER_AMOUNT));
            }, 300);

            $productPromotionsCacheKey = 'product_promotions_' . md5(serialize([
                'product_ids' => $productItems->pluck('id')->toArray(),
                'customer_id' => auth('customer')->id(),
            ]));

            $productPromotions = $this->cacheService->remember($productPromotionsCacheKey, function () use ($productItems) {
                $productPromotions = [];
                foreach ($productItems as $product) {
                    $promotion = Discount::promotionForProduct([$product->id]);
                    if ($promotion) {
                        $productPromotions[$product->id] = $promotion;
                    }
                }

                return $productPromotions;
            }, 300);

            foreach ($productPromotions as $promotion) {
                if ($promotion && $promotion->product_quantity > 1 && $availablePromotions->doesntContain($promotion)) {
                    $availablePromotions = $availablePromotions->push($promotion);
                }
            }

            $cartProductIds = $cartItems->pluck('id')->all();
            $productsWithRelations = null;
            $promotionProductIds = [];
            $promotionCollectionIds = [];
            $promotionCategoryIds = [];
            $customerPromotions = [];
            $customerId = auth('customer')->id();

            foreach ($availablePromotions as $promotion) {
                if (in_array($promotion->target, [DiscountTargetEnum::SPECIFIC_PRODUCT, DiscountTargetEnum::PRODUCT_VARIANT])) {
                    $promotionProductIds[$promotion->id] = $promotion->products()->pluck('product_id')->all();
                } elseif ($promotion->target === DiscountTargetEnum::PRODUCT_COLLECTIONS) {
                    $promotionCollectionIds[$promotion->id] = $promotion->productCollections()->pluck('id')->all();
                } elseif ($promotion->target === DiscountTargetEnum::PRODUCT_CATEGORIES) {
                    $promotionCategoryIds[$promotion->id] = $promotion->productCategories()->pluck('id')->all();
                } elseif (in_array($promotion->target, [DiscountTargetEnum::CUSTOMER, DiscountTargetEnum::ONCE_PER_CUSTOMER]) && $customerId) {
                    $customerPromotions[$promotion->id] = $promotion->customers()->where('customer_id', $customerId)->exists();
                }
            }

            $needsProductRelations = $availablePromotions->whereIn('target', [
                DiscountTargetEnum::PRODUCT_COLLECTIONS,
                DiscountTargetEnum::PRODUCT_CATEGORIES,
                DiscountTargetEnum::CUSTOMER,
                DiscountTargetEnum::ONCE_PER_CUSTOMER,
            ])->isNotEmpty();

            if ($needsProductRelations && ! empty($cartProductIds)) {
                $productsWithRelations = get_products([
                    'condition' => [
                        ['ec_products.id', 'IN', $cartProductIds],
                    ],
                    'with' => ['productCollections', 'categories'],
                ])->keyBy('id');
            }

            foreach ($availablePromotions as $promotion) {
                switch ($promotion->type_option) {
                    case DiscountTypeOptionEnum::AMOUNT:
                        switch ($promotion->target) {
                            case DiscountTargetEnum::MINIMUM_ORDER_AMOUNT:
                                if ($promotion->min_order_price <= $rawTotal) {
                                    $promotionDiscountAmount += $promotion->value;
                                }

                                break;
                            case DiscountTargetEnum::ALL_ORDERS:
                                $promotionDiscountAmount += $promotion->value;

                                break;
                            case DiscountTargetEnum::SPECIFIC_PRODUCT:
                            case DiscountTargetEnum::PRODUCT_VARIANT:
                                $productIdsForPromotion = $promotionProductIds[$promotion->id] ?? [];
                                foreach ($cartItems as $item) {
                                    if (
                                        $item->qty >= $promotion->product_quantity &&
                                        in_array($item->id, $productIdsForPromotion)
                                    ) {
                                        $promotionDiscountAmount += $promotion->value;
                                    }
                                }

                                break;
                            case DiscountTargetEnum::PRODUCT_COLLECTIONS:
                                if (! $productsWithRelations) {
                                    break;
                                }

                                $collectionIdsForPromotion = $promotionCollectionIds[$promotion->id] ?? [];
                                foreach ($cartItems as $item) {
                                    $product = $productsWithRelations->get($item->id);

                                    if (! $product) {
                                        continue;
                                    }

                                    $productCollectionIds = $product->original_product->productCollections->pluck('id')->all();
                                    if (
                                        $item->qty >= $promotion->product_quantity &&
                                        array_intersect($productCollectionIds, $collectionIdsForPromotion)
                                    ) {
                                        $promotionDiscountAmount += $promotion->value;
                                    }
                                }

                                break;
                            case DiscountTargetEnum::PRODUCT_CATEGORIES:
                                if (! $productsWithRelations) {
                                    break;
                                }

                                $categoryIdsForPromotion = $promotionCategoryIds[$promotion->id] ?? [];
                                foreach ($cartItems as $item) {
                                    $product = $productsWithRelations->get($item->id);

                                    if (! $product) {
                                        continue;
                                    }

                                    $productCategoryIds = $product->original_product->categories->pluck('id')->all();
                                    if (
                                        $item->qty >= $promotion->product_quantity &&
                                        array_intersect($productCategoryIds, $categoryIdsForPromotion)
                                    ) {
                                        $promotionDiscountAmount += $promotion->value;
                                    }
                                }

                                break;
                            case DiscountTargetEnum::CUSTOMER:
                            case DiscountTargetEnum::ONCE_PER_CUSTOMER:
                                if (! $customerId || ! ($customerPromotions[$promotion->id] ?? false)) {
                                    break;
                                }

                                foreach ($cartItems as $item) {
                                    if ($item->qty >= $promotion->product_quantity) {
                                        $promotionDiscountAmount += $promotion->value;
                                    }
                                }

                                break;
                            default:
                                if ($countCart >= $promotion->product_quantity) {
                                    $promotionDiscountAmount += $promotion->value;
                                }

                                break;
                        }

                        break;
                    case DiscountTypeOptionEnum::PERCENTAGE:
                        switch ($promotion->target) {
                            case DiscountTargetEnum::MINIMUM_ORDER_AMOUNT:
                                if ($promotion->min_order_price <= $rawTotal) {
                                    $promotionDiscountAmount += $rawTotal * $promotion->value / 100;
                                }

                                break;
                            case DiscountTargetEnum::ALL_ORDERS:
                                $promotionDiscountAmount += $rawTotal * $promotion->value / 100;

                                break;
                            case DiscountTargetEnum::SPECIFIC_PRODUCT:
                            case DiscountTargetEnum::PRODUCT_VARIANT:
                                $productIdsForPromotion = $promotionProductIds[$promotion->id] ?? [];
                                foreach ($cartItems as $item) {
                                    if (
                                        $item->qty >= $promotion->product_quantity &&
                                        in_array($item->id, $productIdsForPromotion)
                                    ) {
                                        $promotionDiscountAmount += $item->price * $promotion->value / 100;
                                    }
                                }

                                break;
                            case DiscountTargetEnum::PRODUCT_COLLECTIONS:
                                if (! $productsWithRelations) {
                                    break;
                                }

                                $collectionIdsForPromotion = $promotionCollectionIds[$promotion->id] ?? [];
                                foreach ($cartItems as $item) {
                                    $product = $productsWithRelations->get($item->id);

                                    if (! $product) {
                                        continue;
                                    }

                                    $productCollectionIds = $product->original_product->productCollections->pluck('id')->all();
                                    if (
                                        $item->qty >= $promotion->product_quantity &&
                                        array_intersect($productCollectionIds, $collectionIdsForPromotion)
                                    ) {
                                        $promotionDiscountAmount += $item->price * $promotion->value / 100;
                                    }
                                }

                                break;
                            case DiscountTargetEnum::PRODUCT_CATEGORIES:
                                if (! $productsWithRelations) {
                                    break;
                                }

                                $categoryIdsForPromotion = $promotionCategoryIds[$promotion->id] ?? [];
                                foreach ($cartItems as $item) {
                                    $product = $productsWithRelations->get($item->id);

                                    if (! $product) {
                                        continue;
                                    }

                                    $productCategoryIds = $product->original_product->categories->pluck('id')->all();
                                    if (
                                        $item->qty >= $promotion->product_quantity &&
                                        array_intersect($productCategoryIds, $categoryIdsForPromotion)
                                    ) {
                                        $promotionDiscountAmount += $item->price * $promotion->value / 100;
                                    }
                                }

                                break;
                            case DiscountTargetEnum::CUSTOMER:
                            case DiscountTargetEnum::ONCE_PER_CUSTOMER:
                                if (! $customerId || ! ($customerPromotions[$promotion->id] ?? false)) {
                                    break;
                                }

                                foreach ($cartItems as $item) {
                                    if ($item->qty >= $promotion->product_quantity) {
                                        $promotionDiscountAmount += $rawTotal * $promotion->value / 100;
                                    }
                                }

                                break;

                            default:
                                if ($countCart >= $promotion->product_quantity) {
                                    $promotionDiscountAmount += $rawTotal * $promotion->value / 100;
                                }

                                break;
                        }

                        break;
                    case DiscountTypeOptionEnum::SAME_PRICE:
                        if ($promotion->product_quantity > 1 && $countCart >= $promotion->product_quantity) {
                            $productIdsForPromotion = $promotionProductIds[$promotion->id] ?? [];
                            $collectionIdsForPromotion = $promotionCollectionIds[$promotion->id] ?? [];

                            foreach ($cartItems as $item) {
                                if ($item->qty < $promotion->product_quantity) {
                                    continue;
                                }

                                if (in_array($promotion->target, [
                                        DiscountTargetEnum::SPECIFIC_PRODUCT,
                                        DiscountTargetEnum::PRODUCT_VARIANT,
                                    ]) &&
                                    in_array($item->id, $productIdsForPromotion)
                                ) {
                                    $promotionDiscountAmount += ($item->price - $promotion->value) * $item->qty;

                                    continue;
                                }

                                if ($product = $productItems->firstWhere('id', $item->id)) {
                                    $productCollectionIds = $product->original_product->productCollections->pluck('id')->all();

                                    if (
                                        ! empty(array_intersect($productCollectionIds, $collectionIdsForPromotion)) &&
                                        $item->price > $promotion->value
                                    ) {
                                        $promotionDiscountAmount += ($item->price - $promotion->value) * $item->qty;
                                    }
                                }
                            }
                        }

                        break;
                }
            }

            return $promotionDiscountAmount;
        });
    }
}
