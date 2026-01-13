<?php

namespace Botble\Marketplace\Enums;

use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static VendorAgreementTypeEnum FLAT_FEE()
 * @method static VendorAgreementTypeEnum COMMISSION()
 */
class VendorAgreementTypeEnum extends Enum
{
    public const FLAT_FEE = 'flat_fee';
    public const COMMISSION = 'commission';

    public static function labels(): array
    {
        return [
            self::FLAT_FEE => __('Flat Fee'),
            self::COMMISSION => __('Commission (%)'),
        ];
    }

    public static function descriptions(): array
    {
        return [
            self::FLAT_FEE => __('Pay a fixed monthly or yearly fee'),
            self::COMMISSION => __('Pay a percentage on each sale'),
        ];
    }
}
