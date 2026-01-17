<?php

namespace Botble\Ecommerce\Tables;

use Botble\Base\Facades\Html;
use Botble\Ecommerce\Models\Customer;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\Action;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\YesNoColumn;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\BulkActions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ResellerManagementTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Customer::class)
            ->addActions([
                Action::make('disable')
                    ->label(__('Disable Reseller'))
                    ->icon('ti ti-user-off')
                    ->color('warning')
                    ->route('reseller-management.disable')
                    ->permission('customers.edit')
                    ->displayIf(fn($item) => $item->is_reseller_active),
                Action::make('enable')
                    ->label(__('Enable Reseller'))
                    ->icon('ti ti-user-check')
                    ->color('success')
                    ->route('reseller-management.enable')
                    ->permission('customers.edit')
                    ->displayIf(fn($item) => !$item->is_reseller_active),
            ]);
    }

    public function query(): Relation|Builder
    {
        $query = $this
            ->getModel()
            ->query()
            ->whereNotNull('reseller_id')
            ->where('is_vendor', false)
            ->select([
                'id',
                'name',
                'email',
                'reseller_id',
                'is_reseller_active',
                'reseller_disabled_at',
                'reseller_disable_reason',
                'created_at',
            ])
            ->orderBy('created_at', 'desc');

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('name')
                ->title(trans('plugins/ecommerce::customer.name'))
                ->alignStart(),
            Column::make('email')
                ->title(trans('plugins/ecommerce::customer.email'))
                ->alignStart(),
            Column::make('reseller_id')
                ->title(trans('Reseller ID'))
                ->alignStart(),
            YesNoColumn::make('is_reseller_active')
                ->title(trans('Active'))
                ->alignCenter(),
            FormattedColumn::make('reseller_disabled_at')
                ->title(trans('Disabled Date'))
                ->alignStart()
                ->renderUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();
                    if (!$item->reseller_disabled_at) {
                        return '—';
                    }
                    return $item->reseller_disabled_at->format('Y-m-d H:i:s');
                }),
            Column::make('reseller_disable_reason')
                ->title(trans('Disable Reason'))
                ->alignStart()
                ->limit(50),
            CreatedAtColumn::make()
                ->title(trans('Registered Date')),
        ];
    }

    public function getFilters(): array
    {
        return [
            'is_reseller_active' => [
                'title' => trans('Status'),
                'type' => 'select',
                'choices' => [
                    1 => trans('Active'),
                    0 => trans('Inactive'),
                ],
            ],
        ];
    }

    public function getDefaultButtons(): array
    {
        return ['export', 'reload'];
    }
}
