<?php

namespace Tests\Feature;

use App\Models\SchoolAchievement;
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
        SchoolAchievement::factory()->count(2)->create();

        $this->get('/')
            ->assertOk()
            ->assertSee('3')
            ->assertSee('4')
            ->assertSee('2')
            ->assertSee('Jumlah Guru')
            ->assertSee('Jumlah Siswa')
            ->assertSee('Jumlah Prestasi');
    }

    public function test_welcome_page_displays_dynamic_achievement_highlights(): void
    {
        SchoolAchievement::factory()->create([
            'title' => 'Juara 1',
            'description' => 'Olimpiade Matematika Tingkat Kabupaten',
            'level' => 'Kabupaten',
            'year' => 2026,
            'sort_order' => 1,
        ]);

        SchoolAchievement::factory()->create([
            'title' => 'Medali Emas',
            'description' => 'Kejuaraan Karate Pelajar',
            'level' => 'Provinsi',
            'year' => 2025,
            'sort_order' => 2,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Juara 1')
            ->assertSee('Olimpiade Matematika Tingkat Kabupaten')
            ->assertSee('Kabupaten')
            ->assertSee('Medali Emas')
            ->assertSee('Kejuaraan Karate Pelajar');
    }
}
