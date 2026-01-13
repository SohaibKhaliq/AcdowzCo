<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Ecommerce\Http\Controllers\SpecificationAttributeController as BaseSpecificationAttributeController;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\SpecificationAttribute;
use Botble\Marketplace\Forms\SpecificationAttributeForm;
use Botble\Marketplace\Tables\SpecificationAttributeTable;

class SpecificationAttributeController extends BaseSpecificationAttributeController
{
    protected function getTable(): string
    {
        return SpecificationAttributeTable::class;
    }

    protected function getForm(): string
    {
        return SpecificationAttributeForm::class;
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
        return 'marketplace.vendor.specification-attributes.index';
    }

    protected function getEditRouteName(): string
    {
        return 'marketplace.vendor.specification-attributes.edit';
    }

    protected function getSpecificationAttribute(string $attribute)
    {
        return SpecificationAttribute::query()
            ->where([
                'author_type' => Customer::class,
                'author_id' => auth('customer')->id(),
            ])
            ->findOrFail($attribute);
    }
}
