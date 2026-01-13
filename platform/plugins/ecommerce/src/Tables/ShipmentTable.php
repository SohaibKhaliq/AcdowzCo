<?php

namespace Botble\Ecommerce\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Ecommerce\Enums\ShippingStatusEnum;
use Botble\Ecommerce\Models\Shipment;
use Botble\Ecommerce\Tables\Formatters\PriceFormatter;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;

class ShipmentTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Shipment::class)
            ->addActions([
                EditAction::make()->route('ecommerce.shipments.edit'),
                DeleteAction::make()->route('ecommerce.shipments.destroy'),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'order_id',
                        'user_id',
                        'price',
                        'status',
                        'cod_status',
                        'created_at',
                    ]);
            })
            ->addColumns([
                IdColumn::make(),
                Column::make('order_id')->title(trans('plugins/ecommerce::shipping.order_id')),
                FormattedColumn::make('user_id')
                    ->title(trans('plugins/ecommerce::order.customer_label'))
                    ->alignStart()
                    ->orderable(false)
                    ->renderUsing(function (FormattedColumn $column) {
                        $item = $column->getItem()->order;

                        return sprintf(
                            '%s <br> %s <br> %s',
                            $item->user->name ?: $item->address->name,
                            Html::mailto($item->user->email ?: $item->address->email, obfuscate: false),
                            $item->user->phone ?: $item->address->phone
                        );
                    }),
                Column::formatted('price')
                    ->title(trans('plugins/ecommerce::shipping.shipping_amount')),
                StatusColumn::make(),
                Column::make('cod_status')->title(trans('plugins/ecommerce::shipping.cod_status')),
                CreatedAtColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('ecommerce.shipments.destroy'),
            ])
            ->addBulkChanges([
                StatusBulkChange::make()->choices(ShippingStatusEnum::labels())
                    ->validate('required|in:' . implode(',', ShippingStatusEnum::values())),
                CreatedAtBulkChange::make(),
            ])
            ->onAjax(function (self $table) {
                return $table->toJson(
                    $table
                        ->table
                        ->eloquent($table->query())
                        ->editColumn('order_id', function (Shipment $item) {
                            if (! $this->hasPermission('orders.edit')) {
                                return $item->order->code;
                            }

                            return Html::link(
                                route('orders.edit', $item->order->id),
                                $item->order->code . BaseHelper::renderIcon('ti ti-external-link'),
                                ['target' => '_blank'],
                                null,
                                false
                            );
                        })
                        ->formatColumn('price', PriceFormatter::class)
                        ->editColumn('cod_status', function (Shipment $item) {
                            if (! (float) $item->cod_amount) {
                                return BaseHelper::renderBadge(trans('plugins/ecommerce::shipping.not_available'), 'info');
                            }

                            return BaseHelper::clean($item->cod_status->toHtml());
                        })
                        ->filter(function ($query) {
                            $keyword = $this->request->input('search.value');
                            if ($keyword) {
                                return $query
                                    ->whereHas('order.address', function ($subQuery) use ($keyword) {
                                        return $subQuery->where('ec_order_addresses.name', 'LIKE', '%' . $keyword . '%');
                                    });
                            }

                            return $query;
                        })
                );
            });
    }
}
