<?php

namespace Botble\Marketplace\Forms\Settings;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Marketplace\Enums\WithdrawalFeeTypeEnum;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Http\Requests\MarketPlaceSettingFormRequest;
use Botble\Marketplace\Models\Store;
use Botble\Media\Facades\RvMedia;
use Botble\Setting\Forms\SettingForm;

class MarketplaceSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        Assets::addStylesDirectly('vendor/core/core/base/libraries/tagify/tagify.css')
            ->addScriptsDirectly([
                'vendor/core/core/base/libraries/tagify/tagify.js',
                'vendor/core/core/base/js/tags.js',
                'vendor/core/plugins/marketplace/js/marketplace-setting.js',
            ]);

        $commissionEachCategory = [];

        if (MarketplaceHelper::isCommissionCategoryFeeBasedEnabled()) {
            $commissionEachCategory = Store::getCommissionEachCategory();
        }

        $allowedMimeTypes = RvMedia::getConfig('allowed_mime_types');
        $allowedMimeTypes = explode(',', $allowedMimeTypes);

        $this
            ->setSectionTitle(trans('plugins/marketplace::marketplace.settings.title'))
            ->setSectionDescription(trans('plugins/marketplace::marketplace.settings.description'))
            ->setValidatorClass(MarketPlaceSettingFormRequest::class)
            ->contentOnly()
            ->add('fee_per_order', 'number', [
                'label' => trans('plugins/marketplace::marketplace.settings.default_commission_fee'),
                'value' => MarketplaceHelper::getSetting('fee_per_order', 0),
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('enable_commission_fee_for_each_category', OnOffCheckboxField::class, [
                'label' => trans('plugins/marketplace::marketplace.settings.enable_commission_fee_for_each_category'),
                'value' => MarketplaceHelper::isCommissionCategoryFeeBasedEnabled(),
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.category-commission-fee-settings',
                ],
            ])
            ->add('category_commission_fee_fields', 'html', [
                'html' => view(
                    'plugins/marketplace::settings.partials.category-commission-fee-fields',
                    compact('commissionEachCategory')
                )->render(),
            ])
            ->add('fee_withdrawal', 'number', [
                'label' => trans('plugins/marketplace::marketplace.settings.fee_withdrawal_amount'),
                'value' => MarketplaceHelper::getSetting('fee_withdrawal', 0),
            ])
            ->add('withdrawal_fee_type', 'customSelect', [
                'label' => trans('plugins/marketplace::marketplace.settings.withdrawal_fee_type'),
                'selected' => MarketplaceHelper::getSetting('withdrawal_fee_type', WithdrawalFeeTypeEnum::FIXED),
                'choices' => WithdrawalFeeTypeEnum::labels(),
            ])
            ->add('check_valid_signature', OnOffCheckboxField::class, [
                'label' => trans('plugins/marketplace::marketplace.settings.check_valid_signature'),
                'value' => MarketplaceHelper::getSetting('check_valid_signature', true),
            ])
            ->add(
                'enable_product_approval',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.enable_product_approval'))
                    ->value(MarketplaceHelper::getSetting('enable_product_approval', true))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.enable_product_approval_description'))
            )
            ->add('max_filesize_upload_by_vendor', 'number', [
                'label' => trans('plugins/marketplace::marketplace.settings.max_upload_filesize'),
                'value' => $maxSize = MarketplaceHelper::maxFilesizeUploadByVendor(),
                'attr' => [
                    'placeholder' => trans(
                        'plugins/marketplace::marketplace.settings.max_upload_filesize_placeholder',
                        [
                            'size' => $maxSize,
                        ]
                    ),
                    'step' => 1,
                ],
            ])
            ->add('max_product_images_upload_by_vendor', 'number', [
                'label' => trans('plugins/marketplace::marketplace.settings.max_product_images_upload_by_vendor'),
                'value' => MarketplaceHelper::maxProductImagesUploadByVendor(),
                'attr' => [
                    'step' => 1,
                ],
            ])
            ->add(
                'media_mime_types_allowed[]',
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.media_file_types_can_be_uploaded_by_vendor'))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.media_file_types_can_be_uploaded_by_vendor_helper'))
                    ->choices(array_combine($allowedMimeTypes, $allowedMimeTypes))
                    ->selected(MarketplaceHelper::mediaMimeTypesAllowed())
            )
            ->add(
                'enabled_vendor_registration',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.enable_vendor_registration'))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.enable_vendor_registration_helper'))
                    ->value(MarketplaceHelper::isVendorRegistrationEnabled())
            )
            ->addOpenFieldset('vendor_registration_settings', [
                'data-bb-collapse' => 'true',
                'data-bb-trigger' => "[name='enabled_vendor_registration']",
                'data-bb-value' => '1',
                'style' => MarketplaceHelper::isVendorRegistrationEnabled() ? '' : 'display: none;',
            ])
            ->add(
                'hide_become_vendor_menu_in_customer_dashboard',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.hide_become_vendor_menu_in_customer_dashboard'))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.hide_become_vendor_menu_in_customer_dashboard_description'))
                    ->value(MarketplaceHelper::getSetting('hide_become_vendor_menu_in_customer_dashboard', false))
            )
            ->add(
                'show_vendor_registration_form_at_registration_page',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.show_vendor_registration_form_at_registration_page'))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.show_vendor_registration_form_at_registration_page_description'))
                    ->value(MarketplaceHelper::getSetting('show_vendor_registration_form_at_registration_page', true))
            )
            ->add(
                'verify_vendor',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.verify_vendor'))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.verify_vendor_helper'))
                    ->value(MarketplaceHelper::getSetting('verify_vendor', true))
            )
            ->add(
                'requires_vendor_documentations_verification',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.requires_vendor_documentations_verification'))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.requires_vendor_documentations_verification_helper'))
                    ->value(MarketplaceHelper::getSetting('requires_vendor_documentations_verification', true))
            )
            ->addCloseFieldset('vendor_registration_settings')
            ->add('hide_store_phone_number', OnOffCheckboxField::class, [
                'label' => trans('plugins/marketplace::marketplace.settings.hide_store_phone_number'),
                'value' => MarketplaceHelper::hideStorePhoneNumber(),
            ])
            ->add('hide_store_email', OnOffCheckboxField::class, [
                'label' => trans('plugins/marketplace::marketplace.settings.hide_store_email'),
                'value' => MarketplaceHelper::hideStoreEmail(),
            ])
            ->add('hide_store_address', OnOffCheckboxField::class, [
                'label' => trans('plugins/marketplace::marketplace.settings.hide_store_address'),
                'value' => MarketplaceHelper::hideStoreAddress(),
            ])
            ->add(
                'hide_store_social_links',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.hide_store_social_links'))
                    ->value(MarketplaceHelper::hideStoreSocialLinks())
            )
            ->add(
                'enable_vendor_categories_filter',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.enable_vendor_categories_filter'))
                    ->value(MarketplaceHelper::getSetting('enable_vendor_categories_filter', true))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.enable_vendor_categories_filter_description'))
            )
            ->add(
                'allow_vendor_manage_shipping',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.allow_vendor_manage_shipping'))
                    ->value(MarketplaceHelper::allowVendorManageShipping())
                    ->helperText(trans('plugins/marketplace::marketplace.settings.allow_vendor_manage_shipping_description'))
            )
            ->add(
                'charge_shipping_per_vendor',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.charge_shipping_per_vendor'))
                    ->value(MarketplaceHelper::isChargeShippingPerVendor())
                    ->helperText(trans('plugins/marketplace::marketplace.settings.charge_shipping_per_vendor_description'))
            )
            ->add(
                'enabled_messaging_system',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                ->label(trans('plugins/marketplace::marketplace.settings.enable_messaging_system'))
                ->value(MarketplaceHelper::isEnabledMessagingSystem())
                ->helperText(trans('plugins/marketplace::marketplace.settings.enable_messaging_system_description'))
            )
            ->add('payment_method_fields', 'html', [
                'html' => view('plugins/marketplace::settings.partials.payment-method-fields')->render(),
            ])
            ->add(
                'minimum_withdrawal_amount',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.minimum_withdrawal_amount'))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.minimum_withdrawal_amount_helper'))
                    ->value(MarketplaceHelper::getMinimumWithdrawalAmount())
            )
            ->add(
                'allow_vendor_delete_their_orders',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.allow_vendor_delete_their_orders'))
                    ->helperText(
                        trans('plugins/marketplace::marketplace.settings.allow_vendor_delete_their_orders_description')
                    )
                    ->value(MarketplaceHelper::allowVendorDeleteTheirOrders())
            )
            ->add(
                'single_vendor_checkout',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.single_vendor_checkout'))
                    ->value(MarketplaceHelper::isSingleVendorCheckout())
                    ->helperText(trans('plugins/marketplace::marketplace.settings.single_vendor_checkout_help'))
            )
            ->add(
                'display_order_total_info_for_each_store',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.display_order_total_info_for_each_store'))
                    ->value(MarketplaceHelper::getSetting('display_order_total_info_for_each_store', false))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.display_order_total_info_for_each_store_helper'))
            )
            ->add(
                'show_vendor_info_at_checkout',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.show_vendor_info_at_checkout'))
                    ->value(MarketplaceHelper::getSetting('show_vendor_info_at_checkout', true))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.show_vendor_info_at_checkout_helper'))
            );
    }
}
