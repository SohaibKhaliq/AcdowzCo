<?php

namespace Botble\Marketplace\Models\Scopes;

use Botble\Marketplace\Enums\StoreStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class HideProductsByLockedVendorScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (is_in_admin()) {
            return;
        }

        $builder
            ->where(function ($query): void {
                $query
                    ->where('ec_products.is_variation', true)
                    ->orWhereNull('ec_products.store_id')
                    ->orWhereDoesntHave('store')
                    ->orWhereHas('store', function ($query): void {
                        $query
                            ->where('status', StoreStatusEnum::PUBLISHED);
                    });
            });
    }
}
