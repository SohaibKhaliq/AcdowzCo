<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Enums\ProductLicenseCodeStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductLicenseCode extends BaseModel
{
    protected $table = 'ec_product_license_codes';

    protected $fillable = [
        'product_id',
        'license_code',
        'status',
        'assigned_order_product_id',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'status' => ProductLicenseCodeStatusEnum::class,
    ];

    protected static function booted(): void
    {
        // License codes can now be created for both main products and variations
        // This allows each variation to have its own specific license codes
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function assignedOrderProduct(): BelongsTo
    {
        return $this->belongsTo(OrderProduct::class, 'assigned_order_product_id');
    }

    public function isAvailable(): bool
    {
        return $this->status->getValue() === ProductLicenseCodeStatusEnum::AVAILABLE;
    }

    public function isUsed(): bool
    {
        return $this->status->getValue() === ProductLicenseCodeStatusEnum::USED;
    }

    public function markAsUsed(OrderProduct $orderProduct): void
    {
        $this->update([
            'status' => ProductLicenseCodeStatusEnum::USED,
            'assigned_order_product_id' => $orderProduct->id,
            'assigned_at' => now(),
        ]);
    }

    public function markAsAvailable(): void
    {
        $this->update([
            'status' => ProductLicenseCodeStatusEnum::AVAILABLE,
            'assigned_order_product_id' => null,
            'assigned_at' => null,
        ]);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', ProductLicenseCodeStatusEnum::AVAILABLE);
    }

    public function scopeUsed(Builder $query): Builder
    {
        return $query->where('status', ProductLicenseCodeStatusEnum::USED);
    }

    public function scopeForProduct(Builder $query, int $productId): Builder
    {
        return $query->where('product_id', $productId);
    }
}
