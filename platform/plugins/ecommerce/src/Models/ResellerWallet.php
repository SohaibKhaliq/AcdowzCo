<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResellerWallet extends BaseModel
{
    protected $table = 'ec_reseller_wallets';

    protected $fillable = [
        'reseller_id',
        'balance',
        'is_blocked',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_blocked' => 'boolean',
    ];

    public function reseller(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'reseller_id');
    }

    public function penalties(): HasMany
    {
        return $this->hasMany(ResellerPenalty::class, 'reseller_id', 'reseller_id');
    }

    public function isNegative(): bool
    {
        return $this->balance < 0;
    }

    public function applyPenalty(float $amount): void
    {
        $this->balance -= $amount;
        
        if ($this->balance < 0) {
            $this->is_blocked = true;
        }
        
        $this->save();
    }

    public function addFunds(float $amount): void
    {
        $this->balance += $amount;
        
        if ($this->balance >= 0) {
            $this->is_blocked = false;
        }
        
        $this->save();
    }

    public function blockWallet(): bool
    {
        $this->is_blocked = true;
        return $this->save();
    }

    public function unblockWallet(): bool
    {
        if ($this->balance >= 0) {
            $this->is_blocked = false;
            return $this->save();
        }
        
        return false;
    }
}
