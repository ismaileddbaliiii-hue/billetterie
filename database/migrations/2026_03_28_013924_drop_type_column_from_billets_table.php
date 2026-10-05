<?php
// database/migrations/xxxx_xx_xx_xxxxxx_drop_type_column_from_billets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            if (Schema::hasColumn('billets', 'type')) {
                $table->dropColumn('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            $table->string('type')->nullable()->after('code_unique');
        });
    }
};