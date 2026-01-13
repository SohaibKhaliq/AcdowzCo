<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Ecommerce\Models\OrderReturnItem;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin OrderReturnItem
 */
class OrderReturnItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_return_id' => $this->order_return_id,
            'order_product_id' => $this->order_product_id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'product_image' => $this->product_image,
            'price' => $this->price,
            'qty' => $this->qty,
            'refund_amount' => $this->refund_amount,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
