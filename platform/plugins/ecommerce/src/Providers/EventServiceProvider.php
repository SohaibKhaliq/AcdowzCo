<?php

namespace Botble\Ecommerce\Providers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\RenderingAdminWidgetEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Ecommerce\Events\AbandonedCartReminderEvent;
use Botble\Ecommerce\Events\CustomerEmailVerified;
use Botble\Ecommerce\Events\OrderCancelledEvent;
use Botble\Ecommerce\Events\OrderCompletedEvent;
use Botble\Ecommerce\Events\OrderCreated;
use Botble\Ecommerce\Events\OrderPaymentConfirmedEvent;
use Botble\Ecommerce\Events\OrderPlacedEvent;
use Botble\Ecommerce\Events\OrderReturnedEvent;
use Botble\Ecommerce\Events\ProductFileUpdatedEvent;
use Botble\Ecommerce\Events\ProductQuantityUpdatedEvent;
use Botble\Ecommerce\Events\ProductVariationCreated;
use Botble\Ecommerce\Events\ProductViewed;
use Botble\Ecommerce\Events\ShippingStatusChanged;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Listeners\AddLanguageForVariantsListener;
use Botble\Ecommerce\Listeners\ClearShippingRuleCache;
use Botble\Ecommerce\Listeners\GenerateInvoiceListener;
use Botble\Ecommerce\Listeners\GenerateLicenseCodeAfterOrderCompleted;
use Botble\Ecommerce\Listeners\MarkCartAsRecovered;
use Botble\Ecommerce\Listeners\OrderCancelledNotification;
use Botble\Ecommerce\Listeners\OrderCreatedNotification;
use Botble\Ecommerce\Listeners\OrderPaymentConfirmedNotification;
use Botble\Ecommerce\Listeners\OrderReturnedNotification;
use Botble\Ecommerce\Listeners\RegisterEcommerceWidget;
use Botble\Ecommerce\Listeners\RenderingSiteMapListener;
use Botble\Ecommerce\Listeners\SaveProductFaqListener;
use Botble\Ecommerce\Listeners\SendDigitalProductEmailAfterOrderCompleted;
use Botble\Ecommerce\Listeners\SendMailsAfterCustomerEmailVerified;
use Botble\Ecommerce\Listeners\SendMailsAfterCustomerRegistered;
use Botble\Ecommerce\Listeners\SendProductFileUpdatedNotification;
use Botble\Ecommerce\Listeners\SendProductReviewsMailAfterOrderCompleted;
use Botble\Ecommerce\Listeners\SendShippingStatusChangedNotification;
use Botble\Ecommerce\Listeners\SendWebhookWhenCartAbandoned;
use Botble\Ecommerce\Listeners\SendWebhookWhenOrderCancelled;
use Botble\Ecommerce\Listeners\SendWebhookWhenOrderCompleted;
use Botble\Ecommerce\Listeners\SendWebhookWhenOrderPlaced;
use Botble\Ecommerce\Listeners\SendWebhookWhenOrderUpdated;
use Botble\Ecommerce\Listeners\SendWebhookWhenPaymentStatusUpdated;
use Botble\Ecommerce\Listeners\SendWebhookWhenShippingStatusUpdated;
use Botble\Ecommerce\Listeners\SyncProductSlug;
use Botble\Ecommerce\Listeners\UpdateInvoiceAndShippingWhenOrderCancelled;
use Botble\Ecommerce\Listeners\UpdateInvoiceWhenOrderCompleted;
use Botble\Ecommerce\Listeners\UpdateProductStockStatus;
use Botble\Ecommerce\Listeners\UpdateProductVariationInfo;
use Botble\Ecommerce\Listeners\UpdateProductView;
use Botble\Ecommerce\Services\HandleApplyCouponService;
use Botble\Ecommerce\Services\HandleApplyProductCrossSaleService;
use Botble\Ecommerce\Services\HandleRemoveCouponService;
use Botble\Slug\Events\UpdatedSlugEvent;
use Botble\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Session\SessionManager;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RenderingSiteMapEvent::class => [
            RenderingSiteMapListener::class,
        ],
        CreatedContentEvent::class => [
            AddLanguageForVariantsListener::class,
            ClearShippingRuleCache::class,
            SaveProductFaqListener::class,
            [SyncProductSlug::class, 'handleCreatedContent'],
        ],
        UpdatedContentEvent::class => [
            AddLanguageForVariantsListener::class,
            ClearShippingRuleCache::class,
            SaveProductFaqListener::class,
            SendWebhookWhenOrderUpdated::class,
            [SyncProductSlug::class, 'handleUpdatedContent'],
        ],
        UpdatedSlugEvent::class => [
            [SyncProductSlug::class, 'handleUpdatedSlug'],
        ],
        DeletedContentEvent::class => [
            ClearShippingRuleCache::class,
        ],
        Registered::class => [
            SendMailsAfterCustomerRegistered::class,
        ],
        CustomerEmailVerified::class => [
            SendMailsAfterCustomerEmailVerified::class,
        ],
        OrderPlacedEvent::class => [
            SendWebhookWhenOrderPlaced::class,
            GenerateInvoiceListener::class,
            OrderCreatedNotification::class,
            MarkCartAsRecovered::class,
        ],
        OrderCreated::class => [
            GenerateInvoiceListener::class,
            OrderCreatedNotification::class,
        ],
        ProductQuantityUpdatedEvent::class => [
            UpdateProductStockStatus::class,
        ],
        OrderCompletedEvent::class => [
            SendDigitalProductEmailAfterOrderCompleted::class,
            SendProductReviewsMailAfterOrderCompleted::class,
            GenerateLicenseCodeAfterOrderCompleted::class,
            UpdateInvoiceWhenOrderCompleted::class,
            SendWebhookWhenOrderCompleted::class,
        ],
        ProductViewed::class => [
            UpdateProductView::class,
        ],
        ShippingStatusChanged::class => [
            SendShippingStatusChangedNotification::class,
            SendWebhookWhenShippingStatusUpdated::class,
        ],
        RenderingAdminWidgetEvent::class => [
            RegisterEcommerceWidget::class,
        ],
        OrderPaymentConfirmedEvent::class => [
            OrderPaymentConfirmedNotification::class,
            SendWebhookWhenPaymentStatusUpdated::class,
        ],
        OrderCancelledEvent::class => [
            OrderCancelledNotification::class,
            UpdateInvoiceAndShippingWhenOrderCancelled::class,
            SendWebhookWhenOrderCancelled::class,
        ],
        OrderReturnedEvent::class => [
            OrderReturnedNotification::class,
        ],
        ProductVariationCreated::class => [
            UpdateProductVariationInfo::class,
        ],
        ProductFileUpdatedEvent::class => [
            SendProductFileUpdatedNotification::class,
        ],
        AbandonedCartReminderEvent::class => [
            SendWebhookWhenCartAbandoned::class,
        ],
    ];

    public function boot(): void
    {
        $events = $this->app['events'];

        $events->listen(
            ['cart.added', 'cart.updated'],
            fn () => $this->app->make(HandleApplyProductCrossSaleService::class)->handle()
        );

        $events->listen(
            ['cart.added', 'cart.removed', 'cart.stored', 'cart.restored', 'cart.updated'],
            function ($cart = null): void {
                $coupon = session('applied_coupon_code');
                if ($coupon) {
                    $this->app->make(HandleRemoveCouponService::class)->execute();
                    if (Cart::count() || ($cart instanceof \Botble\Ecommerce\Cart\Cart && $cart->count())) {
                        $this->app->make(HandleApplyCouponService::class)->execute($coupon);
                    }
                }
            }
        );

        $this->app['events']->listen(Logout::class, function (): void {
            if (get_ecommerce_setting('cart_destroy_on_logout', false)) {
                $this->app->make(SessionManager::class)->forget('cart');
            }
        });
    }
}
