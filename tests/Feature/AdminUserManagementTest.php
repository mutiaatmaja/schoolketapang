<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $adminRole = $this->createRole('admin', 'Admin');
        $siswaRole = $this->createRole('siswa', 'Siswa');

        $admin = User::factory()->create();
        $admin->syncRoles([$adminRole->name]);

        /** @var User $student */
        $student = User::factory()->create();
        $student->syncRoles([$siswaRole->name]);

        $this->actingAs($student)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_user(): void
    {
        $adminRole = $this->createRole('admin', 'Admin');
        $operatorRole = $this->createRole('operator', 'Operator');

        $admin = User::factory()->create();
        $admin->syncRoles([$adminRole->name]);

        Livewire::actingAs($admin)
            ->test('pages::admin.kelola-user.users')
            ->call('openCreate')
            ->set('name', 'Operator Satu')
            ->set('email', 'operator@example.test')
            ->set('password', 'password123')
            ->set('selectedRole', $operatorRole->name)
            ->call('save');

        $user = User::query()->where('email', 'operator@example.test')->firstOrFail();
        $this->assertTrue($user->hasRole('operator'));

        Livewire::actingAs($admin)
            ->test('pages::admin.kelola-user.users')
            ->call('openEdit', $user->id)
            ->set('name', 'Operator Utama')
            ->set('selectedRole', $adminRole->name)
            ->call('save')
            ->call('confirmDelete', $user->id)
            ->call('delete');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_admin_can_manage_custom_roles(): void
    {
        $adminRole = $this->createRole('admin', 'Admin');

        $admin = User::factory()->create();
        $admin->syncRoles([$adminRole->name]);

        Livewire::actingAs($admin)
            ->test('pages::admin.kelola-user.roles')
            ->call('openCreate')
            ->set('name', 'operator')
            ->set('displayName', 'Operator')
            ->set('description', 'Mengelola data harian sekolah.')
            ->call('save');

        $role = Role::query()->where('name', 'operator')->firstOrFail();

        Livewire::actingAs($admin)
            ->test('pages::admin.kelola-user.roles')
            ->call('openEdit', $role->id)
            ->set('displayName', 'Operator Sekolah')
            ->call('save')
            ->call('confirmDelete', $role->id)
            ->call('delete');

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
        ]);
    }

    private function createRole(string $name, string $displayName): Role
    {
        return Role::query()->create([
            'name' => $name,
            'display_name' => $displayName,
            'description' => 'Role untuk pengujian.',
        ]);
    }
}
