<?php

namespace Botble\Ecommerce\Events;

use Botble\Base\Events\Event;
use Botble\Ecommerce\Models\AbandonedCart;
use Illuminate\Queue\SerializesModels;

class AbandonedCartReminderEvent extends Event
{
    use SerializesModels;

    public function __construct(public AbandonedCart $abandonedCart)
    {
    }
}
