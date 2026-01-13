<?php

namespace Botble\Marketplace\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Enums\CustomerStatusEnum;
use Botble\Ecommerce\Models\Customer;
use Botble\Marketplace\Enums\StoreStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Vendor extends Customer
{
    protected $table = 'ec_customers';

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'dob',
        'phone',
        'description',
        'gender',
        'status',
        'private_notes',
        'is_vendor',
        'vendor_verified_at',
        'vendor_info',
    ];

    protected $casts = [
        'dob' => 'date',
        'confirmed_at' => 'datetime',
        'vendor_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'vendor_info' => SafeContent::class,
        'is_vendor' => 'boolean',
        'status' => CustomerStatusEnum::class,
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('vendor', function (Builder $builder) {
            $builder->where('is_vendor', true);
        });

        static::creating(function ($vendor) {
            $vendor->is_vendor = true;
        });
    }

    public function store(): HasOne
    {
        return $this->hasOne(Store::class, 'customer_id');
    }

    public function revenues(): HasMany
    {
        return $this->hasMany(Revenue::class, 'customer_id');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class, 'customer_id');
    }

    public function getTotalRevenueAttribute(): float
    {
        return $this->revenues()->sum('sub_amount');
    }

    public function getTotalEarningsAttribute(): float
    {
        return $this->revenues()->sum('amount');
    }

    public function getTotalWithdrawalsAttribute(): float
    {
        return $this->withdrawals()
            ->whereIn('status', ['completed', 'pending'])
            ->sum('amount');
    }

    public function getCompletedWithdrawalsAttribute(): float
    {
        return $this->withdrawals()
            ->where('status', 'completed')
            ->sum('amount');
    }

    public function getPendingWithdrawalsAttribute(): float
    {
        return $this->withdrawals()
            ->where('status', 'pending')
            ->sum('amount');
    }

    public function getBalanceAttribute(): float
    {
        return $this->total_earnings - $this->total_withdrawals;
    }

    public function getAvailableBalanceAttribute(): float
    {
        return $this->total_earnings - $this->completed_withdrawals - $this->pending_withdrawals;
    }

    public function getIsVerifiedAttribute(): bool
    {
        return ! is_null($this->vendor_verified_at);
    }

    public function getHasStoreAttribute(): bool
    {
        return $this->store()->exists();
    }

    public function getProductsCountAttribute(): int
    {
        return $this->store ? $this->store->products()->count() : 0;
    }

    public function getOrdersCountAttribute(): int
    {
        return $this->store ? $this->store->orders()->count() : 0;
    }

    public function getStoreStatusAttribute(): ?StoreStatusEnum
    {
        if (! $this->store) {
            return null;
        }

        return $this->store->status;
    }

    public function getStoreNameAttribute(): ?string
    {
        return $this->store ? $this->store->name : null;
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('vendor_verified_at');
    }

    public function scopeUnverified(Builder $query): Builder
    {
        return $query->whereNull('vendor_verified_at');
    }

    public function scopeWithStore(Builder $query): Builder
    {
        return $query->whereHas('store');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->verified()
            ->whereHas('store', function ($query) {
                $query->where('status', StoreStatusEnum::PUBLISHED);
            });
    }

    public function scopeWithStatistics(Builder $query): Builder
    {
        return $query->addSelect([
            DB::raw('(SELECT COUNT(*) FROM ec_products WHERE ec_products.store_id = (SELECT id FROM mp_stores WHERE mp_stores.customer_id = ec_customers.id LIMIT 1)) as products_count'),
            DB::raw('(SELECT COUNT(*) FROM ec_orders WHERE ec_orders.store_id = (SELECT id FROM mp_stores WHERE mp_stores.customer_id = ec_customers.id LIMIT 1)) as orders_count'),
            DB::raw('(SELECT SUM(sub_amount) FROM mp_customer_revenues WHERE mp_customer_revenues.customer_id = ec_customers.id) as total_revenue'),
            DB::raw('(SELECT SUM(amount) FROM mp_customer_revenues WHERE mp_customer_revenues.customer_id = ec_customers.id) as total_earnings'),
            DB::raw('(SELECT SUM(amount) FROM mp_customer_withdrawals WHERE mp_customer_withdrawals.customer_id = ec_customers.id AND status IN ("completed", "pending")) as total_withdrawals'),
        ]);
    }

    public function verify(): bool
    {
        $this->vendor_verified_at = Carbon::now();

        return $this->save();
    }

    public function unverify(): bool
    {
        $this->vendor_verified_at = null;

        return $this->save();
    }

    public function createWithdrawal(array $data): ?Withdrawal
    {
        if ($this->available_balance < ($data['amount'] ?? 0)) {
            return null;
        }

        return $this->withdrawals()->create($data);
    }

    public function getFormattedVerificationDateAttribute(): ?string
    {
        return $this->vendor_verified_at
            ? BaseHelper::formatDate($this->vendor_verified_at, 'Y-m-d H:i')
            : null;
    }

    public function getVerificationBadgeAttribute(): string
    {
        if ($this->is_verified) {
            return '<span class="badge bg-green text-green-fg">' . trans('plugins/marketplace::marketplace.vendor_verified') . '</span>';
        }

        return '<span class="badge bg-yellow text-yellow-fg">' . trans('plugins/marketplace::marketplace.vendor_not_verified') . '</span>';
    }

    public function getDisplayNameAttribute(): string
    {
        $name = $this->name;

        if ($this->store_name) {
            $name .= ' (' . $this->store_name . ')';
        }

        return $name;
    }
}
