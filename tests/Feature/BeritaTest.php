<?php

namespace Tests\Feature;

use App\Models\NewsArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BeritaTest extends TestCase
{
    use RefreshDatabase;

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
}
