<?php
// database/migrations/xxxx_xx_xx_add_fields_to_commandes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCommandesTable extends Migration
{
    public function up()
    {
        Schema::table('commandes', function (Blueprint $table) {
            if (!Schema::hasColumn('commandes', 'methode_paiement')) {
                $table->string('methode_paiement')->nullable()->after('montant_total');
            }
            if (!Schema::hasColumn('commandes', 'email_spectateur')) {
                $table->string('email_spectateur')->nullable()->after('methode_paiement');
            }
            if (!Schema::hasColumn('commandes', 'evenement_id')) {
                $table->foreignId('evenement_id')->nullable()->constrained()->onDelete('set null')->after('client_id');
            }
        });
    }

    public function down()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['methode_paiement', 'email_spectateur', 'evenement_id']);
        });
    }
}