<?php

namespace Tests\Feature;

use App\Models\NewsArticle;
use App\Models\SchoolAchievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BeritaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_news_index_page(): void
    {
        NewsArticle::factory()->published()->create([
            'title' => 'Pengumuman Libur Semester',
            'slug' => 'pengumuman-libur-semester',
        ]);

        $response = $this->get(route('berita.index'));

        $response->assertOk();
        $response->assertSee('Semua Berita');
        $response->assertSee('Pengumuman Libur Semester');
    }

    public function test_guest_can_view_news_detail_page(): void
    {
        NewsArticle::factory()->published()->create([
            'title' => 'Seleksi Penerimaan Murid Baru (SPMB) Tahun Ajaran 2025/2026',
            'slug' => 'spmb-2025-2026',
            'content' => '<p>Jadwal seleksi sudah dibuka.</p><p><img src="/storage/berita/2026/05/contoh.jpg" alt="Poster"></p><p>Calon siswa dapat melihat detail tahapan di website.</p>',
        ]);

        $response = $this->get(route('berita.show', 'spmb-2025-2026'));

        $response->assertStatus(200);
        $response->assertSee('Seleksi Penerimaan Murid Baru (SPMB) Tahun Ajaran 2025/2026');
        $response->assertSee('/storage/berita/2026/05/contoh.jpg', false);
    }

    public function test_unknown_news_slug_returns_404(): void
    {
        $response = $this->get(route('berita.show', 'tidak-ada'));

        $response->assertNotFound();
    }

    public function test_guest_can_view_achievement_index_page(): void
    {
        SchoolAchievement::factory()->create([
            'title' => 'Juara 1',
            'description' => 'Olimpiade Matematika Tingkat Kabupaten',
            'level' => 'Kabupaten',
            'year' => 2026,
        ]);

        $response = $this->get(route('prestasi.index'));

        $response->assertOk();
        $response->assertSee('Semua Prestasi');
        $response->assertSee('Juara 1');
        $response->assertSee('Olimpiade Matematika Tingkat Kabupaten');
    }
}
