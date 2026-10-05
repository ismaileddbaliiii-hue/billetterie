<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            
            $table->id();
            $table->string('reference', 255)->unique();
            $table->decimal('montant', 10, 2);
            $table->dateTime('date')->useCurrent();
            $table->string('prestataire', 100);
            $table->enum('statut', ['En attente', 'Payé', 'Échec', 'Remboursé'])->default('En attente');
            
            // Foreign key EXPLICITE vers commandes (1:1)
            $table->unsignedBigInteger('commande_id')->unique();
            $table->foreign('commande_id')
                ->references('id')
                ->on('commandes')
                ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};