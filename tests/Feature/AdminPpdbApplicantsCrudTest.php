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
            'birth_date' => '2018-05-10',
            'submitted_at' => '2026-05-19 08:00:00',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.ppdb.pendaftar'))
            ->assertOk()
            ->assertSee($registration->name)
            ->assertSee($registration->registration_number)
            ->assertSee($registration->ageAtRegistrationLabel());
    }

    public function test_admin_can_view_dynamic_statistics_on_ppdb_summary_page(): void
    {
        $admin = $this->createAdminUser();

        SpmbRegistration::factory()->count(2)->create(['status' => 'submitted']);
        SpmbRegistration::factory()->count(1)->create(['status' => 'lulus']);
        SpmbRegistration::factory()->count(1)->create(['status' => 'cadangan']);
        SpmbRegistration::factory()->count(1)->create(['status' => 'ditolak']);

        $this->actingAs($admin)
            ->get(route('admin.ppdb.index'))
            ->assertOk()
            ->assertSee('Statistik Seleksi')
            ->assertSee('Total Pendaftar')
            ->assertSee('Belum Validasi')
            ->assertSee('Peserta Lulus')
            ->assertSee('Peserta Cadangan')
            ->assertSee('Peserta Ditolak')
            ->assertSee('5')
            ->assertSee('2')
            ->assertSee('1');
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
            'birth_date' => '2017-01-10',
            'submitted_at' => '2026-05-19 08:00:00',
            'birth_certificate_path' => 'akte_lahir/SPMB-2026-DET001_3201010101010101_raka_pratama.pdf',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.ppdb.pendaftar.detail', ['registration' => $registration->registration_number]))
            ->assertOk()
            ->assertSee($registration->name)
            ->assertSee($registration->registration_number)
            ->assertSee('Status Berkas')
            ->assertSee('Umur Saat')
            ->assertSee($registration->ageAtRegistrationLabel())
            ->assertSee('Lihat Berkas')
            ->assertSee('/storage/'.$registration->birth_certificate_path);
    }

    public function test_admin_can_sort_applicants_by_age(): void
    {
        $admin = $this->createAdminUser();

        $olderApplicant = SpmbRegistration::factory()->create([
            'name' => 'Peserta Tertua',
            'registration_number' => 'SPMB-2026-AGE001',
            'birth_date' => '2016-01-10',
            'submitted_at' => '2026-05-19 08:00:00',
        ]);

        $youngerApplicant = SpmbRegistration::factory()->create([
            'name' => 'Peserta Termuda',
            'registration_number' => 'SPMB-2026-AGE002',
            'birth_date' => '2019-01-10',
            'submitted_at' => '2026-05-19 08:00:00',
        ]);

        Livewire::actingAs($admin)
            ->test('pages::admin.ppdb.pendaftar')
            ->set('sortBy', 'age_oldest')
            ->assertSeeInOrder([$olderApplicant->name, $youngerApplicant->name])
            ->set('sortBy', 'age_youngest')
            ->assertSeeInOrder([$youngerApplicant->name, $olderApplicant->name]);
    }

    public function test_admin_can_validate_participant_and_store_validator_and_note(): void
    {
        $admin = $this->createAdminUser();
        $registration = SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-VAL001',
            'status' => 'submitted',
            'validation_note' => null,
            'validated_by_user_id' => null,
            'validated_at' => null,
        ]);

        Livewire::actingAs($admin)
            ->test('pages::admin.ppdb.detail-pendaftar', ['registration' => $registration])
            ->set('validationStatus', 'verified')
            ->set('validationNote', 'Dokumen lengkap dan data sesuai persyaratan.')
            ->call('saveValidation')
            ->assertSet('validationStatus', 'verified')
            ->assertSet('validationNote', 'Dokumen lengkap dan data sesuai persyaratan.');

        $registration->refresh();

        $this->assertSame('verified', $registration->status);
        $this->assertSame('Dokumen lengkap dan data sesuai persyaratan.', $registration->validation_note);
        $this->assertSame($admin->id, $registration->validated_by_user_id);
        $this->assertNotNull($registration->validated_at);
    }

    public function test_admin_status_pages_show_filtered_participants(): void
    {
        $admin = $this->createAdminUser();

        $submitted = SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-SUB001',
            'name' => 'Peserta Belum Validasi',
            'status' => 'submitted',
        ]);

        $lulus = SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-LUL001',
            'name' => 'Peserta Lulus',
            'status' => 'lulus',
        ]);

        $cadangan = SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-CAD001',
            'name' => 'Peserta Cadangan',
            'status' => 'cadangan',
        ]);

        $ditolak = SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-DIT001',
            'name' => 'Peserta Ditolak',
            'status' => 'ditolak',
            'validation_note' => 'Dokumen wajib tidak lengkap.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.ppdb.belum-validasi'))
            ->assertOk()
            ->assertSee($submitted->name)
            ->assertDontSee($lulus->name);

        $this->actingAs($admin)
            ->get(route('admin.ppdb.lulus'))
            ->assertOk()
            ->assertSee($lulus->name)
            ->assertDontSee($submitted->name);

        $this->actingAs($admin)
            ->get(route('admin.ppdb.cadangan'))
            ->assertOk()
            ->assertSee($cadangan->name)
            ->assertDontSee($lulus->name);

        $this->actingAs($admin)
            ->get(route('admin.ppdb.ditolak'))
            ->assertOk()
            ->assertSee($ditolak->name)
            ->assertSee('Dokumen wajib tidak lengkap.')
            ->assertDontSee($submitted->name);
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
