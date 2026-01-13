<?php

namespace Botble\Marketplace\Forms;

use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\FormAbstract;
use Botble\Marketplace\Enums\WithdrawalFeeTypeEnum;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Http\Requests\Fronts\VendorEditWithdrawalRequest;
use Botble\Marketplace\Http\Requests\Fronts\VendorWithdrawalRequest;
use Botble\Marketplace\Models\Withdrawal;

class VendorWithdrawalForm extends FormAbstract
{
    public function setup(): void
    {
        $fee = MarketplaceHelper::getSetting('fee_withdrawal', 0);
        $feeType = MarketplaceHelper::getSetting('withdrawal_fee_type', WithdrawalFeeTypeEnum::FIXED);

        $exists = $this->getModel() && $this->getModel()->id;

        $actionButtons = view('plugins/marketplace::withdrawals.forms.actions')->render();
        if ($exists) {
            $fee = null;
            if (! $this->getModel()->vendor_can_edit) {
                $actionButtons = ' ';
            }
        }

        $user = auth('customer')->user();
        $model = $user;
        $balance = $model->balance;
        $paymentChannel = $model->vendorInfo->payout_payment_method;

        if ($exists) {
            $model = $this->getModel();
            $paymentChannel = $model->payment_channel;
        }

        // Calculate maximum withdrawal amount considering the fee type
        if ($feeType === WithdrawalFeeTypeEnum::PERCENTAGE) {
            // For percentage fee, solve the equation: amount + (amount * fee / 100) <= balance
            // Which gives us: amount <= balance / (1 + fee/100)
            $maximum = $fee > 0 ? floor($balance / (1 + $fee / 100)) : $balance;
        } else {
            // For fixed fee, simply subtract the fee from balance
            $maximum = $balance - $fee;
        }
        $maximum = max(0, $maximum); // Ensure maximum is not negative

        $feeHelperText = '';
        if ($fee) {
            if ($feeType === WithdrawalFeeTypeEnum::FIXED) {
                $feeHelperText = trans('plugins/marketplace::withdrawal.forms.fee_fixed_helper', ['fee' => format_price($fee)]);
            } else {
                $feeHelperText = trans('plugins/marketplace::withdrawal.forms.fee_percentage_helper', ['fee' => $fee]);
            }
        }

        $this
            ->model(Withdrawal::class)
            ->setValidatorClass($exists ? VendorEditWithdrawalRequest::class : VendorWithdrawalRequest::class)
            ->template(MarketplaceHelper::viewPath('vendor-dashboard.forms.base'))
            ->add(
                'amount',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/marketplace::withdrawal.forms.amount_with_balance', ['balance' => format_price($balance)]))
                    ->required()
                    ->placeholder(trans('plugins/marketplace::withdrawal.forms.amount_placeholder'))
                    ->attributes([
                        'data-counter' => 120,
                        'max' => $maximum,
                        'min' => MarketplaceHelper::getMinimumWithdrawalAmount(),
                    ])
                    ->disabled($exists)
                    ->helperText($feeHelperText)
            )
            ->when($exists, function (FormAbstract $form): void {
                $form->add(
                    'fee',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(trans('plugins/marketplace::withdrawal.forms.fee'))
                        ->required()
                        ->disabled()
                );
            })
            ->add(
                'description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('core/base::forms.description'))
                    ->disabled($exists && ! $this->getModel()->vendor_can_edit)
                    ->placeholder(trans('core/base::forms.description_placeholder'))
                    ->attributes(['data-counter' => 200, 'rows' => 3])
            )
            ->add('bankInfo', 'html', [
                'html' => view('plugins/marketplace::withdrawals.payout-info', [
                    'bankInfo' => $model->bank_info,
                    'taxInfo' => $user->tax_info,
                    'paymentChannel' => $paymentChannel,
                    'link' => $exists ? null : route('marketplace.vendor.settings', ['#tab_payout_info']),
                ])
                    ->render(),
            ]);

        if ($exists) {
            if ($model->images) {
                $this->addMetaBoxes([
                    'images' => [
                        'title' => __('Withdrawal images'),
                        'content' => view('plugins/marketplace::withdrawals.forms.images', compact('model'))->render(),
                        'priority' => 4,
                    ],
                ]);
            }

            if ($this->getModel()->vendor_can_edit) {
                $this->add('cancel', 'onOff', [
                    'label' => __('Do you want to cancel?'),
                    'help_block' => [
                        'text' => __('After cancel amount and fee will be refunded back in your balance'),
                    ],
                ]);
            } else {
                $this->add('cancel', 'html', [
                    'label' => trans('core/base::tables.status'),
                    'html' => $model->status->toHtml(),
                ]);
            }
        }

        $this
            ->setBreakFieldPoint('cancel')
            ->setActionButtons($actionButtons);
    }
}
