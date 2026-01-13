<?php

namespace Botble\Theme\Listeners;

use Botble\Base\Events\CacheCleared;
use Botble\Theme\Facades\Manager as ThemeManager;

class ClearThemeCache
{
    public function handle(CacheCleared $event): void
    {
        if (in_array($event->cacheType, ['framework', 'all'])) {
            ThemeManager::clearCache();
        }
    }
}
