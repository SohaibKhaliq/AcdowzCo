<?php

namespace Botble\Ecommerce\Events;

use Botble\Base\Events\Event;
use Botble\Ecommerce\Models\OrderProduct;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderProductCreatedEvent extends Event
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public OrderProduct $orderProduct)
    {
    }
}
