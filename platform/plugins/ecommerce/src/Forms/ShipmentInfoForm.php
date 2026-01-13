<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Forms\Concerns\HasSubmitButton;
use Botble\Ecommerce\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\Ecommerce\Http\Requests\ShipmentRequest;
use Botble\Ecommerce\Models\Shipment;

class ShipmentInfoForm extends FormAbstract
{
    use HasSubmitButton;

    public function setup(): void
    {
        $this
            ->model(Shipment::class)
            ->setValidatorClass(ShipmentRequest::class)
            ->contentOnly()
            ->add(
                'shipping_company_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::shipping.shipping_company_name'))
                    ->placeholder(trans('plugins/ecommerce::shipping.shipping_company_name_placeholder'))
            )
            ->add(
                'tracking_id',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::shipping.tracking_id'))
                    ->placeholder(trans('plugins/ecommerce::shipping.tracking_id_placeholder'))
            )
            ->add(
                'tracking_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::shipping.tracking_link'))
                    ->placeholder(trans('plugins/ecommerce::shipping.tracking_link_placeholder'))
            )
            ->add(
                'estimate_date_shipped',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label(trans('plugins/ecommerce::shipping.estimate_date_shipped'))
            )
            ->add(
                'note',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('plugins/ecommerce::shipping.note'))
                    ->placeholder(trans('plugins/ecommerce::shipping.add_note'))
                    ->rows(3)
                    ->maxLength(10000)
            )
            ->addSubmitButton(trans('core/base::forms.save_and_continue'), 'ti ti-circle-check');
    }
}
