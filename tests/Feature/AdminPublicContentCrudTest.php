<?php

namespace Tests\Feature;

use App\Models\NewsArticle;
use App\Models\SchoolInformation;
use App\Models\User;
use App\Models\VisionMission;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            ->set('content', "Festival literasi dibuka oleh kepala sekolah.\n\nSiswa mengikuti lomba mendongeng dan pojok baca.")
            ->set('status', 'published')
            ->call('save');

        $article = NewsArticle::query()->where('title', 'Festival Literasi Sekolah')->firstOrFail();

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
}
