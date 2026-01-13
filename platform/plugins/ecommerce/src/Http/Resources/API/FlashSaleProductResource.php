<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Ecommerce\Models\Product;

/**
 * @mixin Product
 */
class FlashSaleProductResource extends AvailableProductResource
{
    public function toArray($request): array
    {
        $pivot = $this->pivot;

        $quantity = (int) $pivot->quantity;

        $sold = (int) $pivot->sold;

        return [
            ...parent::toArray($request),
            'price' => $pivot->price,
            'price_formatted' => format_price($pivot->price),
            'quantity' => $quantity,
            'sold' => $sold,
            'sale_count_left' => $quantity - $sold,
            'sale_percent' => $quantity > 0 ? ($sold / $quantity) * 100 : 0,
        ];
    }
}
