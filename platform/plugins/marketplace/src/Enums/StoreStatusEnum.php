<?php

namespace Botble\Marketplace\Enums;

use Botble\Base\Facades\Html;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static StoreStatusEnum PUBLISHED()
 * @method static StoreStatusEnum PENDING()
 * @method static StoreStatusEnum BLOCKED()
 */
class StoreStatusEnum extends Enum
{
    public const PUBLISHED = 'published';
    public const PENDING = 'pending';
    public const BLOCKED = 'blocked';

    public static $langPath = 'plugins/marketplace::marketplace.store_statuses';

    public function toHtml(): string|HtmlString
    {
        return match ($this->value) {
            self::PENDING => Html::tag('span', self::PENDING()->label(), ['class' => 'badge bg-yellow text-yellow-fg']),
            self::PUBLISHED => Html::tag('span', self::PUBLISHED()->label(), ['class' => 'badge bg-green text-green-fg']),
            self::BLOCKED => Html::tag('span', self::BLOCKED()->label(), ['class' => 'badge bg-red text-red-fg']),
            default => parent::toHtml(),
        };
    }
}
