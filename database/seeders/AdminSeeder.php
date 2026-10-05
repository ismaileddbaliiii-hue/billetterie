<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@billetterie.com'],
            [
                'nom' => 'Super',
                'prenom' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'Admin',
                'telephone' => null,
                'bio' => null,
                'active' => true,
                'perm_creation_event' => true,
                'perm_modification_event' => true,
                'perm_suppression_event' => true,
                'perm_view_stats' => true,
                'date_creation' => now(),
                'date_connexion' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'organisateur@billetterie.com'],
            [
                'nom' => 'Exemple',
                'prenom' => 'Organisateur',
                'password' => Hash::make('organisateur123'),
                'role' => 'Organisateur',
                'telephone' => '0600000001',
                'bio' => 'Organisateur par défaut',
                'active' => true,
                'perm_creation_event' => true,
                'perm_modification_event' => true,
                'perm_suppression_event' => false,
                'perm_view_stats' => true,
                'date_creation' => now(),
                'date_connexion' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'client@billetterie.com'],
            [
                'nom' => 'Exemple',
                'prenom' => 'Client',
                'password' => Hash::make('client123'),
                'role' => 'Client',
                'telephone' => '0600000002',
                'bio' => 'Client par défaut',
                'active' => true,
                'perm_creation_event' => false,
                'perm_modification_event' => false,
                'perm_suppression_event' => false,
                'perm_view_stats' => false,
                'date_creation' => now(),
                'date_connexion' => null,
            ]
        );
    }
}