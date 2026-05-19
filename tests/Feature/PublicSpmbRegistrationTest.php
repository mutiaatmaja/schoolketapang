<?php

namespace Tests\Feature;

use App\Models\SpmbRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\LaravelPdf\Facades\Pdf;
use Tests\TestCase;

class PublicSpmbRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_spmb_pages_can_be_viewed(): void
    {
        $this->get(route('ppdb.informasi'))
            ->assertOk()
            ->assertSee('Yang perlu disiapkan');

        $this->get(route('ppdb.daftar'))
            ->assertOk()
            ->assertSee('Isi formulir secara bertahap.');
    }

    public function test_guest_can_submit_spmb_registration_with_documents(): void
    {
        Storage::fake('public');

        Livewire::test('pages::ppdb.daftar')
            ->set('nik', '3201010101010101')
            ->call('nextStep')
            ->set('name', 'Alya Putri')
            ->set('birthPlace', 'Ketapang')
            ->set('birthDate', '2018-05-10')
            ->set('familyCardNumber', '3201010101010102')
            ->set('gender', 'Perempuan')
            ->set('religion', 'Islam')
            ->call('nextStep')
            ->set('fatherName', 'Budi Santoso')
            ->set('motherName', 'Siti Aminah')
            ->set('fatherPhone', '081234567890')
            ->set('motherPhone', '081298765432')
            ->set('address', 'Jl. Merdeka No. 10, Ketapang')
            ->call('nextStep')
            ->set('birthCertificate', UploadedFile::fake()->create('akta.pdf', 200, 'application/pdf'))
            ->set('familyCard', UploadedFile::fake()->create('kk.pdf', 200, 'application/pdf'))
            ->set('studentPhoto', UploadedFile::fake()->image('foto.jpg'))
            ->set('kindergartenCertificate', UploadedFile::fake()->create('ijazah.pdf', 200, 'application/pdf'))
            ->call('nextStep')
            ->call('submitForm')
            ->assertSet('submitted', true)
            ->assertSee('Nomor pendaftaran Anda')
            ->assertSee('Data formulir dan berkas pendukung sudah berhasil')
            ->assertSee('Semua berkas utama telah diterima sistem')
            ->assertSee('Cetak PDF Rekap Pendaftaran');

        $registration = SpmbRegistration::query()->first();

        $this->assertNotNull($registration);
        $this->assertSame('Alya Putri', $registration->name);
        $this->assertSame('3201010101010102', $registration->family_card_number);
        $this->assertNotNull($registration->submitted_at);
        $this->assertStringStartsWith(
            'akte_lahir/'.$registration->registration_number.'_3201010101010101_alya_putri.',
            $registration->birth_certificate_path,
        );
        $this->assertStringStartsWith(
            'kk/3201010101010101_alya_putri.',
            $registration->family_card_path,
        );
        $this->assertStringStartsWith(
            'pasfoto/'.$registration->registration_number.'_3201010101010101_alya_putri.',
            $registration->student_photo_path,
        );
        $this->assertStringStartsWith(
            'ijazah_tk/'.$registration->registration_number.'_3201010101010101_alya_putri.',
            $registration->kindergarten_certificate_path,
        );

        $this->assertTrue(Storage::disk('public')->exists($registration->birth_certificate_path));
        $this->assertTrue(Storage::disk('public')->exists($registration->family_card_path));
        $this->assertTrue(Storage::disk('public')->exists($registration->student_photo_path));
    }

    public function test_guest_cannot_continue_when_nik_is_already_registered(): void
    {
        SpmbRegistration::factory()->create([
            'nik' => '3201010101010101',
        ]);

        Livewire::test('pages::ppdb.daftar')
            ->set('nik', '3201010101010101')
            ->call('nextStep')
            ->assertHasErrors(['nik' => ['unique']])
            ->assertSee('NIK sudah terdaftar. Jika ini kekeliruan, silakan hubungi operator sekolah.');
    }

    public function test_uploaded_files_and_student_data_remain_stable_when_going_back_steps(): void
    {
        Livewire::test('pages::ppdb.daftar')
            ->set('nik', '3201010101010101')
            ->call('nextStep')
            ->set('name', 'Alya Putri')
            ->set('birthPlace', 'Ketapang')
            ->set('birthDate', '2018-05-10')
            ->set('familyCardNumber', '3201010101010102')
            ->set('gender', 'Perempuan')
            ->set('religion', 'Islam')
            ->call('nextStep')
            ->set('fatherName', 'Budi Santoso')
            ->set('motherName', 'Siti Aminah')
            ->set('fatherPhone', '081234567890')
            ->set('motherPhone', '081298765432')
            ->set('address', 'Jl. Merdeka No. 10, Ketapang')
            ->call('nextStep')
            ->set('birthCertificate', UploadedFile::fake()->create('akta.pdf', 200, 'application/pdf'))
            ->set('familyCard', UploadedFile::fake()->create('kk.pdf', 200, 'application/pdf'))
            ->set('studentPhoto', UploadedFile::fake()->image('foto.png'))
            ->call('nextStep')
            ->assertSee('Akte lahir:')
            ->assertSee('akta.pdf')
            ->call('previousStep')
            ->assertSee('File terpilih:')
            ->assertSee('akta.pdf')
            ->assertSee('kk.pdf')
            ->assertSee('foto.png')
            ->call('previousStep')
            ->assertSet('name', 'Alya Putri')
            ->assertSee('Nama ayah');
    }

    public function test_registration_recap_pdf_can_be_rendered(): void
    {
        Pdf::fake();

        $registration = SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-ABC123',
            'name' => 'Alya Putri',
        ]);

        $this->get(route('ppdb.rekap-pdf', ['registrationNumber' => $registration->registration_number]))
            ->assertOk();

        Pdf::assertRespondedWithPdf(function ($pdf) use ($registration) {
            return $pdf->viewName === 'pdf.ppdb-registration-recap'
                && ($pdf->viewData['registration'] ?? null)?->is($registration)
                && ($pdf->viewData['detailUrl'] ?? null) === route('ppdb.detail', ['registrationNumber' => $registration->registration_number])
                && str_starts_with((string) ($pdf->viewData['qrCodeDataUri'] ?? ''), 'data:image/png;base64,')
                && $pdf->downloadName === 'rekap-pendaftaran-'.$registration->registration_number.'.pdf';
        });
    }

    public function test_public_registration_detail_page_can_be_viewed_from_qr_target(): void
    {
        $registration = SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-DET555',
            'name' => 'Naila Putri',
            'status' => 'verified',
        ]);

        $this->get(route('ppdb.detail', ['registrationNumber' => $registration->registration_number]))
            ->assertOk()
            ->assertSee($registration->name)
            ->assertSee($registration->registration_number)
            ->assertSee('Status Berkas')
            ->assertSee('Buka PDF Rekap');
    }
}
