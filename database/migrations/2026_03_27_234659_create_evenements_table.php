<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->dateTime('date');
            
            // Foreign key EXPLICITE vers lieux
            $table->unsignedBigInteger('lieu_id');
            $table->foreign('lieu_id')
                ->references('id')
                ->on('lieux')
                ->onDelete('cascade');
            
            $table->integer('capacite');
            $table->enum('statut', ['Brouillon', 'Publié', 'Terminé', 'Annulé'])->default('Brouillon');
            
            // Foreign key EXPLICITE vers users
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};