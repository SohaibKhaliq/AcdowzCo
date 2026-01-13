<?php

namespace Botble\Marketplace\Tables;

use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Tables\SpecificationTableTable as BaseSpecificationTableTable;
use Botble\Marketplace\Tables\Traits\ForVendor;
use Illuminate\Database\Eloquent\Builder;

class SpecificationTableTable extends BaseSpecificationTableTable
{
    use ForVendor;

    public function setup(): void
    {
        parent::setup();

        $this->queryUsing(function (Builder $query) {
            return $query
                ->where('author_type', Customer::class)
                ->where('author_id', auth('customer')->id());
        });
    }

    protected function getCreateRouteName(): string
    {
        return 'marketplace.vendor.specification-tables.create';
    }

    protected function getEditRouteName(): string
    {
        return 'marketplace.vendor.specification-tables.edit';
    }

    protected function getDeleteRouteName(): string
    {
        return 'marketplace.vendor.specification-tables.destroy';
    }
}
