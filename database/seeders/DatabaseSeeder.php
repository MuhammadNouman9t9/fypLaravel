<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Safe',
            'last_name' => 'Nest',
            'email' => 'admin@safenest.test',
            'preferred_language' => 'en',
            'marketing_opt_in' => true,
        ]);

        $this->call([
            RoleSeeder::class,
            CatalogSeeder::class,
        ]);
    }
}
