<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billets', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            
            $table->id();
            $table->string('code_unique', 255)->unique();
            $table->string('qr_content', 255)->unique();
            $table->string('type');
            $table->decimal('prix', 10, 2);
            $table->enum('statut', ['Valide', 'Annulé'])->default('Valide');
            
            // Foreign key EXPLICITE vers evenements
            $table->unsignedBigInteger('evenement_id');
            $table->foreign('evenement_id')
                ->references('id')
                ->on('evenements')
                ->onDelete('cascade');
            
            // Foreign key EXPLICITE vers commandes
            $table->unsignedBigInteger('commande_id');
            $table->foreign('commande_id')
                ->references('id')
                ->on('commandes')
                ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billets');
    }
};