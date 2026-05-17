<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class BeritaController extends Controller
{
    private const ARTICLES = [
        'spmb-2025-2026' => [
            'title' => 'Seleksi Penerimaan Murid Baru (SPMB) Tahun Ajaran 2025/2026',
            'date' => '15 Oktober 2024',
            'category' => 'Pengumuman',
            'excerpt' => 'Pendaftaran SPMB dibuka dengan alur yang lebih sederhana, transparan, dan terintegrasi secara online.',
            'content' => [
                'Sekolah membuka pendaftaran peserta didik baru untuk tahun ajaran 2025/2026 melalui jalur online agar orang tua lebih mudah mengakses informasi dan mengisi formulir dari rumah.',
                'Calon pendaftar dapat menyiapkan dokumen persyaratan sejak awal, memeriksa jadwal seleksi, dan mengikuti status pendaftaran melalui dashboard SPMB.',
                'Tim sekolah juga menyiapkan pendampingan bagi wali murid yang membutuhkan bantuan selama proses pendaftaran berlangsung.',
            ],
        ],
        'seminar-parenting-digital' => [
            'title' => 'Seminar Parenting: Mendidik Anak di Era Digital',
            'date' => '12 Oktober 2024',
            'category' => 'Kegiatan Sekolah',
            'excerpt' => 'Kegiatan bersama orang tua untuk membahas pendampingan anak di tengah penggunaan gawai dan media digital.',
            'content' => [
                'Seminar parenting ini dihadirkan untuk membantu orang tua memahami tantangan pengasuhan anak pada era digital yang serba cepat dan penuh distraksi.',
                'Narasumber membahas pola komunikasi keluarga, batas penggunaan gawai, serta cara membangun kebiasaan belajar yang sehat di rumah.',
                'Acara ditutup dengan sesi tanya jawab sehingga wali murid dapat berdiskusi langsung mengenai situasi yang mereka hadapi sehari-hari.',
            ],
        ],
    ];

    public function show(string $slug): Response
    {
        abort_unless(isset(self::ARTICLES[$slug]), 404);

        $article = self::ARTICLES[$slug];

        return response()->view('berita.show', [
            'article' => $article,
            'slug' => $slug,
            'relatedArticles' => collect(self::ARTICLES)
                ->except($slug)
                ->map(fn (array $item, string $relatedSlug): array => $item + ['slug' => $relatedSlug])
                ->values()
                ->all(),
        ]);
    }
}
