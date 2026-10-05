<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_types_billets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('types_billets')) {
            Schema::create('types_billets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('evenement_id')->constrained('evenements')->onDelete('cascade');
                $table->string('nom');
                $table->text('description')->nullable();
                $table->decimal('prix', 10, 2);
                $table->integer('quantite')->unsigned();
                $table->integer('quantite_vendue')->unsigned()->default(0);
                $table->boolean('actif')->default(true);
                $table->dateTime('date_vente_debut')->nullable();
                $table->dateTime('date_vente_fin')->nullable();
                $table->timestamps();
                
                $table->index('evenement_id');
                $table->index('actif');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('types_billets');
    }
};