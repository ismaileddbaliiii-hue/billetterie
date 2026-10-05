<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            
            $table->id();
            
            // Foreign key EXPLICITE vers users (nullable)
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            
            $table->string('action', 100);
            $table->string('table_cible', 100)->nullable();
            $table->unsignedBigInteger('record_id')->nullable();
            $table->dateTime('timestamp')->useCurrent();
            $table->string('ip', 45)->nullable();
            $table->enum('niveau', ['INFO', 'WARNING', 'ERROR', 'CRITICAL'])->default('INFO');
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};