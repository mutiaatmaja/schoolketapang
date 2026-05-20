<?php

namespace Tests\Feature;

use App\Models\NewsArticle;
use App\Models\Role;
use App\Models\SchoolAchievement;
use App\Models\SchoolInformation;
use App\Models\User;
use App\Models\VisionMission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPublicContentCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_school_information(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('pages::admin.publik.info-sekolah')
            ->call('openCreate')
            ->set('label', 'Instagram')
            ->set('value', '@sdcerdasketapang')
            ->call('save');

        $item = SchoolInformation::query()->where('label', 'Instagram')->firstOrFail();

        Livewire::actingAs($user)
            ->test('pages::admin.publik.info-sekolah')
            ->call('openEdit', $item->id)
            ->set('value', '@sdcerdas.id')
            ->call('save')
            ->call('confirmDelete', $item->id)
            ->call('delete');

        $this->assertDatabaseMissing('school_information', [
            'label' => 'Instagram',
        ]);
    }

    public function test_admin_can_manage_vision_and_mission_content(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('pages::admin.publik.visi-misi')
            ->call('openCreateVision')
            ->set('content', 'Menjadi sekolah inspiratif untuk pembelajar sepanjang hayat.')
            ->call('save')
            ->call('openCreateMission')
            ->set('content', 'Membentuk budaya belajar yang kolaboratif dan relevan.')
            ->call('save');

        $vision = VisionMission::query()->where('type', 'visi')->firstOrFail();
        $mission = VisionMission::query()->where('type', 'misi')->firstOrFail();

        Livewire::actingAs($user)
            ->test('pages::admin.publik.visi-misi')
            ->call('openEdit', $vision->id)
            ->set('content', 'Menjadi sekolah inspiratif, berkarakter, dan adaptif terhadap masa depan.')
            ->call('save')
            ->call('confirmDelete', $mission->id)
            ->call('delete');

        $this->assertDatabaseHas('vision_missions', [
            'id' => $vision->id,
            'type' => 'visi',
        ]);

        $this->assertDatabaseMissing('vision_missions', [
            'id' => $mission->id,
        ]);
    }

    public function test_admin_can_manage_news_articles(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('pages::admin.publik.berita')
            ->call('openCreate')
            ->set('title', 'Festival Literasi Sekolah')
            ->set('category', 'Kegiatan Sekolah')
            ->set('excerpt', 'Festival literasi menghadirkan berbagai aktivitas membaca dan menulis.')
            ->set('content', '<p>Festival literasi dibuka oleh kepala sekolah.</p><p><strong>Siswa</strong> mengikuti lomba mendongeng dan pojok baca.</p>')
            ->set('status', 'published')
            ->call('save');

        $article = NewsArticle::query()->where('title', 'Festival Literasi Sekolah')->firstOrFail();

        $this->assertStringContainsString('<strong>Siswa</strong>', $article->content);

        Livewire::actingAs($user)
            ->test('pages::admin.publik.berita')
            ->call('openEdit', $article->id)
            ->set('title', 'Festival Literasi dan Numerasi Sekolah')
            ->call('save')
            ->call('confirmDelete', $article->id)
            ->call('delete');

        $this->assertDatabaseMissing('news_articles', [
            'id' => $article->id,
        ]);
    }

    public function test_admin_can_create_update_and_delete_school_achievements(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('pages::admin.publik.prestasi')
            ->call('openCreate')
            ->set('title', 'Juara 1')
            ->set('description', 'Olimpiade Sains Nasional Tingkat Provinsi')
            ->set('level', 'Provinsi')
            ->set('year', '2026')
            ->call('save');

        $achievement = SchoolAchievement::query()->where('title', 'Juara 1')->firstOrFail();

        Livewire::actingAs($user)
            ->test('pages::admin.publik.prestasi')
            ->call('openEdit', $achievement->id)
            ->set('description', 'Olimpiade Sains Nasional Tingkat Nasional')
            ->set('level', 'Nasional')
            ->call('save')
            ->call('confirmDelete', $achievement->id)
            ->call('delete');

        $this->assertDatabaseMissing('school_achievements', [
            'id' => $achievement->id,
        ]);
    }

    public function test_admin_can_upload_news_images_for_jodit_editor(): void
    {
        Storage::fake('public');
        /** @var User $user */
        $user = User::factory()->create();
        Role::query()->create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Role admin untuk upload gambar berita.',
        ]);
        $user->syncRoles(['admin']);

        $response = $this->actingAs($user)
            ->post(route('admin.publik.berita.upload-image'), [
                'files' => [UploadedFile::fake()->image('poster-berita.jpg')],
            ]);

        $response->assertOk()
            ->assertJsonPath('error', 0);

        $uploadedPath = str_replace('/storage/', '', parse_url($response->json('files.0'), PHP_URL_PATH) ?: '');

        $this->assertTrue(Storage::disk('public')->exists($uploadedPath));
    }
}
