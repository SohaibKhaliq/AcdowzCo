<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Location\Models\Country;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCountry extends BaseModel
{
    protected $table = 'ec_product_countries';

    protected $fillable = [
        'product_id',
        'country_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
