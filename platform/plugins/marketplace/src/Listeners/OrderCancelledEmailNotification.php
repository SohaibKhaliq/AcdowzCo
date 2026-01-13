<?php

namespace Botble\Marketplace\Listeners;

use Botble\Base\Facades\EmailHandler;
use Botble\Ecommerce\Events\OrderCancelledEvent;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Marketplace\Facades\MarketplaceHelper;

class OrderCancelledEmailNotification
{
    public function handle(OrderCancelledEvent $event): void
    {
        $order = $event->order;

        if (! $order->store || ! $order->store->email) {
            return;
        }

        $mailer = EmailHandler::setModule(MARKETPLACE_MODULE_SCREEN_NAME);

        if ($mailer->templateEnabled('order_cancellation_to_vendor')) {
            OrderHelper::setEmailVariables($order);
            MarketplaceHelper::setEmailVendorVariables($order);

            $mailer->sendUsingTemplate('order_cancellation_to_vendor', $order->store->email);
        }
    }
}
