<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_places_vendues_to_evenements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            if (!Schema::hasColumn('evenements', 'places_vendues')) {
                $table->integer('places_vendues')->default(0)->after('capacite');
            }
        });
    }

    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            if (Schema::hasColumn('evenements', 'places_vendues')) {
                $table->dropColumn('places_vendues');
            }
        });
    }
};