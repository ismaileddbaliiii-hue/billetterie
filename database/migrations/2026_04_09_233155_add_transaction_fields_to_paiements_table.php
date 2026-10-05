<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->string('transaction_id', 255)->nullable()->after('statut');
            $table->dateTime('date_paiement')->nullable()->after('transaction_id');
            $table->string('source_token', 255)->nullable()->after('date_paiement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'date_paiement', 'source_token']);
        });
    }
};
