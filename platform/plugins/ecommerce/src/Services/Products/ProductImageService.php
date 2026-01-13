<?php

namespace Botble\Ecommerce\Services\Products;

use Botble\Ecommerce\Models\Product;
use Botble\Media\Facades\RvMedia;

class ProductImageService
{
    public function getProductImagesWithSizes(Product $product): array
    {
        if ($product->images) {
            $originalImages = $product->images;

            // Check if we should merge variation images with main product images
            if (get_ecommerce_setting('how_to_display_product_variation_images') === 'variation_images_and_main_product_images') {
                $parentImages = is_array($product->original_images)
                    ? $product->original_images
                    : (array) json_decode($product->original_images, true);

                if ($parentImages) {
                    $originalImages = array_merge($originalImages, $parentImages);
                }
            }
        } else {
            // Product has no images, fall back to parent product images
            $originalImages = $product->original_images ?: $product->original_product->images;

            if (! is_array($originalImages)) {
                $originalImages = json_decode($originalImages, true);
            }
        }

        // Generate image list with different sizes
        $imageWithSizes = rv_get_image_list($originalImages, array_unique([
            'origin',
            'thumb',
            ...array_keys(RvMedia::getSizes()),
        ]));

        return [
            'images' => $originalImages,
            'image_with_sizes' => $imageWithSizes,
        ];
    }
}
