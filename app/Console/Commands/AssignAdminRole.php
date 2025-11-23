<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class AssignAdminRole extends Command
{
    protected $signature = 'admin:assign-role {email=admin@safenest.com}';

    protected $description = 'Assign admin role to a user';

    public function handle(): int
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email {$email} not found.");

            return Command::FAILURE;
        }

        $role = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'label' => 'Administrator',
                'description' => 'Full system access',
                'is_default' => false,
            ]
        );

        if ($user->hasRole('admin')) {
            $this->info("User {$email} already has admin role.");
        } else {
            $user->roles()->attach($role->id, [
                'assigned_by' => $user->id,
                'assigned_at' => now(),
            ]);
            $this->info("Admin role assigned to {$email} successfully!");
        }

        // Verify
        $user->refresh();
        $isAdmin = $user->isAdmin();
        $this->info("Verification: User is admin = ".($isAdmin ? 'YES' : 'NO'));

        return Command::SUCCESS;
    }
}
