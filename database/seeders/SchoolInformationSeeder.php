<?php

namespace Database\Seeders;

use App\Models\SchoolInformation;
use Illuminate\Database\Seeder;

class SchoolInformationSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['label' => 'NPSN', 'value' => '20123456', 'sort_order' => 1],
            ['label' => 'Nama Sekolah', 'value' => 'SD Cerdas Ketapang', 'sort_order' => 2],
            ['label' => 'Alamat', 'value' => 'Jl. Pendidikan No. 123, Ketapang, Kalimantan Barat', 'sort_order' => 3],
            ['label' => 'No. Telepon', 'value' => '(0534) 123456', 'sort_order' => 4],
            ['label' => 'Email', 'value' => 'info@sdcerdas.sch.id', 'sort_order' => 5],
            ['label' => 'Website', 'value' => 'www.sdcerdas.sch.id', 'sort_order' => 6],
            ['label' => 'Akreditasi', 'value' => 'A', 'sort_order' => 7],
            ['label' => 'Motto Sekolah', 'value' => 'Cerdas, Berakhlak, dan Berprestasi Tanpa Batas', 'sort_order' => 8],
            ['label' => 'Informasi Sekolah', 'value' => 'Mencetak generasi cerdas dan berakhlak mulia melalui pendidikan berkualitas dan lingkungan yang mendukung.', 'sort_order' => 9],
        ];

        foreach ($items as $item) {
            SchoolInformation::query()->updateOrCreate(
                ['label' => $item['label']],
                $item,
            );
        }
    }
}
