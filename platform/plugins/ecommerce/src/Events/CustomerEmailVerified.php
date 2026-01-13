<?php

namespace Botble\Ecommerce\Events;

use Botble\Base\Events\Event;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerEmailVerified extends Event
{
    use SerializesModels;
    use Dispatchable;

    public function __construct(public Customer $customer)
    {
    }
}
