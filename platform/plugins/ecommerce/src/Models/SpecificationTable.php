<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Facades\AdminHelper;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SpecificationTable extends BaseModel
{
    protected $table = 'ec_specification_tables';

    protected $fillable = [
        'author_type',
        'author_id',
        'name',
        'description',
    ];

    protected static function booted(): void
    {
        if (AdminHelper::isInAdmin(true)) {
            static::addGlobalScope('admin', function ($query): void {
                $query->whereNull('author_id');
            });
        }
    }

    public function groups(): BelongsToMany
    {
        return $this
            ->belongsToMany(SpecificationGroup::class, 'ec_specification_table_group', 'table_id', 'group_id')
            ->withPivot('order');
    }

    public function getSortedAttributesForProduct(?Product $product = null): array
    {
        $result = [];

        foreach ($this->groups as $group) {
            $attributes = $group->specificationAttributes;

            if ($product) {
                $attributes = $attributes->sortBy(function ($attribute) use ($product) {
                    $pivot = $product->specificationAttributes->where('id', $attribute->id)->first();

                    return optional($pivot)->pivot?->order ?? 999;
                });
            }

            $result[] = [
                'group' => $group,
                'attributes' => $attributes,
            ];
        }

        return $result;
    }

    public static function getAttributeDisplayData(
        ?Product $product,
        SpecificationAttribute $attribute,
        ?string $langCode = null
    ): array {
        $specificationAttribute = null;
        $defaultValue = $attribute->default_value;
        $isHidden = false;
        $order = 0;

        if ($product) {
            $specificationAttribute = $product->getSpecificationAttributePivot($attribute);
            if ($specificationAttribute) {
                $defaultValue = $specificationAttribute->pivot->value ?: $attribute->default_value;
                $isHidden = $specificationAttribute->pivot->hidden;
                $order = $specificationAttribute->pivot->order;
            }
        }

        $displayValue = $product
            ? ProductSpecificationAttributeTranslation::getDisplayValue($product, $attribute, $langCode)
            : $defaultValue;

        return [
            'attribute' => $attribute,
            'specificationAttribute' => $specificationAttribute,
            'defaultValue' => $defaultValue,
            'displayValue' => $displayValue,
            'isHidden' => $isHidden,
            'order' => $order,
        ];
    }
}
