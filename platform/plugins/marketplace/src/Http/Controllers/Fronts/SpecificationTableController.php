<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Ecommerce\Http\Controllers\SpecificationTableController as BaseSpecificationTableController;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\SpecificationTable;
use Botble\Marketplace\Forms\SpecificationTableForm;
use Botble\Marketplace\Tables\SpecificationTableTable;

class SpecificationTableController extends BaseSpecificationTableController
{
    protected function getTable(): string
    {
        return SpecificationTableTable::class;
    }

    protected function getForm(): string
    {
        return SpecificationTableForm::class;
    }

    protected function getAdditionalDataForSaving(): array
    {
        return [
            'author_type' => Customer::class,
            'author_id' => auth('customer')->id(),
        ];
    }

    protected function getIndexRouteName(): string
    {
        return 'marketplace.vendor.specification-tables.index';
    }

    protected function getEditRouteName(): string
    {
        return 'marketplace.vendor.specification-tables.edit';
    }

    protected function getSpecificationTable(string $table)
    {
        return SpecificationTable::query()
            ->where([
                'author_type' => Customer::class,
                'author_id' => auth('customer')->id(),
            ])
            ->findOrFail($table);
    }
}
