<?php

namespace Botble\Marketplace\Models;

use Botble\ACL\Models\User;
use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Avatar;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\QueryBuilders\StoreQueryBuilder;
use Botble\Ecommerce\Traits\LocationTrait;
use Botble\Marketplace\Enums\StoreStatusEnum;
use Botble\Marketplace\Models\VendorWarning;
use Botble\Marketplace\Models\VendorSubscription;
use Botble\Marketplace\Models\SubscriptionPlan;
use Botble\Media\Facades\RvMedia;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Store extends BaseModel
{
    use LocationTrait;

    protected $table = 'mp_stores';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'country',
        'state',
        'city',
        'customer_id',
        'logo',
        'logo_square',
        'cover_image',
        'description',
        'content',
        'status',
        'company',
        'zip_code',
        'certificate_file',
        'government_id_file',
        'agreement_type',
        'agreement_value',
        'agreement_notes',
        'subscription_plan_id',
        'commission_rate',
        'agreement_accepted_at',
        'agreement_updated_at',
        'agreement_last_updated_by',
        'agreement_history',
        'tax_id',
        'is_verified',
        'verified_at',
        'verified_by',
        'verification_note',
    ];

    protected $casts = [
        'status' => StoreStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'content' => SafeContent::class,
        'address' => SafeContent::class,
        'company' => SafeContent::class,
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'verification_note' => SafeContent::class,
        'agreement_value' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'agreement_accepted_at' => 'datetime',
        'agreement_updated_at' => 'datetime',
        'agreement_history' => 'array',
    ];

    protected static function booted(): void
    {
        static::deleted(function (Store $store): void {
            $store->products()->each(fn(Product $product) => $product->delete());
            $store->discounts()->delete();
            $store->orders()->update(['store_id' => null]);

            $folder = Storage::path($store->upload_folder);
            if (File::isDirectory($folder) && Str::endsWith($store->upload_folder, '/' . ($store->slug ?: $store->id))) {
                File::deleteDirectory($folder);
            }
        });

        static::updating(function (Store $store): void {
            if ($store->getOriginal('status') != $store->status) {
                $status = $store->status;

                if ($status == StoreStatusEnum::BLOCKED) {
                    $store
                        ->products()
                        ->where('status', BaseStatusEnum::PUBLISHED)
                        ->update(['status' => $status]);
                } elseif ($status == StoreStatusEnum::PUBLISHED) {
                    $store
                        ->products()
                        ->where('status', 'blocked')
                        ->update(['status' => $status]);
                }
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withDefault();
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id')->withDefault();
    }

    public function agreementUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agreement_last_updated_by');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->where('is_finished', 1);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class, 'store_id');
    }

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) {
            return RvMedia::getImageUrl($this->logo);
        }

        try {
            return (new Avatar())->create((string)$this->name)->toBase64();
        } catch (Exception) {
            return RvMedia::getDefaultImage();
        }
    }

    public function reviews(): HasMany
    {
        return $this
            ->hasMany(Product::class)
            ->join('ec_reviews', 'ec_products.id', '=', 'ec_reviews.product_id');
    }

    protected function uploadFolder(): Attribute
    {
        return Attribute::make(
            get: function () {
                $folder = $this->id ? 'stores/' . ($this->slug ?: $this->id) : 'stores';

                return apply_filters('marketplace_store_upload_folder', $folder, $this);
            }
        );
    }

    protected function badge(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->is_verified) {
                    return '';
                }

                return view('plugins/marketplace::partials.verified-badge', ['size' => 'sm'])->render();
            }
        );
    }

    public static function handleCommissionEachCategory(array $data): array
    {
        $commissions = [];
        CategoryCommission::query()->truncate();
        foreach ($data as $datum) {
            if (! $datum['categories']) {
                continue;
            }

            $categories = json_decode($datum['categories'], true);

            if (! is_array($categories) || ! count($categories)) {
                continue;
            }

            foreach ($categories as $category) {
                $commission = CategoryCommission::query()->firstOrNew([
                    'product_category_id' => $category['id'],
                ]);

                if (! $commission) {
                    continue;
                }

                $commission->commission_percentage = $datum['commission_fee'];
                $commission->save();
                $commissions[] = $commission;
            }
        }

        return $commissions;
    }

    public static function getCommissionEachCategory(): array
    {
        $commissions = CategoryCommission::query()->with(['category'])->get();
        $data = [];
        foreach ($commissions as $commission) {
            if (! $commission->category) {
                continue;
            }

            $data[$commission->commission_percentage]['commission_fee'] = $commission->commission_percentage;
            $data[$commission->commission_percentage]['categories'][] = [
                'id' => $commission->product_category_id,
                'value' => $commission->category->name,
            ];
        }

        return $data;
    }

    public function warnings(): HasMany
    {
        return $this->hasMany(VendorWarning::class, 'store_id');
    }

    public function unacknowledgedWarnings(): HasMany
    {
        return $this->hasMany(VendorWarning::class, 'store_id')->where('acknowledged', false);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(VendorSubscription::class, 'store_id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(VendorSubscription::class, 'store_id')
            ->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    public function canBeVerified(): bool
    {
        if (!$this->hasActiveSubscription()) {
            return false;
        }

        $subscription = $this->activeSubscription()->first();
        return $subscription && $subscription->plan && $subscription->plan->verified_eligible;
    }

    public function shippingCountries(): BelongsToMany
    {
        return $this->belongsToMany(\Botble\Location\Models\Country::class, 'mp_store_shipping_countries', 'store_id', 'country_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function activeShippingCountries(): BelongsToMany
    {
        return $this->shippingCountries()->wherePivot('is_active', true);
    }

    public function canShipToCountry($countryId): bool
    {
        if (!$countryId) {
            return true;
        }

        return $this->activeShippingCountries()->where('countries.id', $countryId)->exists();
    }

    public function newEloquentBuilder($query): StoreQueryBuilder
    {
        return new StoreQueryBuilder($query);
    }

    public function getMetaData(string $key, bool $single = false): array|string|null
    {
        if (in_array($key, ['cover_image', 'background'])) {
            return $this->cover_image;
        }

        return parent::getMetaData($key, $single);
    }

    /**
     * Calculate commission for an order amount
     */
    public function calculateCommission(float $orderAmount): float
    {
        if ($this->agreement_type === 'flat_fee') {
            return 0; // Flat fee vendors don't pay per-order commission
        }

        $rate = $this->commission_rate ?: $this->agreement_value;
        return round(($orderAmount * $rate) / 100, 2);
    }

    /**
     * Check if vendor agreement is accepted
     */
    public function hasAcceptedAgreement(): bool
    {
        return !is_null($this->agreement_accepted_at);
    }

    /**
     * Get agreement display text
     */
    public function getAgreementDisplayText(): string
    {
        if ($this->agreement_type === 'flat_fee') {
            return __('Flat Fee: :amount per period', ['amount' => format_price($this->agreement_value)]);
        }

        $rate = $this->commission_rate ?: $this->agreement_value;
        return __('Commission: :rate% per sale', ['rate' => number_format($rate, 2)]);
    }

    /**
     * Update vendor agreement and track history
     */
    public function updateAgreement(array $data, ?int $updatedBy = null): bool
    {
        $oldAgreement = [
            'type' => $this->agreement_type,
            'value' => $this->agreement_value,
            'commission_rate' => $this->commission_rate,
            'subscription_plan_id' => $this->subscription_plan_id,
            'notes' => $this->agreement_notes,
        ];

        $newAgreement = [
            'type' => $data['agreement_type'] ?? $this->agreement_type,
            'value' => $data['agreement_value'] ?? $this->agreement_value,
            'commission_rate' => $data['commission_rate'] ?? $this->commission_rate,
            'subscription_plan_id' => $data['subscription_plan_id'] ?? $this->subscription_plan_id,
            'notes' => $data['agreement_notes'] ?? $this->agreement_notes,
        ];

        // Only update if there are actual changes
        if ($oldAgreement === $newAgreement) {
            return false;
        }

        // Add to history
        $history = $this->agreement_history ?: [];
        $history[] = [
            'old' => $oldAgreement,
            'new' => $newAgreement,
            'updated_by' => $updatedBy,
            'updated_at' => now()->toISOString(),
            'reason' => $data['update_reason'] ?? null,
        ];

        $this->fill([
            'agreement_type' => $newAgreement['type'],
            'agreement_value' => $newAgreement['value'],
            'commission_rate' => $newAgreement['commission_rate'],
            'subscription_plan_id' => $newAgreement['subscription_plan_id'],
            'agreement_notes' => $newAgreement['notes'],
            'agreement_history' => $history,
            'agreement_updated_at' => now(),
            'agreement_last_updated_by' => $updatedBy,
        ]);

        return $this->save();
    }

    /**
     * Get agreement history in human-readable format
     */
    public function getAgreementHistoryFormatted(): array
    {
        if (!$this->agreement_history) {
            return [];
        }

        return collect($this->agreement_history)->map(function ($entry) {
            return [
                'date' => $entry['updated_at'],
                'updated_by' => $entry['updated_by'] ? User::find($entry['updated_by'])?->name : 'System',
                'changes' => $this->formatAgreementChanges($entry['old'], $entry['new']),
                'reason' => $entry['reason'] ?? null,
            ];
        })->toArray();
    }

    /**
     * Format agreement changes for display
     */
    protected function formatAgreementChanges(array $old, array $new): array
    {
        $changes = [];

        if ($old['type'] !== $new['type']) {
            $changes[] = __('Type changed from :old to :new', [
                'old' => $old['type'],
                'new' => $new['type'],
            ]);
        }

        if ($old['value'] != $new['value']) {
            $changes[] = __('Value changed from :old to :new', [
                'old' => $old['value'],
                'new' => $new['value'],
            ]);
        }

        if ($old['commission_rate'] != $new['commission_rate']) {
            $changes[] = __('Commission rate changed from :old% to :new%', [
                'old' => $old['commission_rate'],
                'new' => $new['commission_rate'],
            ]);
        }

        return $changes;
    }

    /**
     * Accept vendor agreement
     */
    public function acceptAgreement(): bool
    {
        $this->agreement_accepted_at = now();
        return $this->save();
    }

    /**
     * Check if agreement needs renewal
     */
    public function needsAgreementRenewal(): bool
    {
        if (!$this->agreement_updated_at) {
            return false;
        }

        // Check if agreement was updated after vendor accepted it
        return $this->agreement_updated_at > $this->agreement_accepted_at;
    }
}
