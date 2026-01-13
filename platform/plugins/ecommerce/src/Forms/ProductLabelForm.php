<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\ColorFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\ColorField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Http\Requests\ProductLabelRequest;
use Botble\Ecommerce\Models\ProductLabel;

class ProductLabelForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(ProductLabel::class)
            ->setValidatorClass(ProductLabelRequest::class)
            ->add('name', TextField::class, NameFieldOption::make())
            ->add(
                'color',
                ColorField::class,
                ColorFieldOption::make()
                    ->label(trans('plugins/ecommerce::product-label.background_color'))
            )
            ->add(
                'text_color',
                ColorField::class,
                ColorFieldOption::make()
                    ->label(trans('plugins/ecommerce::product-label.text_color'))
                    ->helperText(trans('plugins/ecommerce::product-label.text_color_helper'))
                    ->defaultValue('#ffffff')
            )
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
