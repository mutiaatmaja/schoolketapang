<?php

namespace Tests\Feature;

use App\Models\SpmbRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PpdbPublicInformationPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_compact_information_page(): void
    {
        $this->get(route('ppdb.informasi'))
            ->assertOk()
            ->assertSee('Yang perlu disiapkan')
            ->assertSee('Alur pendaftaran')
            ->assertSee('Buka Statistik SPMB')
            ->assertDontSee('Cek Status Pendaftaran');
    }

    public function test_public_can_view_statistics_and_masked_data_on_statistics_page(): void
    {
        SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-00001',
            'name' => 'Raka Pratama',
            'nik' => '3201010101010999',
            'status' => 'submitted',
        ]);

        SpmbRegistration::factory()->create(['status' => 'lulus']);
        SpmbRegistration::factory()->create(['status' => 'cadangan']);
        SpmbRegistration::factory()->create(['status' => 'ditolak']);

        $this->get(route('ppdb.statistik'))
            ->assertOk()
            ->assertSee('Statistik dan Cek Status SPMB')
            ->assertSee('Total Pendaftar')
            ->assertSee('Belum Validasi')
            ->assertSee('Terverifikasi')
            ->assertSee('Lulus')
            ->assertSee('Cadangan')
            ->assertSee('Ditolak')
            ->assertDontSee('3201010101010999')
            ->assertDontSee('Raka Pratama')
            ->assertDontSee('Edit')
            ->assertDontSee('Hapus');
    }

    public function test_public_can_search_by_registration_number_and_nik_without_exposing_sensitive_data(): void
    {
        SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-00077',
            'name' => 'Nadia Zahra',
            'nik' => '3202010101010777',
            'status' => 'verified',
            'validation_note' => 'Dokumen telah diverifikasi.',
        ]);

        Livewire::test('pages::ppdb.statistik')
            ->set('searchKeyword', 'SPMB-2026-00077')
            ->assertSee('SPMB-2026-00077')
            ->assertSee('3202010101010***')
            ->assertDontSee('3202010101010777')
            ->assertDontSee('Nadia Zahra')
            ->set('searchKeyword', '3202010101010777')
            ->assertSee('SPMB-2026-00077')
            ->assertSee('Terverifikasi');
    }

    public function test_public_can_open_each_category_page_separately(): void
    {
        SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-SUB001',
            'status' => 'submitted',
        ]);

        SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-VER001',
            'status' => 'verified',
        ]);

        SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-LUL001',
            'status' => 'lulus',
        ]);

        SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-CAD001',
            'status' => 'cadangan',
        ]);

        SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-DIT001',
            'status' => 'ditolak',
            'validation_note' => 'Dokumen belum lengkap.',
        ]);

        $this->get(route('ppdb.kategori.belum-validasi'))
            ->assertOk()
            ->assertSee('Kategori Belum Validasi')
            ->assertSee('SPMB-2026-SUB001')
            ->assertDontSee('SPMB-2026-VER001');

        $this->get(route('ppdb.kategori.terverifikasi'))
            ->assertOk()
            ->assertSee('Kategori Terverifikasi')
            ->assertSee('SPMB-2026-VER001')
            ->assertDontSee('SPMB-2026-SUB001');

        $this->get(route('ppdb.kategori.lulus'))
            ->assertOk()
            ->assertSee('Kategori Lulus')
            ->assertSee('SPMB-2026-LUL001');

        $this->get(route('ppdb.kategori.cadangan'))
            ->assertOk()
            ->assertSee('Kategori Cadangan')
            ->assertSee('SPMB-2026-CAD001');

        $this->get(route('ppdb.kategori.ditolak'))
            ->assertOk()
            ->assertSee('Kategori Ditolak')
            ->assertSee('SPMB-2026-DIT001')
            ->assertSee('Dokumen belum lengkap.');
    }

    public function test_public_search_is_rate_limited(): void
    {
        SpmbRegistration::factory()->create([
            'registration_number' => 'SPMB-2026-RATE001',
            'status' => 'submitted',
        ]);

        $component = Livewire::test('pages::ppdb.statistik');

        foreach (range(1, 21) as $attempt) {
            $component->set('searchKeyword', 'SPMB-2026-RATE'.$attempt);
        }

        $component
            ->set('searchKeyword', 'SPMB-2026-RATE001')
            ->assertSee('Pencarian dibatasi sementara.');
    }
}
