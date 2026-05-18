<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\SpmbRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PublicSpmbRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_spmb_information_page_is_accessible(): void
    {
        $response = $this->get(route('ppdb.informasi'));

        $response->assertStatus(200);
        $response->assertSee('Buat akun orang tua', false);
    }

    public function test_guest_can_register_parent_account_and_is_redirected_to_spmb_form(): void
    {
        $response = $this->post(route('ppdb.register.store'), [
            'name' => 'Orang Tua Nadia',
            'email' => 'ortu.nadia@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('ppdb.daftar'));
        $this->assertAuthenticated();

        $user = User::query()->where('email', 'ortu.nadia@example.com')->firstOrFail();

        $this->assertTrue($user->hasRole('orang_tua'));
    }

    public function test_guest_must_login_before_accessing_spmb_form(): void
    {
        $response = $this->get(route('ppdb.daftar'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_parent_can_submit_spmb_registration_with_documents(): void
    {
        Storage::fake('public');

        $parentRole = Role::query()->create([
            'name' => 'orang_tua',
            'display_name' => 'Orang Tua',
            'description' => 'Akun orang tua untuk mengelola pendaftaran SPMB.',
        ]);

        $user = User::factory()->create([
            'email' => 'ortu@example.com',
        ]);

        $user->syncRoles([$parentRole->name]);

        Livewire::actingAs($user)
            ->test('pages::ppdb.daftar')
            ->set('name', 'Nadia Putri')
            ->set('birthPlace', 'Ketapang')
            ->set('birthDate', '2019-05-10')
            ->set('nik', '3173000000001111')
            ->set('gender', 'Perempuan')
            ->set('religion', 'Islam')
            ->set('fatherName', 'Andi Saputra')
            ->set('motherName', 'Rina Sari')
            ->set('fatherOccupation', 'Wiraswasta')
            ->set('motherOccupation', 'Ibu Rumah Tangga')
            ->set('fatherPhone', '081234567890')
            ->set('motherPhone', '081234567891')
            ->set('address', 'Jl. Pendidikan No. 5')
            ->set('notes', 'Siap mengikuti tes observasi.')
            ->set('birthCertificate', UploadedFile::fake()->create('akte.pdf', 200, 'application/pdf'))
            ->set('familyCard', UploadedFile::fake()->create('kk.pdf', 250, 'application/pdf'))
            ->set('studentPhoto', UploadedFile::fake()->image('foto-siswa.jpg'))
            ->set('kindergartenCertificate', UploadedFile::fake()->create('ijazah-tk.pdf', 180, 'application/pdf'))
            ->call('submitForm')
            ->assertSet('isSubmitted', true)
            ->assertSet('step', 4);

        $registration = SpmbRegistration::query()->firstOrFail();

        $this->assertSame($user->id, $registration->user_id);
        $this->assertSame('Nadia Putri', $registration->name);
        $this->assertSame('submitted', $registration->status);
        $this->assertNotNull($registration->submitted_at);

        $this->assertTrue(Storage::disk('public')->exists($registration->birth_certificate_path));
        $this->assertTrue(Storage::disk('public')->exists($registration->family_card_path));
        $this->assertTrue(Storage::disk('public')->exists($registration->student_photo_path));
        $this->assertTrue(Storage::disk('public')->exists($registration->kindergarten_certificate_path));
    }
}
