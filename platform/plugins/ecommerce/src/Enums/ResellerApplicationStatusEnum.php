<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static ResellerApplicationStatusEnum PENDING()
 * @method static ResellerApplicationStatusEnum APPROVED()
 * @method static ResellerApplicationStatusEnum REJECTED()
 */
class ResellerApplicationStatusEnum extends Enum
{
    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';

    public static $langPath = 'plugins/ecommerce::reseller-application.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
