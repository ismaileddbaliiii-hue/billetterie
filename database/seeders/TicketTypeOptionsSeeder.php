<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketTypeOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Standard',
                'label' => '🎫 Standard',
                'icon' => '🎫',
                'order' => 1,
                'active' => true,
            ],
            [
                'name' => 'VIP',
                'label' => '✨ VIP',
                'icon' => '✨',
                'order' => 2,
                'active' => true,
            ],
            [
                'name' => 'Premium',
                'label' => '💎 Premium',
                'icon' => '💎',
                'order' => 3,
                'active' => true,
            ],
        ];

        foreach ($types as $type) {
            \App\Models\TicketTypeOption::create($type);
        }
    }
}
