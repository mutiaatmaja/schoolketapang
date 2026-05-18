<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\SpmbRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPpdbApplicantsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_registered_participants_on_applicants_page(): void
    {
        $admin = $this->createAdminUser();
        $registration = SpmbRegistration::factory()->create([
            'name' => 'Alya Putri',
            'registration_number' => 'SPMB-2026-REG001',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.ppdb.pendaftar'))
            ->assertOk()
            ->assertSee($registration->name)
            ->assertSee($registration->registration_number);
    }

    public function test_admin_can_create_update_and_delete_applicant_manually(): void
    {
        $admin = $this->createAdminUser();

        Livewire::actingAs($admin)
            ->test('pages::admin.ppdb.pendaftar')
            ->call('openCreate')
            ->set('name', 'Dina Maharani')
            ->set('birthPlace', 'Ketapang')
            ->set('birthDate', '2018-01-10')
            ->set('nik', '3201010101010999')
            ->set('familyCardNumber', '3201010101010888')
            ->set('gender', 'Perempuan')
            ->set('religion', 'Islam')
            ->set('fatherName', 'Arif Setiawan')
            ->set('motherName', 'Nur Aisyah')
            ->set('fatherOccupation', 'Wiraswasta')
            ->set('motherOccupation', 'Ibu Rumah Tangga')
            ->set('fatherPhone', '081200000001')
            ->set('motherPhone', '081200000002')
            ->set('address', 'Jl. Pendidikan No. 10, Ketapang')
            ->set('notes', 'Input manual oleh admin.')
            ->set('status', 'verified')
            ->call('save');

        $registration = SpmbRegistration::query()->where('nik', '3201010101010999')->firstOrFail();

        Livewire::actingAs($admin)
            ->test('pages::admin.ppdb.pendaftar')
            ->call('openEdit', $registration->id)
            ->set('name', 'Dina Maharani Salsabila')
            ->set('status', 'lulus')
            ->call('save')
            ->call('confirmDelete', $registration->id)
            ->call('delete');

        $this->assertDatabaseMissing('spmb_registrations', [
            'id' => $registration->id,
        ]);
    }

    public function test_admin_can_open_participant_detail_page(): void
    {
        $admin = $this->createAdminUser();
        Storage::fake('public');
        Storage::disk('public')->put('akte_lahir/SPMB-2026-DET001_3201010101010101_raka_pratama.pdf', 'dummy');
        $registration = SpmbRegistration::factory()->create([
            'name' => 'Raka Pratama',
            'registration_number' => 'SPMB-2026-DET001',
            'status' => 'verified',
            'birth_certificate_path' => 'akte_lahir/SPMB-2026-DET001_3201010101010101_raka_pratama.pdf',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.ppdb.pendaftar.detail', ['registration' => $registration->registration_number]))
            ->assertOk()
            ->assertSee($registration->name)
            ->assertSee($registration->registration_number)
            ->assertSee('Status Berkas')
            ->assertSee('Lihat Berkas')
            ->assertSee(Storage::disk('public')->url($registration->birth_certificate_path));
    }

    private function createAdminUser(): User
    {
        $role = Role::query()->create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Role admin untuk pengujian.',
        ]);

        $user = User::factory()->create();
        $user->syncRoles([$role->name]);

        return $user;
    }
}
