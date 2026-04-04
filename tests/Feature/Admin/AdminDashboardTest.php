<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_can_be_rendered_for_admin_user(): void
    {
        $adminRole = Role::query()->create([
            'name' => 'admin',
            'label' => 'Admin',
            'description' => null,
            'is_default' => false,
            'permissions' => null,
        ]);

        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole->id, [
            'assigned_by' => null,
            'assigned_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSeeText('Control panel');
    }
}
