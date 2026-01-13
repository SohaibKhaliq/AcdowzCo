<?php

namespace Botble\Marketplace\Http\Requests\Fronts;

use Botble\Marketplace\Enums\WithdrawalFeeTypeEnum;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Support\Http\Requests\Request;

class VendorWithdrawalRequest extends Request
{
    public function rules(): array
    {
        $balance = auth('customer')->user()->balance;
        $fee = MarketplaceHelper::getSetting('fee_withdrawal', 0);
        $feeType = MarketplaceHelper::getSetting('withdrawal_fee_type', WithdrawalFeeTypeEnum::FIXED);
        $minimumWithdrawal = MarketplaceHelper::getMinimumWithdrawalAmount();

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

        return [
            'amount' => [
                'required',
                'numeric',
                "min:{$minimumWithdrawal}",
                "max:{$maximum}",
            ],
            'description' => ['nullable', 'max:400'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.max' => __('The balance is not enough for withdrawal'),
            'amount.min' => __('The minimum withdrawal amount is :amount', [
                'amount' => format_price(MarketplaceHelper::getMinimumWithdrawalAmount()),
            ]),
        ];
    }
}
