<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            
            $table->id();
            
            // Foreign key EXPLICITE vers users (client)
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            
            $table->dateTime('date')->useCurrent();
            $table->decimal('montant_total', 10, 2);
            $table->string('moyen_paiement')->nullable();
            $table->enum('statut', ['En attente', 'Confirmée', 'Annulée'])->default('En attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};