<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Enums\ResellerApplicationStatusEnum;
use Botble\Ecommerce\Models\ResellerApplication;

class ResellerApplicationForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(ResellerApplication::class)
            ->setMethod('PUT')
            ->columns()
            ->add('notes', TextareaField::class, 
                TextareaFieldOption::make()
                    ->label(__('Customer Notes'))
                    ->rows(5)
                    ->attributes(['readonly' => true])
                    ->colspan(2)
            )
            ->add('status', SelectField::class, 
                StatusFieldOption::make()
                    ->choices(ResellerApplicationStatusEnum::labels())
                    ->defaultValue(ResellerApplicationStatusEnum::PENDING)
            )
            ->add('rejection_reason', TextareaField::class, 
                TextareaFieldOption::make()
                    ->label(__('Rejection Reason'))
                    ->placeholder(__('Explain why this application was rejected...'))
                    ->rows(3)
                    ->colspan(2)
            )
            ->setBreakFieldPoint('status');
    }
}
