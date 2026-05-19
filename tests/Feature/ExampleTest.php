<?php

namespace Tests\Feature;

use App\Models\NewsArticle;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_welcome_page_displays_dynamic_stats_from_admin_data(): void
    {
        Teacher::factory()->count(3)->create();
        Student::factory()->count(4)->create();
        NewsArticle::factory()->count(2)->published()->create([
            'category' => 'Prestasi',
        ]);
        NewsArticle::factory()->published()->create([
            'category' => 'Pengumuman',
        ]);
        NewsArticle::factory()->draft()->create([
            'category' => 'Prestasi',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('3')
            ->assertSee('4')
            ->assertSee('2')
            ->assertSee('Jumlah Guru')
            ->assertSee('Jumlah Siswa')
            ->assertSee('Jumlah Prestasi');
    }
}
