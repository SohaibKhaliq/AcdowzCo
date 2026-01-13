<?php

namespace Botble\Ecommerce\Tables;

use Botble\Ecommerce\Models\SpecificationAttribute;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\LinkableColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class SpecificationAttributeTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(SpecificationAttribute::class)
            ->addHeaderAction(CreateHeaderAction::make()->route($this->getCreateRouteName()))
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route($this->getEditRouteName()),
                LinkableColumn::make('group_id')
                    ->label(trans('plugins/ecommerce::product-specification.specification_attributes.group'))
                    ->externalLink()
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        return $item->group ? $item->group->name : '—';
                    })
                    ->urlUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        return $item->group ? route('ecommerce.specification-groups.edit', $item->group->id) : null;
                    }),
                FormattedColumn::make('type')
                    ->label(trans('plugins/ecommerce::product-specification.specification_attributes.type'))
                    ->renderUsing(function (FormattedColumn $column) {
                        return $column->getItem()->type->label();
                    }),
                CreatedAtColumn::make(),
            ])
            ->addActions([
                EditAction::make()->route($this->getEditRouteName()),
                DeleteAction::make()->route($this->getDeleteRouteName()),
            ])
            ->queryUsing(function (Builder $query) {
                return $query->with('group');
            });
    }

    protected function getCreateRouteName(): string
    {
        return 'ecommerce.specification-attributes.create';
    }

    protected function getEditRouteName(): string
    {
        return 'ecommerce.specification-attributes.edit';
    }

    protected function getDeleteRouteName(): string
    {
        return 'ecommerce.specification-attributes.destroy';
    }
}
