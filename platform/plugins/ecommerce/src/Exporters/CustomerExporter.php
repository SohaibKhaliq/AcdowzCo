<?php

namespace Botble\Ecommerce\Exporters;

use Botble\DataSynchronize\Exporter\ExportColumn;
use Botble\DataSynchronize\Exporter\ExportCounter;
use Botble\DataSynchronize\Exporter\Exporter;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CustomerExporter extends Exporter
{
    public function getLabel(): string
    {
        return trans('plugins/ecommerce::customer.name');
    }

    public function columns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID')
                ->disabled(),
            ExportColumn::make('name')
                ->label(trans('core/base::forms.name'))
                ->disabled(),
            ExportColumn::make('email')
                ->label(trans('core/base::forms.email'))
                ->disabled(),
            ExportColumn::make('phone')
                ->label(trans('plugins/ecommerce::customer.phone'))
                ->disabled(),
            ExportColumn::make('dob')
                ->label(trans('plugins/ecommerce::customer.dob'))
                ->disabled(),
            ExportColumn::make('status')
                ->label(trans('core/base::tables.status'))
                ->disabled(),
            ExportColumn::make('confirmed_at')
                ->label(trans('plugins/ecommerce::customer.confirmed_at'))
                ->disabled(),
            ExportColumn::make('created_at')
                ->label(trans('core/base::tables.created_at'))
                ->disabled(),
        ];
    }

    public function counters(): array
    {
        return [
            ExportCounter::make()
                ->label(trans('plugins/ecommerce::customer.export.total'))
                ->value(Customer::query()->count()),
        ];
    }

    public function query(): Builder|Customer
    {
        return Customer::query()
            ->select([
                'id',
                'name',
                'email',
                'phone',
                'dob',
                'status',
                'confirmed_at',
                'created_at',
            ]);
    }

    public function hasDataToExport(): bool
    {
        return Customer::query()->exists();
    }

    public function collection(): Collection
    {
        return Customer::query()
            ->select([
                'id',
                'name',
                'email',
                'phone',
                'dob',
                'status',
                'confirmed_at',
                'created_at',
            ])
            ->get()
            ->transform(fn (Customer $item) => [
                'id' => $item->getKey(),
                'name' => $item->name,
                'email' => $item->email,
                'phone' => $item->phone,
                'dob' => $item->dob ? $item->dob->format('Y-m-d') : null,
                'status' => $item->status->getValue(),
                'confirmed_at' => $item->confirmed_at ? $item->confirmed_at->format('Y-m-d H:i:s') : null,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
            ]);
    }
}
