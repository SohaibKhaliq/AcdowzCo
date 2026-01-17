<?php

namespace Botble\Ecommerce\Tables;

use Botble\Base\Facades\Html;
use Botble\Ecommerce\Models\Customer;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\Action;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\CreatedAtColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ResellerDeletionRequestTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Customer::class)
            ->addActions([
                Action::make('approve')
                    ->label(__('Approve Deletion'))
                    ->icon('ti ti-check')
                    ->color('success')
                    ->route('reseller-management.process-deletion')
                    ->permission('customers.edit'),
                Action::make('reject')
                    ->label(__('Reject Request'))
                    ->icon('ti ti-x')
                    ->color('danger')
                    ->route('reseller-management.reject-deletion')
                    ->permission('customers.edit'),
            ]);
    }

    public function query(): Relation|Builder
    {
        $query = $this
            ->getModel()
            ->query()
            ->whereNotNull('reseller_deletion_requested_at')
            ->where('is_reseller_active', true)
            ->where('is_vendor', false)
            ->select([
                'id',
                'name',
                'email',
                'reseller_id',
                'reseller_deletion_requested_at',
                'created_at',
            ])
            ->orderBy('reseller_deletion_requested_at', 'desc');

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
            FormattedColumn::make('reseller_deletion_requested_at')
                ->title(trans('Request Date'))
                ->alignStart()
                ->renderUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();
                    if (!$item->reseller_deletion_requested_at) {
                        return '—';
                    }
                    return $item->reseller_deletion_requested_at->format('Y-m-d H:i:s');
                }),
            CreatedAtColumn::make()
                ->title(trans('Registered Date')),
        ];
    }

    public function getDefaultButtons(): array
    {
        return ['reload'];
    }
}
