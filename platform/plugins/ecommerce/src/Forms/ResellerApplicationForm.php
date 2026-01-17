<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Enums\ResellerApplicationStatusEnum;
use Botble\Ecommerce\Models\ResellerApplication;

class ResellerApplicationForm extends FormAbstract
{
    public function setup(): void
    {
        $resellerApplication = $this->getModel();
        $customer = $resellerApplication ? $resellerApplication->customer : null;

        $this
            ->model(ResellerApplication::class)
            ->setMethod('PUT')
            ->setUrl(route('ecommerce.reseller-applications.update', $resellerApplication->id))
            ->columns()
            ->add('customer_details', HtmlField::class, [
                'html' => $customer ? sprintf(
                    '<div class="widget meta-boxes">
                        <div class="widget-title">
                            <h4>%s</h4>
                        </div>
                        <div class="widget-body">
                            <p><strong>%s:</strong> <a href="%s" target="_blank">%s</a></p>
                            <p><strong>%s:</strong> %s</p>
                            <p><strong>%s:</strong> %s</p>
                        </div>
                    </div>',
                    __('Customer Information'),
                    __('Name'),
                    route('customers.edit', $customer->id),
                    $customer->name,
                    __('Email'),
                    $customer->email,
                    __('Phone'),
                    $customer->phone ?: 'N/A'
                ) : '',
                'colspan' => 2,
            ])
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
