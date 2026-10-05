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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // Noms personnalisés (pas de 'name')
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // Rôle
            $table->enum('role', ['Admin', 'Organisateur', 'Client'])->default('Client');
            
            // Champs profil
            $table->string('telephone')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('active')->default(true);
            
            // Permissions pour Organisateurs
            $table->boolean('perm_creation_event')->default(false);
            $table->boolean('perm_modification_event')->default(false);
            $table->boolean('perm_suppression_event')->default(false);
            $table->boolean('perm_view_stats')->default(false);
            
            // Dates
            $table->dateTime('date_creation')->useCurrent();
            $table->dateTime('date_connexion')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};