<?php

namespace Database\Seeders;

use App\Models\VisionMission;
use Illuminate\Database\Seeder;

class VisionMissionSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'type' => 'visi',
                'content' => 'Menjadi sekolah dasar unggul yang membentuk murid berkarakter, berprestasi, dan siap menghadapi masa depan.',
                'sort_order' => 1,
            ],
            [
                'type' => 'misi',
                'content' => 'Menyelenggarakan pembelajaran aktif, kreatif, dan menyenangkan.',
                'sort_order' => 1,
            ],
            [
                'type' => 'misi',
                'content' => 'Menanamkan nilai religius, disiplin, dan kepedulian sosial.',
                'sort_order' => 2,
            ],
            [
                'type' => 'misi',
                'content' => 'Mengembangkan potensi akademik dan non-akademik murid secara seimbang.',
                'sort_order' => 3,
            ],
            [
                'type' => 'misi',
                'content' => 'Membangun kolaborasi antara sekolah, orang tua, dan masyarakat.',
                'sort_order' => 4,
            ],
        ];

        foreach ($items as $item) {
            VisionMission::query()->updateOrCreate(
                [
                    'type' => $item['type'],
                    'sort_order' => $item['sort_order'],
                ],
                $item,
            );
        }
    }
}
