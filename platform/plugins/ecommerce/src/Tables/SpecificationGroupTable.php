<?php

namespace Botble\Ecommerce\Tables;

use Botble\Ecommerce\Models\SpecificationGroup;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;

class SpecificationGroupTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(SpecificationGroup::class)
            ->addHeaderAction(CreateHeaderAction::make()->route($this->getCreateRouteName()))
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route($this->getEditRouteName()),
                FormattedColumn::make('description')
                    ->label(trans('core/base::forms.description'))
                    ->withEmptyState()
                    ->limit(50),
                CreatedAtColumn::make(),
            ])
            ->addActions([
                EditAction::make()->route($this->getEditRouteName()),
                DeleteAction::make()->route($this->getDeleteRouteName()),
            ]);
    }

    protected function getCreateRouteName(): string
    {
        return 'ecommerce.specification-groups.create';
    }

    protected function getEditRouteName(): string
    {
        return 'ecommerce.specification-groups.edit';
    }

    protected function getDeleteRouteName(): string
    {
        return 'ecommerce.specification-groups.destroy';
    }
}
