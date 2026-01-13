<?php

namespace Botble\Marketplace\Tables;

use Botble\Marketplace\Models\Message;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\EmailBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\EmailColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Illuminate\Database\Eloquent\Builder;

class AdminMessageTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Message::class)
            ->addActions([
                EditAction::make()->route('marketplace.admin.messages.edit'),
                DeleteAction::make()->route('marketplace.admin.messages.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('marketplace.admin.messages.edit'),
                EmailColumn::make()->linkable(),
                FormattedColumn::make('content')->limit(50),
                CreatedAtColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('marketplace.admin.messages.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                EmailBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'name',
                        'email',
                        'content',
                        'created_at',
                    ])
                    ->with(['store', 'customer']);
            });
    }

    public function getDefaultButtons(): array
    {
        return array_unique(array_merge(['export'], parent::getDefaultButtons()));
    }
}
