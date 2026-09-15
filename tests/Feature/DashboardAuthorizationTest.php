<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role, bool $active = true): User
    {
        return User::create([
            'name' => $role,
            'first_name' => $role,
            'phone' => '09' . str_pad((string) random_int(100000000, 999999999), 9, '0'),
            'password' => Hash::make('password123'),
            'role' => $role,
            'is_active' => $active,
        ]);
    }

    private function permission(string $name): void
    {
        $permissionId = DB::table('permissions')->insertGetId([
            'name' => $name,
            'label' => $name,
            'group_name' => 'test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('role_permissions')->insert([
            'role' => 'staff',
            'permission_id' => $permissionId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_member_cannot_access_admin_dashboard(): void
    {
        $this->actingAs($this->user('buyer'))
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_staff_needs_explicit_dashboard_permission(): void
    {
        $staff = $this->user('staff');
        $this->actingAs($staff)->get(route('admin.dashboard'))->assertForbidden();

        $this->permission('dashboard.view');
        $this->actingAs($staff)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_role_change_to_admin_is_rejected_for_non_admin(): void
    {
        $staff = $this->user('staff');
        $target = $this->user('buyer');
        $this->permission('users.update');

        $this->actingAs($staff)
            ->put(route('admin.users.update', $target), [
                'first_name' => 'Target',
                'last_name' => '',
                'phone' => $target->phone,
                'role' => 'admin',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertSessionHasErrors('role');

        $this->assertSame('buyer', $target->fresh()->role);
    }

    public function test_last_active_admin_cannot_be_demoted(): void
    {
        $admin = $this->user('admin');

        $this->actingAs($admin)
            ->put(route('admin.users.update', $admin), [
                'first_name' => $admin->first_name,
                'last_name' => '',
                'phone' => $admin->phone,
                'role' => 'buyer',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertSessionHasErrors('role');
    }
}