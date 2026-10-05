<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_type_billet_id_to_billets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            // Ajouter d'abord type_billet_id
            if (!Schema::hasColumn('billets', 'type_billet_id')) {
                $table->foreignId('type_billet_id')->nullable()->after('id');
                $table->foreign('type_billet_id')->references('id')->on('types_billets')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            if (Schema::hasColumn('billets', 'type_billet_id')) {
                $table->dropForeign(['type_billet_id']);
                $table->dropColumn('type_billet_id');
            }
        });
    }
};