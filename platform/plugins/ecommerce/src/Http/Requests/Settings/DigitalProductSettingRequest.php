<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class DigitalProductSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'is_enabled_support_digital_products' => $onOffRule = new OnOffRule(),
            'allow_guest_checkout_for_digital_products' => $onOffRule,
            'enable_filter_products_by_brands' => $onOffRule,
            'enable_filter_products_by_tags' => $onOffRule,
            'enable_filter_products_by_attributes' => $onOffRule,
            'disable_physical_product' => $onOffRule,
            'enable_license_codes_for_digital_products' => $onOffRule,
            'auto_complete_digital_orders_after_payment' => $onOffRule,
            'hide_used_license_codes_in_product_form' => $onOffRule,
        ];
    }
}
