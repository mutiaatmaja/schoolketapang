<?php

namespace Tests\Feature;

use App\Models\SpmbRegistration;
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
        $response->assertSee('Pendaftaran murid baru', false);
    }

    public function test_public_user_can_submit_spmb_registration_with_documents(): void
    {
        Storage::fake('public');

        Livewire::test('pages::ppdb.daftar')
            ->set('classLevel', '1')
            ->set('name', 'Nadia Putri')
            ->set('nis', '')
            ->set('nisn', '1234567890')
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

        $this->assertSame('1', $registration->class_level);
        $this->assertSame('Nadia Putri', $registration->name);
        $this->assertSame('submitted', $registration->status);
        $this->assertNotNull($registration->submitted_at);

        Storage::disk('public')->assertExists($registration->birth_certificate_path);
        Storage::disk('public')->assertExists($registration->family_card_path);
        Storage::disk('public')->assertExists($registration->student_photo_path);
        Storage::disk('public')->assertExists($registration->kindergarten_certificate_path);
    }
}
