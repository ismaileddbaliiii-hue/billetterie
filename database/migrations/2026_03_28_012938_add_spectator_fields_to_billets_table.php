<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_spectator_fields_to_billets_table_v2.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            // Ajouter les colonnes une par une
            if (!Schema::hasColumn('billets', 'nom_spectateur')) {
                $table->string('nom_spectateur')->nullable()->after('type_billet_id');
            }
            
            if (!Schema::hasColumn('billets', 'email_spectateur')) {
                $table->string('email_spectateur')->nullable()->after('nom_spectateur');
            }
            
            if (!Schema::hasColumn('billets', 'date_validation')) {
                $table->timestamp('date_validation')->nullable()->after('statut');
            }
            
            if (!Schema::hasColumn('billets', 'valide_par')) {
                $table->string('valide_par')->nullable()->after('date_validation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            $columns = ['nom_spectateur', 'email_spectateur', 'date_validation', 'valide_par'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('billets', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};