<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Location\Models\Country;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerCountry extends BaseModel
{
    protected $table = 'ec_customer_countries';

    protected $fillable = [
        'customer_id',
        'country_id',
        'detected_by',
        'confirmed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
