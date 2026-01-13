<?php

namespace Botble\Marketplace\Tables;

use Botble\Base\Facades\Html;
use Botble\Marketplace\Enums\StoreStatusEnum;
use Botble\Marketplace\Models\Store;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Actions\ViewAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class StoreTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Store::class)
            ->addActions([
                ViewAction::make()
                    ->route('marketplace.store.view')
                    ->permission('marketplace.store.view'),
                EditAction::make()->route('marketplace.store.edit'),
                DeleteAction::make()->route('marketplace.store.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                $name = Html::link(route('marketplace.store.edit', $item->id), $item->name);
                if ($item->is_verified) {
                    $name .= ' ' . view('plugins/marketplace::partials.verified-badge', ['size' => 'sm'])->render();
                }

                return $name;
            })
            ->editColumn('earnings', function ($item) {
                return $item->customer->id ? format_price($item->customer->balance ?: 0) : '--';
            })
            ->editColumn('products_count', function ($item) {
                return $item->products_count;
            })
            ->addColumn('customer_name', function ($item) {
                if (! $item->customer->name) {
                    return '&mdash;';
                }

                return Html::link(route('customers.edit', $item->customer->id), $item->customer->name);
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'logo',
                'name',
                'created_at',
                'status',
                'customer_id',
                'is_verified',
            ])
            ->with(['customer', 'customer.vendorInfo'])
            ->withCount(['products']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            ImageColumn::make('logo')
                ->title(trans('plugins/marketplace::store.forms.logo')),
            NameColumn::make()->route('marketplace.store.edit'),
            Column::make('earnings')
                ->title(trans('plugins/marketplace::marketplace.tables.earnings'))
                ->alignStart()
                ->orderable(false)
                ->searchable(false)
                ->width('100'),
            Column::make('products_count')
                ->title(trans('plugins/marketplace::marketplace.tables.products_count'))
                ->orderable(false)
                ->searchable(false),
            Column::make('customer_name')
                ->title(trans('plugins/marketplace::marketplace.vendor'))
                ->alignStart()
                ->orderable(false)
                ->searchable(false)
                ->printable(false),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('marketplace.store.create'), 'marketplace.store.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('marketplace.store.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => StoreStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', StoreStatusEnum::values()),
            ],
            'is_verified' => [
                'title' => trans('plugins/marketplace::store.forms.is_verified'),
                'type' => 'select',
                'choices' => [
                    0 => trans('core/base::base.no'),
                    1 => trans('core/base::base.yes'),
                ],
                'validate' => 'required|in:0,1',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }
}
