<?php

namespace Botble\Ecommerce\Forms\Fronts\Customer;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\InputFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Ecommerce\Http\Requests\EditAccountRequest;
use Botble\Ecommerce\Models\Customer;
use Botble\Theme\FormFront;
use Illuminate\Support\Facades\App;

class CustomerForm extends FormFront
{
    public function setup(): void
    {
        $this
            ->model(Customer::class)
            ->setUrl(route('customer.edit-account'))
            ->setValidatorClass(EditAccountRequest::class)
            ->contentOnly()
            ->add(
                'name',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Full Name'))
            )
            ->when(get_ecommerce_setting('enabled_customer_dob_field', true), function (CustomerForm $form): void {
                $form->add(
                    'dob',
                    TextField::class,
                    InputFieldOption::make()
                        ->addAttribute('id', 'date_of_birth')
                        ->addAttribute('data-date-format', config('core.base.general.date_format.js.date'))
                        ->addAttribute('data-locale', App::getLocale())
                        ->value($this->getModel()->dob ? BaseHelper::formatDate($this->getModel()->dob) : null)
                        ->label(__('Date of birth'))
                );
            })
            ->add(
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->disabled($this->getModel()->email)
            )
            ->add(
                'phone',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Phone'))
            )
            ->add(
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->label(__('Update'))
                    ->cssClass('btn btn-primary')
            );
    }
}
