<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

class InvoiceItem extends BaseModel
{
    protected $table = 'ec_invoice_items';

    protected $fillable = [
        'invoice_id',
        'reference_type',
        'reference_id',
        'name',
        'description',
        'image',
        'qty',
        'price',
        'sub_total',
        'tax_amount',
        'discount_amount',
        'amount',
        'metadata',
        'options',
    ];

    protected $casts = [
        'sub_total' => 'float',
        'tax_amount' => 'float',
        'discount_amount' => 'float',
        'amount' => 'float',
        'metadata' => 'json',
        'paid_at' => 'datetime',
        'options' => 'json',
        'name' => SafeContent::class,
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    protected function amountFormat(): Attribute
    {
        return Attribute::get(fn () => format_price($this->price));
    }

    protected function totalFormat(): Attribute
    {
        return Attribute::get(fn () => format_price($this->price * $this->qty));
    }

    public function productOptionsImplode(): Attribute
    {
        return Attribute::get(function () {
            $options = $this->product_options_array;

            if (! $options) {
                return '';
            }

            return '(' . implode(', ', Arr::map($options, function ($item) use ($options): string {
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
