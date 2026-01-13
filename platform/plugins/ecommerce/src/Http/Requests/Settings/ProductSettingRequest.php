<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class ProductSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'how_to_display_product_variation_images' => ['in:only_variation_images,variation_images_and_main_product_images'],
            'show_number_of_products' => $onOffRule = new OnOffRule(),
            'show_out_of_stock_products' => $onOffRule,
            'is_enabled_product_options' => $onOffRule,
            'is_enabled_related_products' => $onOffRule,
            'related_products_source' => ['nullable', 'in:category,brand'],
            'trending_products_period_days' => ['nullable', 'in:1,3,7,14,30,60,90'],
            'is_enabled_cross_sale_products' => $onOffRule,
            'auto_generate_product_sku' => $onOffRule,
            'enable_product_specification' => $onOffRule,
            'product_sku_format' => ['nullable', 'string', 'max:120'],
            'make_product_barcode_required' => $onOffRule,
        ];
    }
}
