<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Settings\DigitalProductSettingRequest;
use Botble\Setting\Forms\SettingForm;

class DigitalProductSettingFom extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.digital_product.digital_products_settings'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.digital_product.digital_products_settings_description'))
            ->setValidatorClass(DigitalProductSettingRequest::class)
            ->add('is_enabled_support_digital_products', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.digital_product.form.enable_support_digital_product'),
                'value' => EcommerceHelper::isEnabledSupportDigitalProducts(),
                'helper' => trans('plugins/ecommerce::setting.digital_product.form.enable_support_digital_product_helper'),
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.digital-products-settings',
                ],
            ])
            ->add('open_allow_guest_checkout_for_digital_products', 'html', [
                'html' => sprintf(
                    '<fieldset class="form-fieldset mt-3 digital-products-settings" style="display: %s;" data-bb-value="1">',
                    EcommerceHelper::isEnabledSupportDigitalProducts() ? 'block' : 'none'
                ),
            ])
            ->add(
                'allow_guest_checkout_for_digital_products',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.digital_product.form.allow_guest_checkout_for_digital_products'))
                    ->value(EcommerceHelper::allowGuestCheckoutForDigitalProducts())
                    ->helperText(trans('plugins/ecommerce::setting.digital_product.form.allow_guest_checkout_for_digital_products_helper'))
            )
            ->add(
                'disable_physical_product',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.digital_product.form.disable_physical_product'))
                    ->value(EcommerceHelper::isDisabledPhysicalProduct())
                    ->helperText(trans('plugins/ecommerce::setting.digital_product.form.disable_physical_product_helper'))
            )
            ->add(
                'enable_license_codes_for_digital_products',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.digital_product.form.enable_license_codes_for_digital_products'))
                    ->value(EcommerceHelper::isEnabledLicenseCodesForDigitalProducts())
                    ->helperText(trans('plugins/ecommerce::setting.digital_product.form.enable_license_codes_for_digital_products_helper'))
            )
            ->addOpenCollapsible('enable_license_codes_for_digital_products', '1', EcommerceHelper::isEnabledLicenseCodesForDigitalProducts())
            ->add(
                'hide_used_license_codes_in_product_form',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.digital_product.form.hide_used_license_codes_in_product_form'))
                    ->value(get_ecommerce_setting('hide_used_license_codes_in_product_form', false))
                    ->helperText(trans('plugins/ecommerce::setting.digital_product.form.hide_used_license_codes_in_product_form_helper'))
            )
            ->addCloseCollapsible('enable_license_codes_for_digital_products', '1')
            ->add(
                'auto_complete_digital_orders_after_payment',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.digital_product.form.auto_complete_digital_orders_after_payment'))
                    ->value(EcommerceHelper::isAutoCompleteDigitalOrdersAfterPayment())
                    ->helperText(trans('plugins/ecommerce::setting.digital_product.form.auto_complete_digital_orders_after_payment_helper'))
            )
            ->add('closed_allow_guest_checkout_for_digital_products', 'html', ['html' => '</fieldset>']);
    }
}
