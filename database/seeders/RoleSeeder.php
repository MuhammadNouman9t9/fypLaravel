<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'label' => 'Administrator',
                'description' => 'Full system access with all administrative privileges',
                'is_default' => false,
                'permissions' => ['*'],
            ],
            [
                'name' => 'owner',
                'label' => 'Store Owner',
                'description' => 'Store owner with management privileges',
                'is_default' => false,
                'permissions' => ['manage_products', 'manage_orders', 'view_analytics'],
            ],
            [
                'name' => 'security_consultant',
                'label' => 'Security Consultant',
                'description' => 'Security expert who can provide consultation and support to customers',
                'is_default' => false,
                'permissions' => ['view_support', 'respond_support', 'view_risk_assessments', 'provide_consultation'],
            ],
            [
                'name' => 'support_staff',
                'label' => 'Support Staff',
                'description' => 'Customer support staff with limited administrative access',
                'is_default' => false,
                'permissions' => ['view_support', 'respond_support', 'view_orders'],
            ],
            [
                'name' => 'customer',
                'label' => 'Customer',
                'description' => 'Regular customer with standard access',
                'is_default' => true,
                'permissions' => ['view_products', 'place_orders', 'view_own_orders'],
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }
    }
}
