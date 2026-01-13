<?php

namespace Botble\Marketplace\Models;

use Botble\Base\Models\BaseModel;
use Botble\ACL\Models\User;
use Botble\Marketplace\Enums\WarningLevelEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorWarning extends BaseModel
{
    protected $table = 'mp_vendor_warnings';

    protected $fillable = [
        'store_id',
        'issued_by',
        'title',
        'content',
        'severity',
        'acknowledged',
        'acknowledged_at',
        'email_sent',
    ];

    protected $casts = [
        'severity' => WarningLevelEnum::class,
        'acknowledged' => 'boolean',
        'acknowledged_at' => 'datetime',
        'email_sent' => 'boolean',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by')->withDefault();
    }

    public function acknowledge(): bool
    {
        $this->acknowledged = true;
        $this->acknowledged_at = now();

        return $this->save();
    }
}
