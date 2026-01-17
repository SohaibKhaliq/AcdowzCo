<?php

namespace Botble\Ecommerce\Tables;

use Botble\Base\Facades\Html;
use Botble\Ecommerce\Models\ResellerApplication;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\Columns\CreatedAtColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ResellerApplicationTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(ResellerApplication::class)
            ->addActions([
                EditAction::make()->route('ecommerce.reseller-applications.edit'),
            ]);
    }

    public function query(): Relation|Builder
    {
        $query = $this
            ->getModel()
            ->query()
            ->with(['customer'])
            ->select([
                'id',
                'customer_id',
                'notes',
                'status',
                'created_at',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            FormattedColumn::make('customer_id')
                ->title(trans('plugins/ecommerce::customer.name'))
                ->alignStart()
                ->renderUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();
                    return $item->customer && $item->customer->id ? Html::link(route('customers.edit', $item->customer->id), $item->customer->name) : '—';
                }),
            Column::make('notes')
                ->title(trans('plugins/ecommerce::reseller.penalties.reason'))
                ->alignStart()
                ->limit(50),
            StatusColumn::make(),
            CreatedAtColumn::make(),
        ];
    }
}
