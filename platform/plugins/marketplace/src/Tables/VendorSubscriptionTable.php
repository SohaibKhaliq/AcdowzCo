<?php

namespace Botble\Marketplace\Tables;

use Botble\Base\Facades\Html;
use Botble\Marketplace\Models\VendorSubscription;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\Action;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class VendorSubscriptionTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(VendorSubscription::class)
            ->addActions([
                Action::make('view')
                    ->label(trans('core/base::tables.view'))
                    ->icon('ti ti-eye')
                    ->route('marketplace.vendor-subscriptions.show'),
                Action::make('cancel')
                    ->label(trans('plugins/marketplace::subscription.subscriptions.cancel'))
                    ->icon('ti ti-x')
                    ->route('marketplace.vendor-subscriptions.cancel')
                    ->color('danger')
                    ->displayIf(fn($item) => $item->status === 'active'),
            ]);
    }

    public function query(): Relation|Builder
    {
        $query = $this
            ->getModel()
            ->query()
            ->with(['customer', 'store', 'plan'])
            ->select([
                'id',
                'customer_id',
                'store_id',
                'plan_id',
                'starts_at',
                'expires_at',
                'status',
                'created_at',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('customer_id')
                ->title(trans('plugins/marketplace::subscription.subscriptions.vendor'))
                ->alignStart()
                ->renderUsing(fn($item) => $item->customer ? Html::link(route('customers.edit', $item->customer->id), $item->customer->name) : '—'),
            Column::make('store_id')
                ->title(trans('plugins/marketplace::subscription.subscriptions.store'))
                ->alignStart()
                ->renderUsing(fn($item) => $item->store ? Html::link(route('marketplace.stores.edit', $item->store->id), $item->store->name) : '—'),
            Column::make('plan_id')
                ->title(trans('plugins/marketplace::subscription.subscriptions.plan'))
                ->alignStart()
                ->renderUsing(fn($item) => $item->plan ? Html::link(route('marketplace.subscription-plans.edit', $item->plan->id), $item->plan->name) : '—'),
            Column::make('starts_at')
                ->title(trans('plugins/marketplace::subscription.subscriptions.starts_at'))
                ->alignStart()
                ->dateFormat(),
            Column::make('expires_at')
                ->title(trans('plugins/marketplace::subscription.subscriptions.expires_at'))
                ->alignStart()
                ->dateFormat(),
            Column::make('status')
                ->title(trans('core/base::tables.status'))
                ->alignStart()
                ->renderUsing(function ($item) {
                    $color = match ($item->status) {
                        'active' => 'success',
                        'expired' => 'warning',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    };
                    
                    return Html::tag('span', ucfirst($item->status), ['class' => "badge bg-{$color}"]);
                }),
            CreatedAtColumn::make(),
        ];
    }
}
