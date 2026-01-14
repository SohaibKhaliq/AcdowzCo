<?php

namespace Botble\Marketplace\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends BaseModel
{
    protected $table = 'mp_subscription_plans';

    protected $fillable = [
        'name',
        'duration',
        'price',
        'priority_boost',
        'verified_eligible',
        'description',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'priority_boost' => 'boolean',
        'verified_eligible' => 'boolean',
        'status' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(VendorSubscription::class, 'plan_id');
    }

    public function getDurationInDaysAttribute(): int
    {
        return match ($this->duration) {
            'weekly' => 7,
            'monthly' => 30,
            default => 30,
        };
    }

    public function isActive(): bool
    {
        return $this->status === true;
    }
}
