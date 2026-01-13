<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResellerClick extends BaseModel
{
    protected $table = 'ec_reseller_clicks';

    public $timestamps = false;

    protected $fillable = [
        'reseller_id',
        'product_id',
        'ip_address',
        'user_agent',
        'referrer_url',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function reseller(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'reseller_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
