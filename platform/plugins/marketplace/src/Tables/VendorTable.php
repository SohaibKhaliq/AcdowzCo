<?php

namespace Botble\Marketplace\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Ecommerce\Enums\CustomerStatusEnum;
use Botble\Marketplace\Models\Vendor;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Actions\ViewAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\EmailBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\EmailColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\Columns\YesNoColumn;
use Illuminate\Database\Eloquent\Builder;

class VendorTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Vendor::class)
            ->addActions([
                ViewAction::make()
                    ->route('marketplace.vendors.view')
                    ->permission('marketplace.vendors.index'),
                EditAction::make()->route('customers.edit'),
                DeleteAction::make()->route('customers.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('customers.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                EmailBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select('ec_customers.*')
                    ->withStatistics()
                    ->with(['store']);
            });
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            FormattedColumn::make('avatar')
                ->title(trans('plugins/ecommerce::customer.avatar'))
                ->renderUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    if ($this->isExportingToCSV() || $this->isExportingToExcel()) {
                        return $item->avatar_url;
                    }

                    return Html::tag(
                        'img',
                        '',
                        ['src' => $item->avatar_url, 'alt' => BaseHelper::clean($item->name), 'width' => 50, 'height' => 50, 'class' => 'rounded']
                    );
                }),
            NameColumn::make()
                ->route('marketplace.vendors.view')
                ->permission('marketplace.vendors.index'),
            EmailColumn::make(),
            FormattedColumn::make('store_name')
                ->title(trans('plugins/marketplace::marketplace.store_name'))
                ->renderUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    if (! $item->store) {
                        return '—';
                    }

                    return Html::link(
                        route('marketplace.store.edit', $item->store->id),
                        BaseHelper::clean($item->store->name),
                        ['target' => '_blank']
                    );
                })
                ->orderable(false)
                ->searchable(false),
            FormattedColumn::make('store_phone')
                ->title(trans('plugins/marketplace::marketplace.store_phone'))
                ->renderUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    return $item->store ? $item->store->phone : '—';
                })
                ->orderable(false)
                ->searchable(false),
            FormattedColumn::make('products_count')
                ->title(trans('plugins/marketplace::marketplace.products_count'))
                ->renderUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();
                    $count = $item->products_count ?? 0;

                    return Html::tag('span', number_format($count), ['class' => 'badge bg-blue text-blue-fg']);
                })
                ->orderable(false)
                ->searchable(false),
            FormattedColumn::make('total_revenue')
                ->title(trans('plugins/marketplace::marketplace.total_revenue'))
                ->renderUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();
                    $revenue = $item->total_revenue ?? 0;

                    return format_price($revenue);
                })
                ->orderable(false)
                ->searchable(false),
            FormattedColumn::make('balance')
                ->title(trans('plugins/marketplace::marketplace.balance'))
                ->renderUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    $earnings = $item->total_earnings ?? 0;
                    $withdrawals = $item->total_withdrawals ?? 0;
                    $balance = $earnings - $withdrawals;

                    $class = $balance > 0 ? 'bg-green text-green-fg' : ($balance < 0 ? 'bg-red text-red-fg' : 'bg-cyan text-cyan-fg');

                    return Html::tag('span', format_price($balance), ['class' => 'badge ' . $class]);
                })
                ->orderable(false)
                ->searchable(false),
            YesNoColumn::make('vendor_verified_at')
                ->title(trans('plugins/marketplace::marketplace.verified'))
                ->alignCenter(),
            FormattedColumn::make('store_status')
                ->title(trans('plugins/marketplace::marketplace.store_status'))
                ->renderUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();
                    $status = $item->store_status;

                    if (! $status) {
                        return '—';
                    }

                    return BaseHelper::clean($status->toHtml());
                })
                ->orderable(false)
                ->searchable(false),
            StatusColumn::make(),
            CreatedAtColumn::make(),
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
            'email' => [
                'title' => trans('core/base::tables.email'),
                'type' => 'text',
                'validate' => 'required|max:120|email',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => CustomerStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', CustomerStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }

    public function getFilters(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::forms.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'email' => [
                'title' => trans('core/base::tables.email'),
                'type' => 'text',
                'validate' => 'required|max:120|email',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
            'vendor_verified_at' => [
                'title' => trans('plugins/marketplace::marketplace.verified'),
                'type' => 'select',
                'choices' => [
                    1 => trans('core/base::base.yes'),
                    0 => trans('core/base::base.no'),
                ],
                'validate' => 'required|in:0,1',
            ],
        ];
    }

    public function getDefaultButtons(): array
    {
        return [
            'export',
            'reload',
        ];
    }
}
