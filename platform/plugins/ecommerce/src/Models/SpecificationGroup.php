<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Facades\AdminHelper;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpecificationGroup extends BaseModel
{
    protected $table = 'ec_specification_groups';

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

        static::deleting(function (self $group): void {
            $group->specificationAttributes()->delete();
        });
    }

    public function specificationAttributes(): HasMany
    {
        return $this->hasMany(SpecificationAttribute::class, 'group_id');
    }
}
