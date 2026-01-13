<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Ecommerce\Models\Order;
use Botble\Media\Facades\RvMedia;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * @mixin Order
 */
class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'status' => $this->status,
            'status_html' => $this->status->toHtml(),
            'customer' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
            ],
            'created_at' => $this->created_at->translatedFormat('Y-m-d\TH:i:sP'),
            'amount' => $this->amount,
            'amount_formatted' => format_price($this->amount),
            'tax_amount' => $this->tax_amount,
            'tax_amount_formatted' => format_price($this->tax_amount),
            'shipping_amount' => $this->shipping_amount,
            'shipping_amount_formatted' => format_price($this->shipping_amount),
            'shipping_method' => $this->shipping_method,
            'shipping_status' => $this->shipment->status ?? null,
            'shipping_status_html' => $this->shipment->status->toHtml() ?? null,
            'shipping_info' => [
                'name' => $this->address->name,
                'phone' => $this->address->phone,
                'email' => $this->address->email,
                'address' => $this->address->address,
                'city' => $this->address->city,
                'state' => $this->address->state,
                'country' => $this->address->country_name,
                'zip_code' => $this->address->zip_code,
            ],
            'billing_info' => $this->whenLoaded('billingAddress', function () {
                return [
                    'name' => $this->billingAddress->name,
                    'phone' => $this->billingAddress->phone,
                    'email' => $this->billingAddress->email,
                    'address' => $this->billingAddress->address,
                    'city' => $this->billingAddress->city,
                    'state' => $this->billingAddress->state,
                    'country' => $this->billingAddress->country_name,
                    'zip_code' => $this->billingAddress->zip_code,
                ];
            }, []),
            'payment_method' => $this->payment->payment_channel ?? null,
            'payment_status' => $this->payment->status ?? null,
            'payment_status_html' => $this->payment->status->toHtml() ?? null,
            'products_count' => $this->products_count,
            'products' => $this->whenLoaded('products', function () {
                // Get all original products info
                $originalProducts = $this->getOrderProducts();

                return $this->products->map(function ($product) use ($originalProducts) {
                    // Find the corresponding original product
                    $originalProduct = $originalProducts->firstWhere('id', $product->product_id);

                    return [
                        'id' => $product->id,
                        'product_id' => $product->product_id,
                        'product_name' => $product->product_name,
                        'product_image' => RvMedia::getImageUrl($product->product_image, 'thumb', false, RvMedia::getDefaultImage()),
                        'product_slug' => $originalProduct?->original_product?->slug,
                        'sku' => Arr::get($product->options, 'sku'),
                        'attributes' => Arr::get($product->options, 'attributes'),
                        'amount' => $product->price,
                        'amount_formatted' => $product->amount_format,
                        'quantity' => $product->qty,
                        'total' => $product->price * $product->qty,
                        'total_formatted' => $product->total_format,
                    ];
                });
            }),
        ];
    }
}
