<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Settings\ProductSettingRequest;
use Botble\Setting\Forms\SettingForm;

class ProductSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.product.product_settings'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.product.product_settings_description'))
            ->setValidatorClass(ProductSettingRequest::class)
            ->add(
                'how_to_display_product_variation_images',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product.form.how_to_display_product_variation_images'))
                    ->helperText(trans('plugins/ecommerce::setting.product.form.how_to_display_product_variation_images_helper'))
                    ->choices([
                        'only_variation_images' => trans('plugins/ecommerce::setting.product.form.only_variation_images'),
                        'variation_images_and_main_product_images' => trans(
                            'plugins/ecommerce::setting.product.form.variation_images_and_main_product_images',
                        ),
                    ])
                    ->selected(get_ecommerce_setting('how_to_display_product_variation_images', 'only_variation_images'))
            )
            ->add(
                'show_number_of_products',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product.form.show_number_of_products'))
                    ->helperText(trans('plugins/ecommerce::setting.product.form.show_number_of_products_helper'))
                    ->value(EcommerceHelper::showNumberOfProductsInProductSingle())
            )
            ->add(
                'show_out_of_stock_products',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product.form.show_out_of_stock_products'))
                    ->helperText(trans('plugins/ecommerce::setting.product.form.show_out_of_stock_products_helper'))
                    ->value(EcommerceHelper::showOutOfStockProducts())
            )
            ->add(
                'is_enabled_product_options',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product.form.enable_product_options'))
                    ->helperText(trans('plugins/ecommerce::setting.product.form.enable_product_options_helper'))
                    ->value(EcommerceHelper::isEnabledProductOptions())
            )
            ->add(
                'is_enabled_cross_sale_products',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product.form.is_enabled_cross_sale_products'))
                    ->helperText(trans('plugins/ecommerce::setting.product.form.is_enabled_cross_sale_products_helper'))
                    ->value(EcommerceHelper::isEnabledCrossSaleProducts())
            )
            ->add(
                'is_enabled_related_products',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product.form.is_enabled_related_products'))
                    ->helperText(trans('plugins/ecommerce::setting.product.form.is_enabled_related_products_helper'))
                    ->value($relatedProductsEnabled = EcommerceHelper::isEnabledRelatedProducts())
            )
            ->addOpenCollapsible('is_enabled_related_products', '1', $relatedProductsEnabled)
            ->add(
                'related_products_source',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product.form.related_products_source'))
                    ->helperText(trans('plugins/ecommerce::setting.product.form.related_products_source_helper'))
                    ->choices([
                        'category' => trans('plugins/ecommerce::setting.product.form.related_products_source_category'),
                        'brand' => trans('plugins/ecommerce::setting.product.form.related_products_source_brand'),
                    ])
                    ->selected(get_ecommerce_setting('related_products_source', 'category'))
            )
            ->addCloseCollapsible('is_enabled_related_products', '1')
            ->add(
                'trending_products_period_days',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product.form.trending_products_period'))
                    ->helperText(trans('plugins/ecommerce::setting.product.form.trending_products_period_helper'))
                    ->choices([
                        '1' => trans('plugins/ecommerce::setting.product.form.trending_products_period_1_day'),
                        '3' => trans('plugins/ecommerce::setting.product.form.trending_products_period_3_days'),
                        '7' => trans('plugins/ecommerce::setting.product.form.trending_products_period_7_days'),
                        '14' => trans('plugins/ecommerce::setting.product.form.trending_products_period_14_days'),
                        '30' => trans('plugins/ecommerce::setting.product.form.trending_products_period_30_days'),
                        '60' => trans('plugins/ecommerce::setting.product.form.trending_products_period_60_days'),
                        '90' => trans('plugins/ecommerce::setting.product.form.trending_products_period_90_days'),
                    ])
                    ->selected(get_ecommerce_setting('trending_products_period_days', '7'))
            )
            ->add(
                'enable_product_specification',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product.form.enable_product_specification'))
                    ->helperText(trans('plugins/ecommerce::setting.product.form.enable_product_specification_help'))
                    ->value(EcommerceHelper::isProductSpecificationEnabled())
            )
            ->add(
                'auto_generate_product_sku',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product.form.auto_generate_product_sku'))
                    ->helperText(trans('plugins/ecommerce::setting.product.form.auto_generate_product_sku_helper'))
                    ->value($targetValue = get_ecommerce_setting('auto_generate_product_sku', true))
            )
            ->addOpenCollapsible('auto_generate_product_sku', '1', $targetValue == '1')
            ->add(
                'product_sku_format',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product.form.product_sku_format'))
                    ->value(get_ecommerce_setting('product_sku_format', null))
                    ->helperText(trans('plugins/ecommerce::setting.product.form.product_sku_format_helper'))
            )
            ->addCloseCollapsible('auto_generate_product_sku', '1')
            ->add(
                'make_product_barcode_required',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product.form.make_product_barcode_required'))
                    ->helperText(trans('plugins/ecommerce::setting.product.form.make_product_barcode_required_helper'))
                    ->value(get_ecommerce_setting('make_product_barcode_required', false))
            );
    }
}
