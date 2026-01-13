<?php

namespace Botble\Ecommerce\Events;

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductFile;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ProductFileUpdatedEvent
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @param  Collection<ProductFile>  $productFiles
     */
    public function __construct(public Product $product, public Collection $productFiles)
    {
    }
}
