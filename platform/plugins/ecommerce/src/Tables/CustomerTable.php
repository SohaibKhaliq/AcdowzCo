<?php

namespace Botble\Ecommerce\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\DataSynchronize\Table\HeaderActions\ExportHeaderAction;
use Botble\DataSynchronize\Table\HeaderActions\ImportHeaderAction;
use Botble\Ecommerce\Enums\CustomerStatusEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Customer;
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
use Botble\Table\Columns\PhoneColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\Columns\YesNoColumn;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class CustomerTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Customer::class)
            ->addHeaderActions([
                ExportHeaderAction::make()
                    ->route('ecommerce.customers.export.index')
                    ->permission('ecommerce.customers.export'),
                ImportHeaderAction::make()
                    ->route('ecommerce.customers.import.index')
                    ->permission('ecommerce.customers.import'),
            ])
            ->addActions([
                ViewAction::make()
                    ->route('customers.view')
                    ->permission('customers.index'),
                EditAction::make()->route('customers.edit'),
                DeleteAction::make()->route('customers.destroy'),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'name',
                        'email',
                        'phone',
                        'avatar',
                        'created_at',
                        'status',
                        'confirmed_at',
                    ]);
            });
    }

    public function columns(): array
    {
        $columns = [
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
                        ['src' => $item->avatar_url, 'alt' => BaseHelper::clean($item->name), 'width' => 50]
                    );
                }),
            NameColumn::make()->route('customers.edit'),
        ];

        if (EcommerceHelper::isLoginUsingPhone()) {
            $columns[] = PhoneColumn::make();
        } else {
            $columns[] = EmailColumn::make();

            if (EcommerceHelper::isEnableEmailVerification()) {
                $columns = array_merge($columns, [
                    YesNoColumn::make('confirmed_at')
                        ->title(trans('plugins/ecommerce::customer.email_verified')),
                ]);
            }
        }

        return array_merge($columns, [
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ]);
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('customers.create'), 'customers.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('customers.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            NameBulkChange::make(),
            EmailBulkChange::make(),
            StatusBulkChange::make()
                ->choices(CustomerStatusEnum::labels())
                ->validate(['required', Rule::in(CustomerStatusEnum::values())]),
            CreatedAtBulkChange::make(),
        ];
    }

    public function getFilters(): array
    {
        $filters = parent::getFilters();

        if (EcommerceHelper::isEnableEmailVerification()) {
            $filters['confirmed_at'] = [
                'title' => trans('plugins/ecommerce::customer.email_verified'),
                'type' => 'select',
                'choices' => [1 => trans('core/base::base.yes'), 0 => trans('core/base::base.no')],
                'validate' => 'required|in:1,0',
            ];
        }

        return $filters;
    }

    public function renderTable($data = [], $mergeData = []): View|Factory|Response
    {
        if ($this->isEmpty()) {
            return view('plugins/ecommerce::customers.intro');
        }

        return parent::renderTable($data, $mergeData);
    }

    public function applyFilterCondition(
        Relation|Builder|QueryBuilder $query,
        string $key,
        string $operator,
        ?string $value
    ) {
        if (EcommerceHelper::isEnableEmailVerification() && $key === 'confirmed_at') {
            return $value ? $query->whereNotNull($key) : $query->whereNull($key);
        }

        return parent::applyFilterCondition($query, $key, $operator, $value);
    }
}
