<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\Html;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductLabel extends BaseModel
{
    protected $table = 'ec_product_labels';

    protected $fillable = [
        'name',
        'color',
        'text_color',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'ec_product_label_products', 'product_label_id', 'product_id');
    }

    protected static function booted(): void
    {
        static::deleted(function (ProductLabel $label): void {
            $label->products()->detach();
        });
    }

    protected function cssStyles(): Attribute
    {
        return Attribute::get(function () {
            $styles = [];
            if ($this->color) {
                $styles[] = "background-color: {$this->color} !important;";
            }

            if ($this->text_color) {
                $styles[] = "color: {$this->text_color} !important;";
            }

            return Html::attributes(['style' => implode(' ', $styles)]);
        });
    }
}
