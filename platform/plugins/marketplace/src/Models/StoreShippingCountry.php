<?php

namespace Botble\Marketplace\Models;

use Botble\Base\Models\BaseModel;
use Botble\Location\Models\Country;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreShippingCountry extends BaseModel
{
    protected $table = 'mp_store_shipping_countries';

    protected $fillable = [
        'store_id',
        'country_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
