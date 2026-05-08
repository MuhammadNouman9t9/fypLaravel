<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Note: WithoutModelEvents was intentionally removed. It suppressed `creating`
// hooks (uuid, slug, sku auto-generation) and forced every seeder to set those
// fields manually — fragile and easy to forget.
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            CatalogSeeder::class,
        ]);
    }
}
