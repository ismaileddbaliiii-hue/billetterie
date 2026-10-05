<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_reference_and_fix_columns_to_commandes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenceAndFixColumnsToCommandesTable extends Migration
{
    public function up()
    {
        Schema::table('commandes', function (Blueprint $table) {
            // Ajouter la colonne reference si elle n'existe pas
            if (!Schema::hasColumn('commandes', 'reference')) {
                $table->string('reference')->unique()->nullable()->after('id');
            }
            
            // Ajouter la colonne notes si elle n'existe pas
            if (!Schema::hasColumn('commandes', 'notes')) {
                $table->text('notes')->nullable()->after('statut');
            }
            
            // S'assurer que la colonne evenement_id existe
            if (!Schema::hasColumn('commandes', 'evenement_id')) {
                $table->foreignId('evenement_id')->nullable()->after('client_id');
            }
            
            // S'assurer que la colonne methode_paiement existe
            if (!Schema::hasColumn('commandes', 'methode_paiement')) {
                $table->string('methode_paiement')->nullable()->after('montant_total');
            }
            
            // S'assurer que la colonne email_spectateur existe
            if (!Schema::hasColumn('commandes', 'email_spectateur')) {
                $table->string('email_spectateur')->nullable()->after('methode_paiement');
            }
        });
    }

    public function down()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['reference', 'notes', 'evenement_id', 'methode_paiement', 'email_spectateur']);
        });
    }
}