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
                'content' => 'TERWUJUDNYA PESERTA DIDIK YANG BERIMAN, BERKARAKTER, CERDAS DAN TERAMPIL, MANDIRI SERTA BERWAWASAN PANCASILA.',
                'sort_order' => 1,
            ],
            [
                'type' => 'misi',
                'content' => 'Menanamkan Keimanan Dan Ketaqwaan Melalui Pembelajaran Agama.',
                'sort_order' => 1,
            ],
            [
                'type' => 'misi',
                'content' => '⁠Membiasakan Prilaku Yang Mencerminkan Iman, Taqwa, Dan Berakhlak Mulia Membiasakan Bersikap Jujur, Dan Disiplin.',
                'sort_order' => 2,
            ],
            [
                'type' => 'misi',
                'content' => '⁠Bertanggung Jawab, Serta Melakukan Kegiatan Secara Bersama-Sama Dengan Suka Rela.',
                'sort_order' => 3,
            ],
            [
                'type' => 'misi',
                'content' => '⁠Terbentuknya Peserta Didik Yang Memiliki Pengetahuan Secara Akademis.',
                'sort_order' => 4,
            ],
            [
                'type' => 'misi',
                'content' => '⁠Meningkatkan Pengetahuan Dan Keterampilan Warga Sekolah Dalam Bidang Iptek.',
                'sort_order' => 5,
            ],
            [
                'type' => 'misi',
                'content' => '⁠⁠Membiasakan Sikap Dan Prilaku Tidak Ketergantungan Kepada Orang Lain.',
                'sort_order' => 6,
            ],
            [
                'type' => 'misi',
                'content' => '⁠Menanamkan Rasa Cinta Tanah Air, Mengenal Lagu-Lagu Kebangsaan Beserta Simbol-Simbol Negara.',
                'sort_order' => 7,
            ],
            [
                'type' => 'misi',
                'content' => '⁠⁠Menerapkan System Pembelajaran Yang Berorientasi Pada Profil Pelajar Pancasila..',
                'sort_order' => 8,
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
