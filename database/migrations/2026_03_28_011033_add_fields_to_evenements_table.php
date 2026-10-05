<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_fields_to_evenements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            // Vérifier si les colonnes existent déjà avant de les ajouter
            
            // Date de fin
            if (!Schema::hasColumn('evenements', 'date_fin')) {
                $table->dateTime('date_fin')->nullable()->after('date');
            }
            
            // Conditions générales
            if (!Schema::hasColumn('evenements', 'conditions')) {
                $table->text('conditions')->nullable()->after('statut');
            }
            
            // Date de début de vente
            if (!Schema::hasColumn('evenements', 'date_vente_debut')) {
                $table->dateTime('date_vente_debut')->nullable()->after('conditions');
            }
            
            // Date de fin de vente
            if (!Schema::hasColumn('evenements', 'date_vente_fin')) {
                $table->dateTime('date_vente_fin')->nullable()->after('date_vente_debut');
            }
            
            // Affiche (image principale)
            if (!Schema::hasColumn('evenements', 'affiche')) {
                $table->string('affiche')->nullable()->after('date_vente_fin');
            }
            
            // Bannière (image d'en-tête)
            if (!Schema::hasColumn('evenements', 'banniere')) {
                $table->string('banniere')->nullable()->after('affiche');
            }
        });
    }

    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            $columns = [
                'date_fin',
                'conditions',
                'date_vente_debut',
                'date_vente_fin',
                'affiche',
                'banniere'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('evenements', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};