<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class CleanSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check and clean site_name
        $siteName = Setting::get('site_name');
        if (strpos($siteName, "'") !== false || strpos($siteName, '>') !== false || strpos($siteName, '"') !== false) {
            $this->command->info("🧹 Cleaning polluted site_name: {$siteName}");
            Setting::set('site_name', 'Billetterie Pro');
            $this->command->info("✅ site_name cleaned: Billetterie Pro");
        } else {
            $this->command->info("✅ site_name is clean: {$siteName}");
        }

        // Check and clean site_description
        $siteDesc = Setting::get('site_description');
        if (strpos($siteDesc, "'") !== false || strpos($siteDesc, '>') !== false || strpos($siteDesc, '"') !== false) {
            $this->command->info("🧹 Cleaning polluted site_description: {$siteDesc}");
            Setting::set('site_description', 'Découvrez et réservez vos événements');
            $this->command->info("✅ site_description cleaned");
        } else {
            $this->command->info("✅ site_description is clean: {$siteDesc}");
        }

        // Verify
        $this->command->line("\n=== Final Values ===");
        $this->command->info("site_name: " . Setting::get('site_name'));
        $this->command->info("site_description: " . Setting::get('site_description'));
    }
}
