<?php

namespace Botble\Marketplace\Tables;

use Botble\Marketplace\Models\Message;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\Action;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\ViewAction;
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

class MessageTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Message::class)
            ->addActions([
                ViewAction::make()
                    ->url(fn (Action $action) => route('marketplace.messages.show', $action->getItem())),
                DeleteAction::make()->route('marketplace.messages.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('marketplace.messages.show'),
                EmailColumn::make()->linkable(),
                FormattedColumn::make('content')->limit(50),
                CreatedAtColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('marketplace.messages.destroy'),
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
                        'customer_id',
                        'store_id',
                    ]);
            });
    }
}
