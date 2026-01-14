<?php

namespace Botble\Marketplace\Forms;

use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Marketplace\Http\Requests\SubscriptionPlanRequest;
use Botble\Marketplace\Models\SubscriptionPlan;

class SubscriptionPlanForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(SubscriptionPlan::class)
            ->setValidatorClass(SubscriptionPlanRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add(
                'duration',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/marketplace::subscription.plans.duration'))
                    ->choices([
                        'weekly' => trans('plugins/marketplace::subscription.plans.weekly'),
                        'monthly' => trans('plugins/marketplace::subscription.plans.monthly'),
                    ])
                    ->required()
                    ->toArray()
            )
            ->add(
                'price',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/marketplace::subscription.plans.price'))
                    ->required()
                    ->toArray()
            )
            ->add(
                'priority_boost',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/marketplace::subscription.plans.priority_boost'))
                    ->choices([
                        0 => trans('core/base::base.no'),
                        1 => trans('core/base::base.yes'),
                    ])
                    ->toArray()
            )
            ->add(
                'verified_eligible',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/marketplace::subscription.plans.verified_eligible'))
                    ->choices([
                        0 => trans('core/base::base.no'),
                        1 => trans('core/base::base.yes'),
                    ])
                    ->toArray()
            )
            ->add(
                'description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('core/base::forms.description'))
                    ->rows(4)
                    ->toArray()
            )
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->setBreakFieldPoint('status');
    }
}
