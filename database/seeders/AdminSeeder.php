<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin role
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'label' => 'Administrator',
                'description' => 'Full system access',
                'is_default' => false,
            ]
        );

        // Create or update admin user so credentials are always known
        $admin = User::updateOrCreate(
            ['email' => 'admin@safenest.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'phone' => '+1234567890',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role to admin user
        if (! $admin->hasRole('admin')) {
            $admin->roles()->attach($adminRole->id, [
                'assigned_by' => $admin->id,
                'assigned_at' => now(),
            ]);
        }

        $this->command->info('Admin user created:');
        $this->command->info('Email: admin@safenest.com');
        $this->command->info('Password: password');
    }
}
