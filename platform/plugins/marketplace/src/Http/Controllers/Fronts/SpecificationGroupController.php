<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Ecommerce\Http\Controllers\SpecificationGroupController as BaseSpecificationGroupController;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\SpecificationGroup;
use Botble\Marketplace\Forms\SpecificationGroupForm;
use Botble\Marketplace\Tables\SpecificationGroupTable;

class SpecificationGroupController extends BaseSpecificationGroupController
{
    protected function getTable(): string
    {
        return SpecificationGroupTable::class;
    }

    protected function getForm(): string
    {
        return SpecificationGroupForm::class;
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
        return 'marketplace.vendor.specification-groups.index';
    }

    protected function getEditRouteName(): string
    {
        return 'marketplace.vendor.specification-groups.edit';
    }

    protected function getSpecificationGroup(string $group)
    {
        return SpecificationGroup::query()
            ->where([
                'author_type' => Customer::class,
                'author_id' => auth('customer')->id(),
            ])
            ->findOrFail($group);
    }
}
