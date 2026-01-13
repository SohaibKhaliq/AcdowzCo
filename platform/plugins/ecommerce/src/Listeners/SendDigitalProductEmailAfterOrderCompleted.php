<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Events\OrderCompletedEvent;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDigitalProductEmailAfterOrderCompleted implements ShouldQueue
{
    public function handle(OrderCompletedEvent $event): void
    {
        $order = $event->order;

        if (! ($order instanceof Order)) {
            return;
        }

        OrderHelper::sendEmailForDigitalProducts($order);
    }
}
