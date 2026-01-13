<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Avatar;
use Botble\Media\Facades\RvMedia;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Review extends BaseModel
{
    protected $table = 'ec_reviews';

    protected $fillable = [
        'product_id',
        'customer_id',
        'customer_name',
        'customer_email',
        'star',
        'comment',
        'status',
        'images',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'images' => 'array',
        'order_created_at' => 'datetime',
    ];

    public static function hasUserReviewed(int|string $customerId, int|string $productId): bool
    {
        return static::query()
            ->where([
                'customer_id' => $customerId,
                'product_id' => $productId,
            ])
            ->exists();
    }

    public static function getUserReview(int|string $customerId, int|string $productId): ?Review
    {
        return static::query()
            ->where([
                'customer_id' => $customerId,
                'product_id' => $productId,
            ])
            ->first();
    }

    protected static function booted(): void
    {
        static::creating(function (Review $review): void {
            if (! $review->images || ! is_array($review->images) || ! count($review->images)) {
                $review->images = null;
            }
        });

        static::created(function (Review $review): void {
            if ($review->product_id && $review->status == BaseStatusEnum::PUBLISHED) {
                if ($product = Product::query()->find($review->product_id)) {
                    $product->updateReviewsCache();
                }
            }
        });

        static::updating(function (Review $review): void {
            if (! $review->images || ! is_array($review->images) || ! count($review->images)) {
                $review->images = null;
            }
        });

        static::updated(function (Review $review): void {
            if ($review->product_id && ($review->isDirty('status') || $review->isDirty('star'))) {
                if ($product = Product::query()->find($review->product_id)) {
                    $product->updateReviewsCache();
                }
            }
        });

        static::deleting(fn (Review $review) => $review->reply()->delete());

        static::deleted(function (Review $review): void {
            if ($review->product_id) {
                if ($product = Product::query()->find($review->product_id)) {
                    $product->updateReviewsCache();
                }
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id')->withDefault();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    public function reply(): HasOne
    {
        return $this->hasOne(ReviewReply::class);
    }

    protected function productName(): Attribute
    {
        return Attribute::get(fn () => $this->product->name);
    }

    protected function userName(): Attribute
    {
        return Attribute::get(fn () => $this->user->name ?: $this->customer_name);
    }

    protected function displayName(): Attribute
    {
        return Attribute::get(function () {
            $customerName = $this->userName;

            if (! get_ecommerce_setting('show_customer_full_name', true)) {
                $customerNameCharCount = strlen($customerName);

                if ($customerNameCharCount > 7) {
                    $customerName = Str::mask($customerName, '*', $customerNameCharCount - 5, 5);
                } elseif ($customerNameCharCount > 3) {
                    $customerName = Str::mask($customerName, '*', $customerNameCharCount - 3, 3);
                } else {
                    $customerName = Str::mask($customerName, '*', 1, -1);
                }
            }

            return $customerName;
        });
    }

    protected function orderCreatedAt(): Attribute
    {
        return Attribute::get(fn () => $this->user->orders()->first()?->created_at);
    }

    protected function isApproved(): Attribute
    {
        return Attribute::get(fn () => $this->status == BaseStatusEnum::PUBLISHED);
    }

    protected function customerAvatarUrl(): Attribute
    {
        return Attribute::get(function () {
            if ($this->user->avatar) {
                return RvMedia::getImageUrl($this->user->avatar, 'thumb');
            }

            if ($defaultAvatar = get_ecommerce_setting('customer_default_avatar')) {
                return RvMedia::getImageUrl($defaultAvatar);
            }

            try {
                return (new Avatar())->create(Str::ucfirst($this->user->name ?: $this->customer_name))->toBase64();
            } catch (Exception) {
                return RvMedia::getDefaultImage();
            }
        });
    }
}
