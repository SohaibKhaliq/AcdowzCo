<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Media\Facades\RvMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class OrderProduct extends BaseModel
{
    protected $table = 'ec_order_product';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_image',
        'qty',
        'weight',
        'price',
        'tax_amount',
        'options',
        'product_options',
        'restock_quantity',
        'product_type',
        'license_code',
        'downloaded_at',
    ];

    protected $casts = [
        'options' => 'json',
        'product_options' => 'json',
        'downloaded_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleted(function (OrderProduct $orderProduct): void {
            // When an order product is deleted, set the assigned_order_product_id to null
            // for any license codes that were assigned to this order product
            ProductLicenseCode::query()
                ->where('assigned_order_product_id', $orderProduct->id)
                ->update([
                    'assigned_order_product_id' => null,
                    'status' => 'available',
                    'assigned_at' => null,
                ]);
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withDefault();
    }

    public function productFiles(): HasMany
    {
        return $this->hasMany(ProductFile::class, 'product_id', 'product_id');
    }

    public function totalFormat(): Attribute
    {
        return Attribute::get(function () {
            return format_price(EcommerceHelper::roundPrice($this->price * $this->qty));
        });
    }

    public function productImageUrl(): Attribute
    {
        return Attribute::get(fn () => RvMedia::getImageUrl($this->product_image, 'thumb', default: RvMedia::getDefaultImage()));
    }

    protected function amountFormat(): Attribute
    {
        return Attribute::get(fn () => format_price($this->price));
    }

    protected function productFileExternalCount(): Attribute
    {
        return Attribute::get(fn () => $this->productFiles->filter(fn (ProductFile $file) => $file->is_external_link)->count());
    }

    protected function productFileInternalCount(): Attribute
    {
        return Attribute::get(fn () => $this->productFiles->filter(fn (ProductFile $file) => ! $file->is_external_link)->count());
    }

    public function isTypeDigital(): bool
    {
        return isset($this->attributes['product_type']) && $this->attributes['product_type'] == ProductTypeEnum::DIGITAL;
    }

    public function hasFiles(): bool
    {
        return $this->product_file_internal_count > 0 || $this->product_file_external_count > 0;
    }

    protected function downloadToken(): Attribute
    {
        return Attribute::get(fn () => $this->isTypeDigital() ? ($this->order->id . '-' . $this->order->token . '-' . $this->id) : null);
    }

    protected function downloadHash(): Attribute
    {
        return Attribute::get(fn () => $this->download_token ? Hash::make($this->download_token) : null);
    }

    protected function downloadHashUrl(): Attribute
    {
        return Attribute::get(fn () => $this->download_hash ? route('public.digital-products.download', [
            'id' => $this->id,
            'hash' => $this->download_hash,
        ]) : null);
    }

    protected function downloadExternalUrl(): Attribute
    {
        return Attribute::get(fn () => $this->download_hash ? route('public.digital-products.download', [
            'id' => $this->id,
            'hash' => $this->download_hash,
            'external' => true,
        ]) : null);
    }

    protected function priceWithTax(): Attribute
    {
        return Attribute::get(fn () => $this->price + $this->tax_amount);
    }

    protected function totalPriceWithTax(): Attribute
    {
        return Attribute::get(fn () => $this->price_with_tax * $this->qty);
    }

    protected function licenseCodesArray(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->license_code) {
                return [];
            }

            // Try to decode as JSON first
            $decoded = json_decode($this->license_code, true);

            // If it's a valid JSON array, return it
            if (is_array($decoded)) {
                return $decoded;
            }

            // Otherwise, return the single license code as an array for consistency
            return [$this->license_code];
        });
    }

    public function productOptionsImplode(): Attribute
    {
        return Attribute::get(function () {
            $options = $this->product_options_array;

            if (! $options) {
                return '';
            }

            return '(' . implode(', ', Arr::map($options, function ($item) use ($options) {
                return implode(': ', [
                    $item['label'],
                    $item['value'] . ($item['affect_price'] ? ' (+' . $item['affect_price'] . ')' : ''),
                ]);
            })) . ')';
        });
    }

    public function productOptionsArray(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->options) {
                return '';
            }

            $options = Arr::get($this->options, 'options');

            if (! $options) {
                return '';
            }

            return Arr::map(Arr::get($options, 'optionInfo'), function ($item, $key) use ($options) {
                $affectedPrice = Arr::get($options, "optionCartValue.$key.0.affect_price");

                return [
                    'label' => $item,
                    'value' => Arr::get($options, "optionCartValue.$key.0.option_value"),
                    'affect_price' => $affectedPrice ? format_price($affectedPrice) : '',
                ];
            });
        });
    }
}
