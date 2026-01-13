<?php

namespace Botble\Marketplace\Forms;

use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\FormAbstract;
use Botble\Marketplace\Enums\PayoutPaymentMethodsEnum;
use Botble\Marketplace\Http\Requests\WithdrawalRequest;
use Botble\Marketplace\Models\Withdrawal;
use Illuminate\Support\Arr;

class WithdrawalForm extends FormAbstract
{
    public function setup(): void
    {
        $symbol = sprintf(' (%s)', get_application_currency()->symbol);

        /**
         * @var Withdrawal $withdrawal
         */
        $withdrawal = $this->getModel();

        $this
            ->model(Withdrawal::class)
            ->setValidatorClass(WithdrawalRequest::class)
            ->add('amount', 'text', [
                'label' => trans('plugins/marketplace::withdrawal.forms.amount') . $symbol,
                'attr' => [
                    'disabled' => 'disabled',
                ],
            ])
            ->add('fee', 'text', [
                'label' => trans('plugins/marketplace::withdrawal.forms.fee') . $symbol,
                'attr' => [
                    'disabled' => 'disabled',
                ],
            ])
            ->add('payment_channel', 'customSelect', [
                'label' => trans('plugins/marketplace::withdrawal.forms.payment_channel'),
                'choices' => Arr::pluck(PayoutPaymentMethodsEnum::payoutMethodsEnabled(), 'label', 'key'),
                'attr' => $this->model->transaction_id ? ['disabled' => 'disabled'] : [],
            ])
            ->add('transaction_id', 'text', [
                'label' => trans('plugins/marketplace::withdrawal.forms.transaction_id'),
                'attr' => [
                    'placeholder' => trans('plugins/marketplace::withdrawal.forms.transaction_id_placeholder'),
                    'data-counter' => 60,
                ] + ($this->model->transaction_id ? ['disabled' => 'disabled'] : []),
            ])
            ->add('description', 'textarea', [
                'label' => trans('core/base::forms.description'),
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('payoutInfo', 'html', [
                'html' => view('plugins/marketplace::withdrawals.payout-info', [
                    'bankInfo' => $withdrawal->bank_info,
                    'taxInfo' => $withdrawal->customer->tax_info,
                    'paymentChannel' => $withdrawal->payment_channel,
                    'title' => __('Payout account'),
                ])->render(),
            ])
            ->add('images[]', 'mediaImages', [
                'label' => trans('plugins/ecommerce::products.form.image'),
                'values' => $withdrawal ? $withdrawal->images : [],
            ])
            ->when(! $withdrawal->canEditStatus(), function (FormAbstract $form) use ($withdrawal): void {
                $form->add(
                    'download_invoice',
                    HtmlField::class,
                    HtmlFieldOption::make()
                        ->content(view('plugins/marketplace::withdrawals.download-invoice', compact('withdrawal'))->render())
                );
            });

        if ($withdrawal->canEditStatus()) {
            $this
                ->add('status', 'customSelect', [
                    'label' => trans('core/base::tables.status'),
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'choices' => $withdrawal->getNextStatuses(),
                    'help_block' => [
                        'text' => $withdrawal->getStatusHelper(),
                    ],
                ]);
        } else {
            $this
                ->add('status', 'html', [
                    'html' => $withdrawal->status->toHtml(),
                ]);
        }

        $this->setBreakFieldPoint('status');
    }
}
