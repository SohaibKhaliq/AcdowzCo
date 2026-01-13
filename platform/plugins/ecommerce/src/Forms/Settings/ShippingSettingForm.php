<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Ecommerce\Http\Requests\Settings\ShippingSettingRequest;
use Botble\Setting\Forms\SettingForm;

class ShippingSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.shipping.shipping_setting'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.shipping.shipping_setting_description'))
            ->setValidatorClass(ShippingSettingRequest::class)
            ->contentOnly()
            ->add(
                'hide_other_shipping_options_if_it_has_free_shipping',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shipping.form.hide_other_shipping_options_if_it_has_free_shipping'))
                    ->value(get_ecommerce_setting('hide_other_shipping_options_if_it_has_free_shipping', false))
            )
            ->add(
                'sort_shipping_options_direction',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shipping.form.sort_shipping_options_direction'))
                    ->helperText(trans('plugins/ecommerce::setting.shipping.form.sort_shipping_options_direction_helper'))
                    ->choices([
                        'price_lower_to_higher' => trans('plugins/ecommerce::setting.shipping.form.price_lower_to_higher'),
                        'price_higher_to_lower' => trans('plugins/ecommerce::setting.shipping.form.price_higher_to_lower'),
                    ])
                    ->selected(get_ecommerce_setting('sort_shipping_options_direction', 'price_lower_to_higher'))
            )
            ->add(
                'disable_shipping_options',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shipping.form.disable_shipping_options'))
                    ->helperText(trans('plugins/ecommerce::setting.shipping.form.disable_shipping_options_helper'))
                    ->value(get_ecommerce_setting('disable_shipping_options', false))
                    ->wrapperAttributes([
                        'class' => 'mb-0',
                    ])
            );
    }
}
