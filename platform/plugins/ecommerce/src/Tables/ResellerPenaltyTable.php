<?php

namespace Botble\Ecommerce\Tables;

use Botble\Base\Facades\Html;
use Botble\Ecommerce\Models\ResellerPenalty;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\Action;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;

class ResellerPenaltyTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(ResellerPenalty::class)
            ->addActions([
                Action::make('view')
                    ->label(trans('core/base::tables.view'))
                    ->icon('ti ti-eye')
                    ->route('ecommerce.reseller-penalties.show'),
                Action::make('reverse')
                    ->label(trans('plugins/ecommerce::reseller.penalties.reverse'))
                    ->icon('ti ti-arrow-back')
                    ->route('ecommerce.reseller-penalties.reverse')
                    ->color('warning')
                    ->displayIf(fn($item) => $item->status === 'applied'),
            ]);
    }

    public function query(): Relation|Builder
    {
        $query = $this
            ->getModel()
            ->query()
            ->with(['reseller', 'order', 'product', 'issuedBy'])
            ->select([
                'id',
                'reseller_id',
                'order_id',
                'product_id',
                'amount',
                'reason',
                'status',
                'issued_by',
                'created_at',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('reseller_id')
                ->title(trans('plugins/ecommerce::reseller.penalties.reseller'))
                ->alignStart()
                ->renderUsing(fn($item) => $item->reseller ? Html::link(route('customers.edit', $item->reseller->id), $item->reseller->name) : '—'),
            Column::make('order_id')
                ->title(trans('plugins/ecommerce::reseller.penalties.order'))
                ->alignStart()
                ->renderUsing(fn($item) => $item->order ? Html::link(route('orders.edit', $item->order->id), $item->order->code) : '—'),
            Column::make('product_id')
                ->title(trans('plugins/ecommerce::reseller.penalties.product'))
                ->alignStart()
                ->renderUsing(fn($item) => $item->product ? Html::link(route('products.edit', $item->product->id), $item->product->name) : '—'),
            Column::make('amount')
                ->title(trans('plugins/ecommerce::reseller.penalties.amount'))
                ->alignStart()
                ->renderUsing(fn($item) => format_price($item->amount)),
            Column::make('reason')
                ->title(trans('plugins/ecommerce::reseller.penalties.reason'))
                ->alignStart()
                ->limit(50),
            Column::make('status')
                ->title(trans('core/base::tables.status'))
                ->alignStart()
                ->renderUsing(function ($item) {
                    $color = match ($item->status) {
                        'applied' => 'danger',
                        'reversed' => 'success',
                        default => 'secondary',
                    };

                    return Html::tag('span', ucfirst($item->status), ['class' => "badge bg-{$color}"]);
                }),
            Column::make('issued_by')
                ->title(trans('plugins/ecommerce::reseller.penalties.issued_by'))
                ->alignStart()
                ->renderUsing(fn($item) => $item->issuedBy ? $item->issuedBy->name : '—'),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        // Only add create button if the route exists
        $routeName = null;

        if (Route::has('ecommerce.reseller-penalties.create')) {
            $routeName = 'ecommerce.reseller-penalties.create';
        } elseif (Route::has('reseller-penalties.create')) {
            $routeName = 'reseller-penalties.create';
        }

        if ($routeName) {
            return $this->addCreateButton(route($routeName), $routeName);
        }

        return [];
    }
}
