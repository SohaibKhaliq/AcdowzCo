<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResellerOrder extends BaseModel
{
    protected $table = 'ec_reseller_orders';

    protected $fillable = [
        'reseller_id',
        'order_id',
        'order_amount',
        'commission_rate',
        'commission_earned',
        'status',
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_earned' => 'decimal:2',
    ];

    public function reseller(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'reseller_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function approve(): bool
    {
        $this->status = 'approved';
        return $this->save();
    }

    public function markAsPaid(): bool
    {
        $this->status = 'paid';
        return $this->save();
    }
}
