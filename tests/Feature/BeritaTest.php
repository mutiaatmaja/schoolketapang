<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BeritaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_news_detail_page(): void
    {
        $response = $this->get(route('berita.show', 'spmb-2025-2026'));

        $response->assertStatus(200);
        $response->assertSee('Seleksi Penerimaan Murid Baru (SPMB) Tahun Ajaran 2025/2026');
    }

    public function test_unknown_news_slug_returns_404(): void
    {
        $response = $this->get(route('berita.show', 'tidak-ada'));

        $response->assertNotFound();
    }
}
