<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            AdminSeeder::class,
            TicketTypeOptionsSeeder::class,
        ]);

        // Optionnel : un utilisateur de test pour développement
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'nom' => 'Utilisateur',
                'prenom' => 'Test',
                'email' => 'test@example.com',
            ]);
        }

        $this->call(NotificationSeeder::class);
    }
}
