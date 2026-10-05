<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les commandes qui ont une référence null
        DB::table('commandes')
            ->whereNull('reference')
            ->update([
                'reference' => DB::raw("CONCAT('ADMIN-', UPPER(SUBSTRING(MD5(RAND()), 1, 12)))")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cette migration ne peut pas être renversée car elle modifie des données
    }
};
