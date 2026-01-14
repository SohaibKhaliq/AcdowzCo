<?php

namespace Botble\Marketplace\Tables;

use Botble\Base\Facades\Html;
use Botble\Marketplace\Models\SubscriptionPlan;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class SubscriptionPlanTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(SubscriptionPlan::class)
            ->addActions([
                EditAction::make()->route('marketplace.subscription-plans.edit'),
                DeleteAction::make()->route('marketplace.subscription-plans.destroy'),
            ]);
    }

    public function query(): Relation|Builder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'name',
                'duration',
                'price',
                'priority_boost',
                'verified_eligible',
                'status',
                'created_at',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            NameColumn::make()->route('marketplace.subscription-plans.edit'),
            Column::make('duration')
                ->title(trans('plugins/marketplace::subscription.plans.duration'))
                ->alignStart(),
            Column::make('price')
                ->title(trans('plugins/marketplace::subscription.plans.price'))
                ->alignStart(),
            Column::make('priority_boost')
                ->title(trans('plugins/marketplace::subscription.plans.priority_boost'))
                ->alignStart()
                ->renderUsing(fn($value) => $value ? Html::tag('span', trans('core/base::base.yes'), ['class' => 'badge bg-success']) : Html::tag('span', trans('core/base::base.no'), ['class' => 'badge bg-secondary'])),
            Column::make('verified_eligible')
                ->title(trans('plugins/marketplace::subscription.plans.verified_eligible'))
                ->alignStart()
                ->renderUsing(fn($value) => $value ? Html::tag('span', trans('core/base::base.yes'), ['class' => 'badge bg-success']) : Html::tag('span', trans('core/base::base.no'), ['class' => 'badge bg-secondary'])),
            StatusColumn::make(),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('marketplace.subscription-plans.create'), 'marketplace.subscription-plans.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('marketplace.subscription-plans.destroy'),
        ];
    }
}
