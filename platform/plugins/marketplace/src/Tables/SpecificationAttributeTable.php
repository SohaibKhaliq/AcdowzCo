<?php

namespace Botble\Marketplace\Tables;

use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Tables\SpecificationAttributeTable as BaseSpecificationAttributeTableTable;
use Botble\Marketplace\Tables\Traits\ForVendor;
use Illuminate\Database\Eloquent\Builder;

class SpecificationAttributeTable extends BaseSpecificationAttributeTableTable
{
    use ForVendor;

    public function setup(): void
    {
        parent::setup();

        $this->queryUsing(function (Builder $query) {
            return $query
                ->where('author_type', Customer::class)
                ->where('author_id', auth('customer')->id())
                ->with('group');
        });
    }

    protected function getCreateRouteName(): string
    {
        return 'marketplace.vendor.specification-attributes.create';
    }

    protected function getEditRouteName(): string
    {
        return 'marketplace.vendor.specification-attributes.edit';
    }

    protected function getDeleteRouteName(): string
    {
        return 'marketplace.vendor.specification-attributes.destroy';
    }
}
