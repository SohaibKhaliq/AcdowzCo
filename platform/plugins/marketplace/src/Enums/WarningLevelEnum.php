<?php

namespace Botble\Marketplace\Enums;

use Botble\Base\Supports\Enum;

/**
 * @method static WarningLevelEnum WARNING()
 * @method static WarningLevelEnum CRITICAL()
 * @method static WarningLevelEnum NOTICE()
 */
class WarningLevelEnum extends Enum
{
    public const WARNING = 'warning';
    public const CRITICAL = 'critical';
    public const NOTICE = 'notice';

    public static function labels(): array
    {
        return [
            self::WARNING => __('Warning'),
            self::CRITICAL => __('Critical'),
            self::NOTICE => __('Notice'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::WARNING => '#f59e0b',
            self::CRITICAL => '#ef4444',
            self::NOTICE => '#3b82f6',
        ];
    }
}
