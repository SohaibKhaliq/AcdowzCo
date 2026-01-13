<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Ecommerce\Models\OrderReturn;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin OrderReturn
 */
class OrderReturnResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'order_code' => $this->order->code,
            'return_status' => $this->return_status,
            'reason' => $this->reason,
            'customer_id' => $this->user_id,
            'items_count' => $this->items_count,
            'items' => OrderReturnItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'latest_history' => $this->whenLoaded('latestHistory', function () {
                return [
                    'id' => $this->latestHistory->id,
                    'status' => $this->latestHistory->status,
                    'created_at' => $this->latestHistory->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $this->latestHistory->updated_at->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    }
}
