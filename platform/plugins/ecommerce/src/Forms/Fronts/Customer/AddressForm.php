<?php

namespace Botble\Ecommerce\Forms\Fronts\Customer;

use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CheckboxField;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Ecommerce\Forms\Concerns\HasLocationFields;
use Botble\Ecommerce\Http\Requests\AddressRequest;
use Botble\Ecommerce\Models\Address;
use Botble\Theme\FormFront;

class AddressForm extends FormFront
{
    use HasLocationFields;

    protected string $formSelectInputClass;

    public function setup(): void
    {
        $model = $this->getModel();

        $this
            ->model(Address::class)
            ->setValidatorClass(AddressRequest::class)
            ->contentOnly()
            ->columns()
            ->add(
                'name',
                TextField::class,
                TextFieldOption::make()
                    ->addAttribute('id', 'address-name')
                    ->label(trans('plugins/ecommerce::addresses.name'))
                    ->placeholder(trans('plugins/ecommerce::addresses.name_placeholder'))
                    ->colspan(1)
            )
            ->add(
                'phone',
                TextField::class,
                TextFieldOption::make()
                    ->addAttribute('id', 'address-phone')
                    ->label(trans('plugins/ecommerce::addresses.phone'))
                    ->placeholder(trans('plugins/ecommerce::addresses.phone_placeholder'))
                    ->colspan(1)
            )
            ->add(
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->addAttribute('id', 'address-email')
                    ->colspan(1)
            )
            ->addLocationFields()
            ->add(
                'is_default',
                CheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(__('Use this address as default.'))
                    ->checked($model && $model->is_default)
                    ->colspan(2)
            )
            ->add(
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->colspan(2)
                    ->label(($model && $model->getKey()) ? __('Update') : __('Create'))
                    ->cssClass('btn btn-primary mt-4')
            );
    }

    public function setFormSelectInputClass(string $cssClass): static
    {
        $this->formSelectInputClass = $cssClass;

        return $this;
    }

    public function renderForm(
        array $options = [],
        bool $showStart = true,
        bool $showFields = true,
        bool $showEnd = true
    ): string {
        foreach ($this->getFields() as &$field) {
            if ($field->getType() != SelectField::class) {
                continue;
            }

            if (isset($this->formSelectClass)) {
                $field->setOption('attr.class', $this->formSelectClass);
            }
        }

        return parent::renderForm($options, $showStart, $showFields, $showEnd);
    }
}
