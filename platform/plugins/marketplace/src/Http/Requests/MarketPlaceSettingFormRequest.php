<?php

namespace Botble\Marketplace\Http\Requests;

use Botble\Base\Rules\OnOffRule;
use Botble\Ecommerce\Http\Requests\ProductRequest as BaseProductRequest;
use Botble\Marketplace\Enums\PayoutPaymentMethodsEnum;
use Botble\Marketplace\Enums\WithdrawalFeeTypeEnum;
use Botble\Media\Facades\RvMedia;
use Illuminate\Validation\Rule;

class MarketPlaceSettingFormRequest extends BaseProductRequest
{
    protected function prepareForValidation(): void
    {
        if (! array_filter($this->input('payout_methods'))) {
            $this->merge([
                'payout_methods' => [],
            ]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'payout_methods' => 'required|array:' . implode(',', PayoutPaymentMethodsEnum::values()),
            'payout_methods.*' => 'sometimes|in:0,1',
            'enable_commission_fee_for_each_category' => 'sometimes|in:0,1',
            'check_valid_signature' => 'sometimes|in:0,1',
            'verify_vendor' => 'sometimes|in:0,1',
            'show_vendor_registration_form_at_registration_page' => 'sometimes|in:0,1',
            'requires_vendor_documentations_verification' => 'sometimes|in:0,1',
            'hide_become_vendor_menu_in_customer_dashboard' => 'sometimes|in:0,1',
            'enable_product_approval' => 'sometimes|in:0,1',
            'hide_store_phone_number' => 'sometimes|in:0,1',
            'hide_store_email' => 'sometimes|in:0,1',
            'hide_store_address' => 'sometimes|in:0,1',
            'allow_vendor_manage_shipping' => 'sometimes|in:0,1',
            'charge_shipping_per_vendor' => 'sometimes|in:0,1',
            'hide_store_social_links' => 'sometimes|in:0,1',
            'enable_vendor_categories_filter' => [new OnOffRule()],
            'fee_per_order' => 'sometimes|min:0|max:100|numeric',
            'fee_withdrawal' => 'sometimes|min:0|numeric',
            'withdrawal_fee_type' => ['required', Rule::in(WithdrawalFeeTypeEnum::values())],
            'max_filesize_upload_by_vendor' => 'sometimes|min:1|numeric',
            'max_product_images_upload_by_vendor' => 'sometimes|min:1|numeric',
            'enabled_vendor_registration' => [new OnOffRule()],
            'minimum_withdrawal_amount' => 'nullable|numeric|min:0',
            'allow_vendor_delete_their_orders' => [new OnOffRule()],
            'enabled_messaging_system' => [new OnOffRule()],
            'term_and_privacy_policy_url' => ['nullable', 'string'],
            'single_vendor_checkout' => [new OnOffRule()],
            'display_order_total_info_for_each_store' => [new OnOffRule()],
            'show_vendor_info_at_checkout' => [new OnOffRule()],
            'media_mime_types_allowed' => ['nullable', 'array'],
            'media_mime_types_allowed.*' => ['nullable', 'string', Rule::in(explode(',', RvMedia::getConfig('allowed_mime_types')))],
        ];

        if ($this->input('enable_commission_fee_for_each_category')) {
            // validate request setting category commission
            $commissionByCategory = $this->input('commission_by_category');
            foreach ($commissionByCategory as $key => $item) {
                $commissionFeeName = sprintf('%s.%s.commission_fee', 'commission_by_category', $key);
                $categoryName = sprintf('%s.%s.categories', 'commission_by_category', $key);
                $rules[$commissionFeeName] = 'required|numeric|min:1,max:100';
                $rules[$categoryName] = 'required';
            }
        }

        return $rules;
    }

    public function attributes(): array
    {
        $attributes = [];

        if ($this->input('enable_commission_fee_for_each_category') == 1) {
            // validate request setting category commission
            $commissionByCategory = $this->input('commission_by_category');
            foreach ($commissionByCategory as $key => $item) {
                $commissionFeeName = sprintf('%s.%s.commission_fee', 'commission_by_category', $key);
                $categoryName = sprintf('%s.%s.categories', 'commission_by_category', $key);
                $attributes[$commissionFeeName] = trans('plugins/marketplace::marketplace.settings.commission_fee_each_category_fee_name', ['key' => $key]);
                $attributes[$categoryName] = trans('plugins/marketplace::marketplace.settings.commission_fee_each_category_name', ['key' => $key]);
            }
        }

        return $attributes;
    }
}
