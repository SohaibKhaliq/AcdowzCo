<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Forms\Concerns\HasLocationFields;
use Botble\Ecommerce\Forms\Concerns\HasSubmitButton;
use Botble\Ecommerce\Http\Requests\TaxRuleRequest;
use Botble\Ecommerce\Models\Tax;
use Botble\Ecommerce\Models\TaxRule;
use Illuminate\Support\Facades\Request;

class TaxRuleForm extends FormAbstract
{
    use HasLocationFields;
    use HasSubmitButton;

    public function setup(): void
    {
        $this
            ->model(TaxRule::class)
            ->setValidatorClass(TaxRuleRequest::class)
            ->setFormOption('id', 'ecommerce-tax-rule-form')
            ->when(Request::ajax(), function (FormAbstract $form): void {
                $form->contentOnly();
            });

        if (! $this->getModel()->getKey()) {
            $this
                ->when(
                    $taxId = request()->input('tax_id'),
                    fn (FormAbstract $form) => $form->add('tax_id', 'hidden', [
                        'value' => $taxId,
                    ]),
                    function (FormAbstract $form): void {
                        $taxes = Tax::query()->pluck('title', 'id')->all();
                        $form
                            ->add('tax_id', 'customSelect', [
                                'label' => trans('plugins/ecommerce::tax.tax'),
                                'choices' => $taxes,
                            ]);
                    }
                );
        }

        $this
            ->addLocationFields(countryAttributes: ['required' => true])
            ->remove('address')
            ->add(
                'percentage',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/ecommerce::tax.percentage'))
                    ->attributes(['step' => '0.01'])
                    ->required()
            )
            ->when($this->request->ajax(), function (TaxRuleForm $form): void {
                $form->addSubmitButton(trans('core/base::forms.save'), 'ti ti-device-floppy', [
                    'wrapper' => [
                        'class' => 'd-grid gap-2',
                    ],
                ]);
            });
    }
}
