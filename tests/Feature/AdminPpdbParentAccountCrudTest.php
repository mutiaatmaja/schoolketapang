<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\SpmbRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPpdbParentAccountCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_parent_accounts(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create();

        Livewire::actingAs($admin)
            ->test('pages::admin.ppdb.akun-orang-tua')
            ->call('openCreate')
            ->set('name', 'Wali Nadia')
            ->set('email', 'wali.nadia@example.com')
            ->set('password', 'password123')
            ->set('passwordConfirmation', 'password123')
            ->call('save');

        $parent = User::query()->where('email', 'wali.nadia@example.com')->firstOrFail();

        $this->assertTrue($parent->hasRole('orang_tua'));

        Livewire::actingAs($admin)
            ->test('pages::admin.ppdb.akun-orang-tua')
            ->call('openEdit', $parent->id)
            ->set('name', 'Wali Nadia Update')
            ->set('email', 'wali.nadia.update@example.com')
            ->call('save');

        $this->assertDatabaseHas('users', [
            'id' => $parent->id,
            'name' => 'Wali Nadia Update',
            'email' => 'wali.nadia.update@example.com',
        ]);

        $secondParent = User::factory()->create([
            'name' => 'Wali Hapus',
            'email' => 'wali.hapus@example.com',
        ]);

        $role = Role::query()->firstOrCreate(
            ['name' => 'orang_tua'],
            ['display_name' => 'Orang Tua', 'description' => 'Akun orang tua untuk mengelola pendaftaran SPMB.'],
        );

        $secondParent->syncRoles([$role->name]);

        Livewire::actingAs($admin)
            ->test('pages::admin.ppdb.akun-orang-tua')
            ->call('confirmDelete', $secondParent->id)
            ->call('delete');

        $this->assertDatabaseMissing('users', ['id' => $secondParent->id]);
    }

    public function test_admin_can_view_parent_detail_and_child_name(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create();
        $role = Role::query()->create([
            'name' => 'orang_tua',
            'display_name' => 'Orang Tua',
            'description' => 'Akun orang tua untuk mengelola pendaftaran SPMB.',
        ]);

        /** @var User $parent */
        $parent = User::factory()->create([
            'name' => 'Wali Rafi',
            'email' => 'wali.rafi@example.com',
        ]);
        $parent->syncRoles([$role->name]);

        SpmbRegistration::factory()->create([
            'user_id' => $parent->id,
            'name' => 'Rafi Maulana',
            'registration_number' => 'SPMB-2026-0101',
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.ppdb.orang-tua.show', $parent));

        $response->assertOk();
        $response->assertSee('Wali Rafi');
        $response->assertSee('Rafi Maulana');
        $response->assertSee('SPMB-2026-0101');
    }

    public function test_admin_ppdb_summary_shows_parent_account_count(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create();
        $role = Role::query()->create([
            'name' => 'orang_tua',
            'display_name' => 'Orang Tua',
            'description' => 'Akun orang tua untuk mengelola pendaftaran SPMB.',
        ]);

        /** @var User $parent */
        $parent = User::factory()->create();
        $parent->syncRoles([$role->name]);

        SpmbRegistration::factory()->create(['user_id' => $parent->id]);

        $response = $this->actingAs($admin)->get(route('admin.ppdb.index'));

        $response->assertOk();
        $response->assertSee('Akun Orang Tua', false);
        $response->assertSee('1', false);
    }
}
