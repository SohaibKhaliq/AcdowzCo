<?php

namespace Botble\Ecommerce\Models;

use Botble\ACL\Models\User;
use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Enums\ResellerApplicationStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResellerApplication extends BaseModel
{
    protected $table = 'ec_reseller_applications';

    protected $fillable = [
        'customer_id',
        'notes',
        'status',
        'rejection_reason',
        'handled_by',
    ];

    protected $casts = [
        'status' => ResellerApplicationStatusEnum::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id')->withDefault();
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by')->withDefault();
    }
}
