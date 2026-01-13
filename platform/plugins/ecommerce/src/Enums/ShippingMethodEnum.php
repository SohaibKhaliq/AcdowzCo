<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;

/**
 * @method static ShippingMethodEnum DEFAULT()
 * @method static ShippingMethodEnum NONE()
 */
class ShippingMethodEnum extends Enum
{
    public const DEFAULT = 'default';
    public const NONE = '';

    public static $langPath = 'plugins/ecommerce::shipping.methods';
}
