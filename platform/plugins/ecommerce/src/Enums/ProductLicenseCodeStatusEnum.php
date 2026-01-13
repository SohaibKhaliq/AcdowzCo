<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static ProductLicenseCodeStatusEnum AVAILABLE()
 * @method static ProductLicenseCodeStatusEnum USED()
 */
class ProductLicenseCodeStatusEnum extends Enum
{
    public const AVAILABLE = 'available';

    public const USED = 'used';

    public static $langPath = 'plugins/ecommerce::products.license_codes.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::AVAILABLE => 'success',
            self::USED => 'danger',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
