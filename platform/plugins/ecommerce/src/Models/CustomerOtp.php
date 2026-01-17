<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerOtp extends BaseModel
{
    protected $table = 'ec_customer_otps';

    protected $fillable = [
        'customer_id',
        'phone',
        'otp_code',
        'expires_at',
        'verified',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(string $code): bool
    {
        return !$this->isExpired() &&
            !$this->verified &&
            $this->otp_code === $code;
    }

    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    public function hasExceededMaxAttempts(): bool
    {
        return $this->attempts >= 5; // Max 5 attempts
    }

    public function markAsVerified(): void
    {
        $this->update(['verified' => true]);
    }
}
