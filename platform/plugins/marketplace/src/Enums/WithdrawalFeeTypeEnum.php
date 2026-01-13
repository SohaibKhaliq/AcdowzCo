<?php

namespace Botble\Marketplace\Enums;

use Botble\Base\Supports\Enum;

/**
 * @method static WithdrawalFeeTypeEnum FIXED()
 * @method static WithdrawalFeeTypeEnum PERCENTAGE()
 */
class WithdrawalFeeTypeEnum extends Enum
{
    public const FIXED = 'fixed';
    public const PERCENTAGE = 'percentage';

    public static $langPath = 'plugins/marketplace::marketplace.settings.withdrawal_fee_types';

    public function toHtml(): string
    {
        return match ($this->value) {
            self::FIXED => trans('plugins/marketplace::marketplace.settings.withdrawal_fee_types.fixed'),
            self::PERCENTAGE => trans('plugins/marketplace::marketplace.settings.withdrawal_fee_types.percentage'),
            default => parent::toHtml(),
        };
    }
}
