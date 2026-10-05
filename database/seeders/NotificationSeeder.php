<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        if (! $user) {
            return;
        }

        Notification::insert([
            [
                'user_id' => $user->id,
                'type' => 'info',
                'title' => 'Bienvenue sur votre dashboard',
                'message' => 'Tout est prêt, votre système de notifications est désormais activé.',
                'route' => route('admin.dashboard'),
                'data' => json_encode([]),
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'type' => 'success',
                'title' => 'Compte administrateur détecté',
                'message' => 'Vous pouvez maintenant gérer événements, lieux, billets et utilisateurs.',
                'route' => route('admin.parametres.index'),
                'data' => json_encode([]),
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
