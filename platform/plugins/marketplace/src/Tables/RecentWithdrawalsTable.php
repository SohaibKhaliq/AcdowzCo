<?php

namespace Botble\Marketplace\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Marketplace\Models\Withdrawal;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\EnumColumn;
use Botble\Table\Columns\IdColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class RecentWithdrawalsTable extends TableAbstract
{
    protected $hasOperations = false;

    public function setup(): void
    {
        $this
            ->model(Withdrawal::class)
            ->addActions([]);

        $this->pageLength = 10;
        $this->type = self::TABLE_TYPE_SIMPLE;
        $this->view = $this->simpleTableView();
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('customer_id', function (Withdrawal $item) {
                if (! $item->customer->id || ! $item->customer->store?->id) {
                    return '&mdash;';
                }

                $store = $item->customer->store;
                $logo = Html::image($store->logo_url, $store->name, ['width' => 20, 'class' => 'rounded me-2']);
                $storeName = $store->name;
                if (is_in_admin(true) && $this->hasPermission('marketplace.store.view')) {
                    $storeName = Html::link(route('marketplace.store.view', $store->id), $storeName);
                }

                return BaseHelper::clean($logo . $storeName);
            })
            ->editColumn('amount', function (Withdrawal $item) {
                return format_price($item->amount);
            })
            ->editColumn('fee', function (Withdrawal $item) {
                return format_price($item->fee);
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        [$startDate, $endDate] = EcommerceHelper::getDateRangeInReport(request());

        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'customer_id',
                'amount',
                'fee',
                'status',
                'created_at',
            ])
            ->with([
                'customer:id,name',
                'customer.store:id,name,logo,customer_id',
            ])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->latest();

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('customer_id')
                ->title(trans('plugins/marketplace::store.store'))
                ->alignStart(),
            Column::make('amount')
                ->title(trans('plugins/marketplace::withdrawal.amount'))
                ->alignStart(),
            Column::make('fee')
                ->title(trans('plugins/marketplace::withdrawal.fee'))
                ->alignStart(),
            EnumColumn::make('status')
                ->title(trans('plugins/marketplace::withdrawal.status'))
                ->alignStart(),
            CreatedAtColumn::make(),
        ];
    }
}
