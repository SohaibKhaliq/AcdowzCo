<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Ecommerce\Models\Discount;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Discount
 */
class CouponResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'description' => $this->description,
            'value' => $this->value,
            'type_option' => $this->type_option,
            'target' => $this->target,
            'min_order_price' => $this->min_order_price,
            'min_order_price_formatted' => format_price($this->min_order_price),
            'start_date' => $this->start_date?->toISOString(),
            'end_date' => $this->end_date?->toISOString(),
            'quantity' => $this->quantity,
            'total_used' => $this->total_used,
            'left_quantity' => $this->left_quantity,
            'can_use_with_promotion' => $this->can_use_with_promotion,
            'can_use_with_flash_sale' => $this->can_use_with_flash_sale,
            'apply_via_url' => $this->apply_via_url,
            'display_at_checkout' => $this->display_at_checkout,
            'is_expired' => $this->isExpired(),
        ];
    }
}
