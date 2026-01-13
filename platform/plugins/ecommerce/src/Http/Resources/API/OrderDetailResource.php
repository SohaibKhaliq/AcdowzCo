<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Ecommerce\Models\Order;
use Botble\Media\Facades\RvMedia;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Order
 */
class OrderDetailResource extends JsonResource
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
            'payment_proof' => $this->getPaymentProofInfo(),
            'products' => $this->getProductData(),
            'discount_amount' => $this->discount_amount,
            'discount_amount_formatted' => format_price($this->discount_amount),
            'discount_description' => $this->discount_description,
            'coupon_code' => $this->coupon_code,
            'can_be_canceled' => $this->resource->canBeCanceled(),
            'can_confirm_delivery' => $this->shipment->can_confirm_delivery,
            'is_invoice_available' => $this->resource->isInvoiceAvailable(),
            'can_be_returned' => $this->resource->canBeReturned(),
            'invoice_links' => [
                'print' => $this->resource->isInvoiceAvailable()
                    ? route('customer.print-order', $this->resource->id) . '?type=print'
                    : null,
                'download' => $this->resource->isInvoiceAvailable()
                    ? route('customer.print-order', $this->resource->id)
                    : null,
            ],
        ];
    }

    private function getProductData()
    {
        // Get all original products info
        $originalProducts = $this->getOrderProducts();

        return $this->products->map(function ($product) use ($originalProducts) {
            // Find corresponding original product
            $originalProduct = $originalProducts->firstWhere('id', $product->product_id);

            $item = [
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
                'options' => $product->options,
                'product_options' => $product->product_options,
            ];

            // Add variation attributes if product is variation
            if ($originalProduct && $originalProduct->is_variation) {
                $item['variation_attributes'] = get_product_attributes($originalProduct->id)->map(function ($attribute) {
                    return [
                        'attribute_set_title' => $attribute->attribute_set_title,
                        'title' => $attribute->title,
                        'color' => $attribute->color,
                        'image' => $attribute->image,
                    ];
                });
            }

            // Add marketplace store information if available
            if (
                is_plugin_active('marketplace') &&
                ($originalProduct?->original_product?->store?->id)
            ) {
                $item['sold_by'] = [
                    'store_name' => $originalProduct->original_product->store->name,
                    'store_url' => $originalProduct->original_product->store->url,
                ];
            }

            return $item;
        });
    }

    private function getPaymentProofInfo(): array
    {
        if (! $this->proof_file) {
            return [
                'has_proof' => false,
                'file_name' => null,
                'file_size' => null,
                'uploaded_at' => null,
                'download_url' => null,
            ];
        }

        $storage = Storage::disk('local');
        $fileName = null;
        $fileSize = null;

        if ($storage->exists($this->proof_file)) {
            $fileName = basename($this->proof_file);
            $fileSize = $storage->size($this->proof_file);
        }

        return [
            'has_proof' => true,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_size_formatted' => $fileSize ? $this->formatFileSize($fileSize) : null,
            'uploaded_at' => $this->updated_at?->translatedFormat('Y-m-d\TH:i:sP'),
            'download_url' => route('api.ecommerce.orders.download-proof-file', [
                'token' => hash('sha256', $this->proof_file),
                'order_id' => $this->id,
            ]),
        ];
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' bytes';
    }
}
