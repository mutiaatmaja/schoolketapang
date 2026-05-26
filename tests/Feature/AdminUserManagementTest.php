<?php

namespace Tests\Feature;

use App\Models\NewsArticle;
use App\Models\Role;
use App\Models\SchoolAchievement;
use App\Models\SchoolClass;
use App\Models\SchoolInformation;
use App\Models\SpmbRegistration;
use App\Models\Student;
use App\Models\Teacher;
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

    public function test_admin_dashboard_displays_dynamic_summary(): void
    {
        $adminRole = $this->createRole('admin', 'Admin');

        /** @var User $admin */
        $admin = User::factory()->create([
            'name' => 'Admin Dinamis',
            'email' => 'admin@example.test',
        ]);
        $admin->syncRoles([$adminRole->name]);

        SchoolInformation::query()->create([
            'label' => 'Nama Sekolah',
            'value' => 'SD Dinamis',
            'sort_order' => 1,
        ]);
        SchoolInformation::query()->create([
            'label' => 'Motto Sekolah',
            'value' => 'Unggul dalam data dan layanan.',
            'sort_order' => 2,
        ]);

        $classA = SchoolClass::query()->create(['name' => '1A']);
        $classB = SchoolClass::query()->create(['name' => '1B']);

        Student::factory()->create([
            'school_class_id' => $classA->id,
            'status' => 'AKTIF',
        ]);
        Student::factory()->create([
            'school_class_id' => $classA->id,
            'status' => 'AKTIF',
        ]);
        Student::factory()->create([
            'school_class_id' => $classB->id,
            'status' => 'LULUS',
        ]);

        Teacher::factory()->create([
            'employment_status' => 'Tetap',
        ]);
        Teacher::factory()->create([
            'employment_status' => 'Honorer',
        ]);

        SpmbRegistration::factory()->create(['status' => 'submitted']);
        SpmbRegistration::factory()->create(['status' => 'verified']);
        SpmbRegistration::factory()->create(['status' => 'lulus']);
        SpmbRegistration::factory()->create(['status' => 'cadangan']);

        NewsArticle::factory()->published()->create([
            'title' => 'Berita Dashboard',
            'category' => 'Pengumuman',
        ]);
        NewsArticle::factory()->draft()->create();

        SchoolAchievement::query()->create([
            'title' => 'Prestasi Dashboard',
            'description' => 'Prestasi terbaru sekolah.',
            'level' => 'Kabupaten',
            'year' => 2026,
            'sort_order' => 1,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('SD Dinamis')
            ->assertSee('Unggul dalam data dan layanan.')
            ->assertSee('Admin Dinamis')
            ->assertSee('2 siswa aktif')
            ->assertSee('1 guru tetap')
            ->assertSee('1 belum divalidasi')
            ->assertSee('Berita Dashboard')
            ->assertSee('Prestasi Dashboard')
            ->assertSee('Terverifikasi');
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
