<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà
            if (!Schema::hasColumn('users', 'perm_creation_event')) {
                $table->boolean('perm_creation_event')->default(false);
            }
            if (!Schema::hasColumn('users', 'perm_modification_event')) {
                $table->boolean('perm_modification_event')->default(false);
            }
            if (!Schema::hasColumn('users', 'perm_suppression_event')) {
                $table->boolean('perm_suppression_event')->default(false);
            }
            if (!Schema::hasColumn('users', 'perm_view_stats')) {
                $table->boolean('perm_view_stats')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'perm_creation_event',
                'perm_modification_event',
                'perm_suppression_event',
                'perm_view_stats'
            ]);
        });
    }
};