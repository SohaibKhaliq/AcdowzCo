<?php

namespace Botble\Location\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends BaseModel
{
    protected $table = 'countries';

    protected $fillable = [
        'name',
        'nationality',
        'code',
        'phone_code',
        'iso_code',
        'order',
        'is_default',
        'status',
        'image',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'nationality' => SafeContent::class,
        'code' => SafeContent::class,
        'phone_code' => SafeContent::class,
        'iso_code' => SafeContent::class,
        'is_default' => 'bool',
        'order' => 'int',
    ];

    protected static function booted(): void
    {
        static::deleted(function (Country $country): void {
            $country->states()->delete();
            $country->cities()->delete();
        });
    }

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(\Botble\Ecommerce\Models\ProductCountry::class);
    }

    public function stores(): HasMany
    {
        return $this->hasMany(\Botble\Marketplace\Models\StoreShippingCountry::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published');
    }
}
