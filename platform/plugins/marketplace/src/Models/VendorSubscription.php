<?php

namespace Botble\Marketplace\Models;

use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Models\Customer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorSubscription extends BaseModel
{
    protected $table = 'mp_vendor_subscriptions';

    protected $fillable = [
        'customer_id',
        'store_id',
        'plan_id',
        'starts_at',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function daysRemaining(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->expires_at);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '>', Carbon::now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', Carbon::now());
    }

    public function markAsExpired(): bool
    {
        $this->status = 'expired';
        return $this->save();
    }
}
