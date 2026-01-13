<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Http\Requests\TaxRequest;
use Botble\Ecommerce\Models\Tax;
use Botble\Ecommerce\Tables\TaxRuleTable;

class TaxForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Tax::class)
            ->setValidatorClass(TaxRequest::class)
            ->add('title', 'text', [
                'label' => trans('plugins/ecommerce::tax.title'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/ecommerce::tax.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add(
                'percentage',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/ecommerce::tax.percentage'))
                    ->attributes(['step' => '0.01'])
                    ->required()
            )
            ->add('priority', 'number', [
                'label' => trans('plugins/ecommerce::tax.priority'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/ecommerce::tax.priority'),
                    'data-counter' => 120,
                ],
            ])
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status')
            ->when(
                $this->getModel()->id,
                fn (FormAbstract $form) => $form->addMetaBoxes([
                    'tax_rules' => [
                        'title' => trans('plugins/ecommerce::tax.rule.name'),
                        'content' => app(TaxRuleTable::class)
                            ->setView('core/table::base-table')
                            ->setAjaxUrl(route('tax.rule.index', $this->getModel()->getKey() ?: 0))->renderTable(),
                        'has_table' => true,
                        'wrap' => true,
                    ],
                ])
            );
    }
}
