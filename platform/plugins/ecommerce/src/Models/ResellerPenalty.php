<?php

namespace Botble\Ecommerce\Models;

use Botble\ACL\Models\User;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResellerPenalty extends BaseModel
{
    protected $table = 'ec_reseller_penalties';

    protected $fillable = [
        'reseller_id',
        'order_id',
        'product_id',
        'amount',
        'reason',
        'status',
        'issued_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function reseller(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'reseller_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(ResellerWallet::class, 'reseller_id', 'reseller_id');
    }

    public function scopeApplied($query)
    {
        return $query->where('status', 'applied');
    }

    public function scopeReversed($query)
    {
        return $query->where('status', 'reversed');
    }

    public function reverse(): bool
    {
        if ($this->status === 'reversed') {
            return false;
        }

        $wallet = $this->wallet()->first();
        if ($wallet) {
            $wallet->addFunds($this->amount);
        }

        $this->status = 'reversed';
        return $this->save();
    }
}
