<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\LabelFieldOption;
use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\LabelField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Supports\Helper;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Settings\CheckoutSettingRequest;
use Botble\Language\Forms\Fields\LanguageSwitcherField;
use Botble\Setting\Forms\SettingForm;

class CheckoutSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/setting.js')
            ->addScripts(['input-mask']);

        $countries = Helper::countries();
        $selectedCountries = array_keys(EcommerceHelper::getAvailableCountries());

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.checkout.name'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.checkout.description'))
            ->setValidatorClass(CheckoutSettingRequest::class)
            ->add(
                'enable_guest_checkout',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.enable_guest_checkout'))
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.enable_guest_checkout_helper'))
                    ->value(EcommerceHelper::isEnabledGuestCheckout())
            )
            ->add(
                'minimum_order_amount',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.minimum_order_amount', ['currency' => get_application_currency()->title]))
                    ->value(get_ecommerce_setting('minimum_order_amount', 0))
                    ->attributes([
                        'data-thousands-separator' => EcommerceHelper::getThousandSeparatorForInputMask(),
                        'data-decimal-separator' => EcommerceHelper::getDecimalSeparatorForInputMask(),
                        'class' => 'form-control input-mask-number',
                        'group-flat' => true,
                    ])
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.minimum_order_amount_helper'))
            )
            ->add(
                'minimum_order_quantity',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.minimum_order_quantity'))
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.minimum_order_quantity_helper'))
                    ->value(EcommerceHelper::getMinimumOrderQuantity())
            )
            ->add(
                'maximum_order_quantity',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.maximum_order_quantity'))
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.maximum_order_quantity_helper'))
                    ->value(EcommerceHelper::getMaximumOrderQuantity())
            )
            ->add(
                'mandatory_form_fields_at_checkout[]',
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.mandatory_form_fields_at_checkout'))
                    ->choices(EcommerceHelper::getMandatoryFieldsAtCheckout())
                    ->selected(old('mandatory_form_fields_at_checkout', EcommerceHelper::getEnabledMandatoryFieldsAtCheckout()))
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.mandatory_form_fields_at_checkout_helper'))
                    ->inline()
            )
            ->add(
                'hide_form_fields_at_checkout[]',
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.hide_form_fields_at_checkout'))
                    ->choices(EcommerceHelper::getMandatoryFieldsAtCheckout())
                    ->selected(old('hide_form_fields_at_checkout', EcommerceHelper::getHiddenFieldsAtCheckout()))
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.hide_form_fields_at_checkout_helper'))
                    ->inline()
            )
            ->add(
                'zip_code_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.zip_code_enabled'))
                    ->value(EcommerceHelper::isZipCodeEnabled())
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.zip_code_enabled_helper'))
            )
            ->add(
                'billing_address_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.billing_address_enabled'))
                    ->value(EcommerceHelper::isBillingAddressEnabled())
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.billing_address_enabled_helper'))
            )
            ->add(
                'display_tax_fields_at_checkout_page',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.display_tax_fields_at_checkout_page'))
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.display_tax_fields_at_checkout_page_helper'))
                    ->value(EcommerceHelper::isDisplayTaxFieldsAtCheckoutPage())
            )
            ->add(
                'show_terms_and_policy_checkbox',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.show_terms_and_policy_checkbox'))
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.show_terms_and_policy_checkbox_helper'))
                    ->value($showTermsAndPolicy = get_ecommerce_setting('show_terms_and_policy_checkbox', true))
            )
            ->addOpenCollapsible('show_terms_and_policy_checkbox', '1', $showTermsAndPolicy == '1')
            ->add(
                'terms_and_policy_checkbox_checked_by_default',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.terms_and_policy_checkbox_checked_by_default'))
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.terms_and_policy_checkbox_checked_by_default_helper'))
                    ->value(get_ecommerce_setting('terms_and_policy_checkbox_checked_by_default', false))
            )
            ->addCloseCollapsible('show_terms_and_policy_checkbox', '1')
            ->add(
                'checkout_acceptance_message_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.checkout_acceptance_message_enabled'))
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.checkout_acceptance_message_enabled_helper'))
                    ->value(get_ecommerce_setting('checkout_acceptance_message_enabled', false))
            )
            ->when(is_plugin_active('location'), function () use ($countries, $selectedCountries): void {
                $this
                    ->add(
                        'load_countries_states_cities_from_location_plugin',
                        'customRadio',
                        RadioFieldOption::make()
                            ->label(trans('plugins/ecommerce::setting.checkout.form.load_countries_states_cities_from_location_plugin'))
                            ->selected($loadLocationFromPlugin = (bool) get_ecommerce_setting('load_countries_states_cities_from_location_plugin', 0))
                            ->choices([
                                0 => trans('core/base::base.no'),
                                1 => trans('core/base::base.yes'),
                            ])
                            ->helperText(
                                trans('plugins/ecommerce::setting.checkout.form.load_countries_states_cities_from_location_plugin_placeholder') . ' ' .
                                trans('plugins/ecommerce::setting.checkout.form.load_countries_states_cities_from_location_plugin_placeholder_2')
                            )
                            ->attributes([
                                'data-bb-toggle' => 'collapse',
                                'data-bb-target' => '.location-settings',
                            ])
                    )
                    ->add('open_fieldset_location_settings', HtmlField::class, [
                        'html' => '<fieldset class="form-fieldset"/>',
                    ])
                    ->add('open_wrapper_use_city_field', HtmlField::class, [
                        'html' => sprintf('<div class="location-settings" data-bb-value="1" style="display: %s">', $loadLocationFromPlugin ? 'block' : 'none'),
                    ])
                    ->add(
                        'use_city_field_as_field_text',
                        OnOffCheckboxField::class,
                        OnOffFieldOption::make()
                            ->label(trans('plugins/ecommerce::setting.checkout.form.use_city_field_as_field_text'))
                            ->value(get_ecommerce_setting('use_city_field_as_field_text', false))
                    )
                    ->add('close_wrapper_use_city_field', HtmlField::class, [
                        'html' => '</div>',
                    ])
                    ->add('open_location_settings', HtmlField::class, [
                        'html' => sprintf(
                            '<div class="form-group location-settings" style="display: %s;" data-bb-value="0">',
                            ! $loadLocationFromPlugin ? 'block' : 'none',
                        ),
                    ])
                    ->add(
                        'location_settings_label',
                        LabelField::class,
                        LabelFieldOption::make()->label(trans('plugins/ecommerce::setting.checkout.form.available_countries'))
                    )
                    ->add(
                        'available_countries_all',
                        OnOffCheckboxField::class,
                        OnOffFieldOption::make()
                            ->label(trans('plugins/ecommerce::setting.checkout.form.all'))
                            ->labelAttributes(['class' => 'check-all', 'data-set' => '.available-countries'])
                            ->helperText(trans('plugins/ecommerce::setting.checkout.form.all_helper_text'))
                            ->value((count($selectedCountries) - 1) == count($countries) ? '1' : '')
                    )
                    ->add(
                        'available_countries[]',
                        MultiCheckListField::class,
                        MultiChecklistFieldOption::make()
                            ->label(false)
                            ->choices($countries)
                            ->selected($selectedCountries)
                            ->attributes(['class' => 'available-countries'])
                    )
                    ->add(
                        'default_country_at_checkout_page',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(trans('plugins/ecommerce::setting.checkout.form.default_country_at_checkout_page'))
                            ->choices(['' => trans('plugins/ecommerce::setting.checkout.form.default_country_at_checkout_page_placeholder')] + $countries)
                            ->selected(get_ecommerce_setting('default_country_at_checkout_page'))
                            ->helperText(trans('plugins/ecommerce::setting.checkout.form.default_country_at_checkout_page_help'))
                            ->searchable()
                    )
                    ->add('close_location_settings', HtmlField::class, [
                        'html' => '</div>',
                    ])
                    ->add('close_fieldset_location_settings', HtmlField::class, [
                        'html' => '</fieldset>',
                    ]);
            }, function () use ($countries, $selectedCountries): void {
                $this
                    ->add(
                        'location_settings_label',
                        LabelField::class,
                        LabelFieldOption::make()->label(trans('plugins/ecommerce::setting.checkout.form.available_countries'))
                    )
                    ->add('open_fieldset_location_settings', HtmlField::class, [
                        'html' => '<fieldset class="form-fieldset"/>',
                    ])
                    ->add('available_countries_all', OnOffCheckboxField::class, [
                        'label' => trans('plugins/ecommerce::setting.checkout.form.all'),
                        'label_attr' => [
                            'class' => 'check-all',
                            'data-set' => '.available-countries',
                        ],
                        'help_block' => [
                            'text' => trans('plugins/ecommerce::setting.checkout.form.all_helper_text'),
                        ],
                        'value' => (count($selectedCountries) - 1) == count($countries) ? '1' : '',
                    ])
                    ->add('available_countries[]', MultiCheckListField::class, [
                        'label' => false,
                        'choices' => $countries,
                        'value' => $selectedCountries,
                        'attr' => [
                            'class' => 'available-countries',
                        ],
                    ])
                    ->add(
                        'default_country_at_checkout_page',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(trans('plugins/ecommerce::setting.checkout.form.default_country_at_checkout_page'))
                            ->choices(['' => trans('plugins/ecommerce::setting.checkout.form.default_country_at_checkout_page_placeholder')] + $countries)
                            ->selected(get_ecommerce_setting('default_country_at_checkout_page'))
                            ->helperText(trans('plugins/ecommerce::setting.checkout.form.default_country_at_checkout_page_help'))
                            ->searchable()
                    )
                    ->add('close_fieldset_location_settings', HtmlField::class, [
                        'html' => '</fieldset>',
                    ]);
            })
            ->add(
                'enable_customer_recently_viewed_products',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.recently_viewed.enable'))
                    ->value(EcommerceHelper::isEnabledCustomerRecentlyViewedProducts())
                    ->attributes([
                        'data-bb-toggle' => 'collapse',
                        'data-bb-target' => '.recently-viewed-products-settings',
                    ])
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.recently_viewed.enable_helper'))
            )
            ->add('open_fieldset_recently_viewed_products_settings', HtmlField::class, [
                'html' => sprintf(
                    '<fieldset class="form-fieldset recently-viewed-products-settings" style="display: %s;" data-bb-value="1">',
                    EcommerceHelper::isEnabledCustomerRecentlyViewedProducts() ? 'block' : 'none',
                ),
            ])
            ->add(
                'max_customer_recently_viewed_products',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.recently_viewed.max'))
                    ->value(EcommerceHelper::maxCustomerRecentlyViewedProducts())
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.recently_viewed.max_helper_text'))
                    ->attributes([
                        'min' => 0,
                        'max' => 100,
                    ])
            )
            ->add('close_fieldset_recently_viewed_products_settings', HtmlField::class, [
                'html' => '</fieldset>',
            ])
            ->add(
                'checkout_product_quantity_editable',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.checkout_product_quantity_editable'))
                    ->value(get_ecommerce_setting('checkout_product_quantity_editable', true))
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.checkout_product_quantity_editable_helper'))
            )
            ->add(
                'hide_customer_info_at_checkout',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.hide_customer_info_at_checkout'))
                    ->value(get_ecommerce_setting('hide_customer_info_at_checkout', false))
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.hide_customer_info_at_checkout_helper'))
            )
            ->when(is_plugin_active('language'), function (FormAbstract $form): void {
                $form->add('languageSwitcher', LanguageSwitcherField::class);
            });
    }
}
