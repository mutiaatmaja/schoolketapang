<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsTemplateExport implements FromArray, ShouldAutoSize, WithHeadings
{
    /**
     * @return list<array<int, string>>
     */
    public function array(): array
    {
        return [
            ['Ahmad Fauzi', '2025001', '1234567890', 'Ketapang', '2015-06-10', '3173000000000002', 'Laki-laki', 'Islam', 'Budi Santoso', 'Siti Rahma', 'Wiraswasta', 'Ibu Rumah Tangga', '081298765432', '081298765433', 'Jl. Pelajar No. 2, Ketapang', 'Siswa pindahan semester ganjil', '5', 'AKTIF'],
        ];
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'nama_siswa',
            'nis',
            'nisn',
            'tempat_lahir',
            'tanggal_lahir',
            'nik',
            'jenis_kelamin',
            'agama',
            'nama_ayah',
            'nama_ibu',
            'pekerjaan_ayah',
            'pekerjaan_ibu',
            'no_hp_ayah',
            'no_hp_ibu',
            'alamat',
            'keterangan',
            'kelas',
            'status',
        ];
    }
}
