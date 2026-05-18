<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeachersTemplateExport implements FromArray, ShouldAutoSize, WithHeadings
{
    /**
     * @return list<array<int, string>>
     */
    public function array(): array
    {
        return [
            ['Siti Aminah', '1234567890123456', '198612312010012001', '3173000000000001', 'Perempuan', 'Ketapang', '1986-12-31', 'Tetap', 'Islam', 'Jl. Merdeka No. 10, Ketapang', '081234567890', 'siti.aminah@example.com'],
        ];
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'nama',
            'nuptk',
            'nip',
            'nik',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'status_kepegawaian',
            'agama',
            'alamat',
            'hp',
            'email',
        ];
    }
}
