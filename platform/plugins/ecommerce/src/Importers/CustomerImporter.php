<?php

namespace Botble\Ecommerce\Importers;

use Botble\DataSynchronize\Contracts\Importer\WithMapping;
use Botble\DataSynchronize\Importer\ImportColumn;
use Botble\DataSynchronize\Importer\Importer;
use Botble\Ecommerce\Enums\CustomerStatusEnum;
use Botble\Ecommerce\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomerImporter extends Importer implements WithMapping
{
    protected bool $updateExisting = false;

    public function __construct()
    {
        $this->updateExisting = request()->boolean('update_existing_customers');
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function columns(): array
    {
        return [
            ImportColumn::make('id')
                ->label('ID')
                ->rules(['nullable', 'integer']),
            ImportColumn::make('name')
                ->label(trans('core/base::forms.name'))
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('email')
                ->label(trans('core/base::forms.email'))
                ->rules(['required', 'string', 'email', 'max:255']),
            ImportColumn::make('phone')
                ->label(trans('plugins/ecommerce::customer.phone'))
                ->rules(['nullable', 'string', 'max:20']),
            ImportColumn::make('dob')
                ->label(trans('plugins/ecommerce::customer.dob'))
                ->rules(['nullable', 'date', 'date_format:Y-m-d', 'before:today']),
            ImportColumn::make('status')
                ->label(trans('core/base::tables.status'))
                ->rules(['nullable', Rule::in(CustomerStatusEnum::values())]),
            ImportColumn::make('confirmed_at')
                ->label(trans('plugins/ecommerce::customer.confirmed_at'))
                ->rules(['nullable', 'date']),
            ImportColumn::make('created_at')
                ->label(trans('core/base::tables.created_at'))
                ->rules(['nullable', 'date']),
            ImportColumn::make('password')
                ->label(trans('plugins/ecommerce::customer.password'))
                ->rules(['nullable', 'string', 'min:6']),
        ];
    }

    public function handle(array $data): int
    {
        $updatedCount = 0;
        $now = Carbon::now();

        foreach ($data as $row) {
            $existingCustomer = Customer::query()
                ->where('email', $row['email'])
                ->first();

            $customerData = [
                'email' => $row['email'],
                'name' => $row['name'],
                'phone' => isset($row['phone']) ? (string) $row['phone'] : null,
                'dob' => $row['dob'] ?? null,
                'status' => $row['status'] ?? CustomerStatusEnum::ACTIVATED,
                'updated_at' => $now,
            ];

            if (! empty($row['password'])) {
                $customerData['password'] = Hash::make($row['password']);
            } elseif (! $existingCustomer) {
                $customerData['password'] = Hash::make(Str::random(8));
            }

            if (! empty($row['confirmed_at'])) {
                $customerData['confirmed_at'] = Carbon::parse($row['confirmed_at']);
            }

            if (! empty($row['created_at']) && ! $existingCustomer) {
                $customerData['created_at'] = Carbon::parse($row['created_at']);
            } elseif (! $existingCustomer) {
                $customerData['created_at'] = $now;
            }

            if ($existingCustomer) {
                if ($this->updateExisting) {
                    unset($customerData['created_at']);
                    if (empty($row['password'])) {
                        unset($customerData['password']);
                    }
                    $existingCustomer->update($customerData);
                    $updatedCount++;
                }
            } else {
                Customer::query()->create($customerData);
                $updatedCount++;
            }
        }

        return $updatedCount;
    }

    public function examples(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+1234567890',
                'dob' => '1990-01-15',
                'status' => CustomerStatusEnum::ACTIVATED,
                'confirmed_at' => '2024-01-15 10:30:00',
                'created_at' => '2024-01-15 10:30:00',
                'password' => 'password123',
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '+0987654321',
                'dob' => '1985-05-20',
                'status' => CustomerStatusEnum::ACTIVATED,
                'confirmed_at' => null,
                'created_at' => '2024-01-16 14:45:00',
                'password' => '',
            ],
        ];
    }

    public function getLabel(): string
    {
        return trans('plugins/ecommerce::customer.name');
    }

    public function getValidateUrl(): string
    {
        return route('ecommerce.customers.import.validate');
    }

    public function getImportUrl(): string
    {
        return route('ecommerce.customers.import.store');
    }

    public function getDownloadExampleUrl(): string
    {
        return route('ecommerce.customers.import.download-example');
    }

    public function getDoneMessage(int $count): string
    {
        return $this->updateExisting
            ? trans('plugins/ecommerce::customer.import.updated_message', ['count' => number_format($count)])
            : trans('plugins/ecommerce::customer.import.created_message', ['count' => number_format($count)]);
    }

    public function map(mixed $row): array
    {
        if (isset($row['phone']) && is_numeric($row['phone'])) {
            $row['phone'] = (string) $row['phone'];
        }

        return $row;
    }
}
