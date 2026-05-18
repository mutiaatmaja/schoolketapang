<?php

namespace Database\Seeders;

use App\Models\NewsArticle;
use Illuminate\Database\Seeder;

class NewsArticleSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Seleksi Penerimaan Murid Baru (SPMB) Tahun Ajaran 2025/2026',
                'slug' => 'spmb-2025-2026',
                'category' => 'Pengumuman',
                'excerpt' => 'Pendaftaran SPMB dibuka dengan alur yang lebih sederhana, transparan, dan terintegrasi secara online.',
                'content' => implode("\n\n", [
                    'Sekolah membuka pendaftaran peserta didik baru untuk tahun ajaran 2025/2026 melalui jalur online agar orang tua lebih mudah mengakses informasi dan mengisi formulir dari rumah.',
                    'Calon pendaftar dapat menyiapkan dokumen persyaratan sejak awal, memeriksa jadwal seleksi, dan mengikuti status pendaftaran melalui dashboard SPMB.',
                    'Tim sekolah juga menyiapkan pendampingan bagi wali murid yang membutuhkan bantuan selama proses pendaftaran berlangsung.',
                ]),
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Seminar Parenting: Mendidik Anak di Era Digital',
                'slug' => 'seminar-parenting-digital',
                'category' => 'Kegiatan Sekolah',
                'excerpt' => 'Kegiatan bersama orang tua untuk membahas pendampingan anak di tengah penggunaan gawai dan media digital.',
                'content' => implode("\n\n", [
                    'Seminar parenting ini dihadirkan untuk membantu orang tua memahami tantangan pengasuhan anak pada era digital yang serba cepat dan penuh distraksi.',
                    'Narasumber membahas pola komunikasi keluarga, batas penggunaan gawai, serta cara membangun kebiasaan belajar yang sehat di rumah.',
                    'Acara ditutup dengan sesi tanya jawab sehingga wali murid dapat berdiskusi langsung mengenai situasi yang mereka hadapi sehari-hari.',
                ]),
                'status' => 'published',
                'published_at' => now()->subDays(6),
            ],
            [
                'title' => 'Jadwal Ujian Akhir Semester Genap',
                'slug' => 'jadwal-ujian-akhir-semester-genap',
                'category' => 'Akademik',
                'excerpt' => 'Informasi awal jadwal ujian akhir semester untuk seluruh jenjang kelas.',
                'content' => 'Jadwal ujian akhir semester sedang difinalisasi oleh tim akademik sekolah dan akan diumumkan setelah validasi kepala sekolah.',
                'status' => 'draft',
                'published_at' => null,
            ],
        ];

        foreach ($items as $item) {
            NewsArticle::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item,
            );
        }
    }
}
