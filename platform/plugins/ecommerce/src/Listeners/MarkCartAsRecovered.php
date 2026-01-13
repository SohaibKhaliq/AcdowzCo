<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Events\OrderPlacedEvent;
use Botble\Ecommerce\Services\AbandonedCartService;

class MarkCartAsRecovered
{
    public function __construct(
        protected AbandonedCartService $abandonedCartService
    ) {
    }

    public function handle(OrderPlacedEvent $event): void
    {
        $this->abandonedCartService->markCartAsRecovered($event->order);
    }
}
