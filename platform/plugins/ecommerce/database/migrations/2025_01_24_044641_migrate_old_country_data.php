<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        $this->updateCountryColumn('ec_shipping');
        $this->updateCountryColumn('ec_shipping_rule_items');
        $this->updateCountryColumn('ec_tax_rules');
        $this->updateCountryColumn('ec_customer_addresses');
        $this->updateCountryColumn('ec_store_locators');
        $this->updateCountryColumn('ec_order_addresses');
        $this->updateCountryColumn('mp_stores');
    }

    protected function updateCountryColumn(string $table, string $column = 'country'): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        foreach (DB::table($table)->get() as $item) {
            if (! is_numeric($item->{$column})) {
                continue;
            }

            $country = DB::table('countries')->where('id', $item->{$column})->whereNotNull('code')->first();

            if ($country) {
                DB::table($table)
                    ->where('id', $item->id)
                    ->update([
                        $column => $country->code,
                    ]);
            }
        }
    }
};
