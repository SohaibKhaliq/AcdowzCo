<?php

namespace Botble\Ecommerce\Tables;

use Botble\Ecommerce\Models\SpecificationTable;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class SpecificationTableTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(SpecificationTable::class)
            ->queryUsing(fn (Builder $query) => $query->withCount('groups'))
            ->addHeaderAction(CreateHeaderAction::make()->route($this->getCreateRouteName()))
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route($this->getEditRouteName()),
                FormattedColumn::make('description')
                    ->label(trans('core/base::forms.description'))
                    ->withEmptyState()
                    ->limit(50),
                FormattedColumn::make('tables')
                    ->orderable(false)
                    ->label(trans('plugins/ecommerce::product-specification.specification_tables.fields.assigned_groups'))
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->groups_count),
                CreatedAtColumn::make(),
            ])
            ->addActions([
                EditAction::make()->route($this->getEditRouteName()),
                DeleteAction::make()->route($this->getDeleteRouteName()),
            ]);
    }

    protected function getCreateRouteName(): string
    {
        return 'ecommerce.specification-tables.create';
    }

    protected function getEditRouteName(): string
    {
        return 'ecommerce.specification-tables.edit';
    }

    protected function getDeleteRouteName(): string
    {
        return 'ecommerce.specification-tables.destroy';
    }
}
