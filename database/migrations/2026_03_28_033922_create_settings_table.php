<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
            
            $table->index('key');
        });
        
        // Insérer les paramètres par défaut
        DB::table('settings')->insert([
            ['key' => 'site_name', 'value' => config('app.name')],
            ['key' => 'site_email', 'value' => 'contact@example.com'],
            ['key' => 'site_phone', 'value' => null],
            ['key' => 'site_address', 'value' => null],
            ['key' => 'maintenance_mode', 'value' => '0'],
            ['key' => 'ticket_expiry_days', 'value' => '30'],
            ['key' => 'max_tickets_per_order', 'value' => '10'],
            ['key' => 'enable_notifications', 'value' => '1'],
            ['key' => 'enable_email_verification', 'value' => '1'],
            ['key' => 'default_currency', 'value' => 'MAD'],
        ]);
    }
    
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};