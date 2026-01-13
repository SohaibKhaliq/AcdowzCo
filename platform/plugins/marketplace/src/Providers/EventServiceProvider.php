<?php

namespace Botble\Marketplace\Providers;

use Botble\Base\Events\RenderingAdminWidgetEvent;
use Botble\Ecommerce\Events\OrderCancelledEvent;
use Botble\Ecommerce\Events\OrderCreated;
use Botble\Marketplace\Events\WithdrawalRequested;
use Botble\Marketplace\Listeners\ClearVendorCategoriesCacheOnSync;
use Botble\Marketplace\Listeners\OrderCancelledEmailNotification;
use Botble\Marketplace\Listeners\OrderCreatedEmailNotification;
use Botble\Marketplace\Listeners\RegisterMarketplaceWidget;
use Botble\Marketplace\Listeners\RenderingSiteMapListener;
use Botble\Marketplace\Listeners\SaveVendorInformationListener;
use Botble\Marketplace\Listeners\SendMailAfterVendorRegistered;
use Botble\Marketplace\Listeners\WithdrawalRequestedNotification;
use Botble\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SaveVendorInformationListener::class,
            SendMailAfterVendorRegistered::class,
        ],
        RenderingSiteMapEvent::class => [
            RenderingSiteMapListener::class,
        ],
        OrderCreated::class => [
            OrderCreatedEmailNotification::class,
        ],
        OrderCancelledEvent::class => [
            OrderCancelledEmailNotification::class,
        ],
        WithdrawalRequested::class => [
            WithdrawalRequestedNotification::class,
        ],
        RenderingAdminWidgetEvent::class => [
            RegisterMarketplaceWidget::class,
        ],
        'eloquent.pivotAttached: Botble\Ecommerce\Models\Product' => [
            ClearVendorCategoriesCacheOnSync::class,
        ],
        'eloquent.pivotDetached: Botble\Ecommerce\Models\Product' => [
            ClearVendorCategoriesCacheOnSync::class,
        ],
        'eloquent.pivotUpdated: Botble\Ecommerce\Models\Product' => [
            ClearVendorCategoriesCacheOnSync::class,
        ],
    ];
}
