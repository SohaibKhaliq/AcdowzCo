<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->index('name', 'idx_cities_name');
            $table->index(['state_id', 'status'], 'idx_cities_state_status');
            $table->index('status', 'idx_cities_status');
            $table->index('state_id', 'idx_cities_state_id');
        });

        Schema::table('cities_translations', function (Blueprint $table) {
            $table->index(['cities_id', 'lang_code'], 'idx_cities_trans_city_lang');
            $table->index('name', 'idx_cities_trans_name');
            $table->index('cities_id', 'idx_cities_trans_cities_id');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->index('name', 'idx_states_name');
            $table->index('status', 'idx_states_status');
            $table->index('country_id', 'idx_states_country_id');
        });

        Schema::table('states_translations', function (Blueprint $table) {
            $table->index(['states_id', 'lang_code'], 'idx_states_trans_state_lang');
            $table->index('name', 'idx_states_trans_name');
            $table->index('states_id', 'idx_states_trans_states_id');
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->index('name', 'idx_countries_name');
            $table->index('status', 'idx_countries_status');
        });

        Schema::table('countries_translations', function (Blueprint $table) {
            $table->index(['countries_id', 'lang_code'], 'idx_countries_trans_country_lang');
            $table->index('name', 'idx_countries_trans_name');
            $table->index('countries_id', 'idx_countries_trans_countries_id');
        });
    }

    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {

            $table->dropIndex('idx_cities_name');
            $table->dropIndex('idx_cities_state_status');
            $table->dropIndex('idx_cities_status');
            $table->dropIndex('idx_cities_state_id');
        });

        Schema::table('cities_translations', function (Blueprint $table) {
            $table->dropIndex('idx_cities_trans_city_lang');
            $table->dropIndex('idx_cities_trans_name');
            $table->dropIndex('idx_cities_trans_cities_id');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropIndex('idx_states_name');
            $table->dropIndex('idx_states_status');
            $table->dropIndex('idx_states_country_id');
        });

        Schema::table('states_translations', function (Blueprint $table) {
            $table->dropIndex('idx_states_trans_state_lang');
            $table->dropIndex('idx_states_trans_name');
            $table->dropIndex('idx_states_trans_states_id');
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->dropIndex('idx_countries_name');
            $table->dropIndex('idx_countries_status');
        });

        Schema::table('countries_translations', function (Blueprint $table) {
            $table->dropIndex('idx_countries_trans_country_lang');
            $table->dropIndex('idx_countries_trans_name');
            $table->dropIndex('idx_countries_trans_countries_id');
        });
    }
};
