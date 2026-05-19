<?php

namespace App\Exports;

use App\Models\SpmbRegistration;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SpmbRegistrationExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    private int $rowNumber = 0;

    public function __construct(private readonly string $status) {}

    public function query(): Builder
    {
        return SpmbRegistration::query()
            ->where('status', $this->status)
            ->orderByDesc('validated_at')
            ->orderByDesc('submitted_at');
    }

    /** @return list<string> */
    public function headings(): array
    {
        return [
            'No',
            'Nomor Peserta',
            'Nama Siswa',
            'NIK',
            'No. Kartu Keluarga',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Umur Saat Daftar',
            'Nama Ayah',
            'Nama Ibu',
            'Pekerjaan Ayah',
            'Pekerjaan Ibu',
            'No. Telp Ayah',
            'No. Telp Ibu',
            'Alamat',
            'Status',
            'Catatan Validasi',
            'Waktu Daftar',
            'Waktu Validasi',
        ];
    }

    /**
     * @param  SpmbRegistration  $row
     * @return list<mixed>
     */
    public function map($row): array
    {
        $this->rowNumber++;

        $statusLabel = match ($row->status) {
            'submitted' => 'Belum Validasi',
            'verified' => 'Terverifikasi',
            'lulus' => 'Lulus',
            'cadangan' => 'Cadangan',
            'ditolak' => 'Ditolak',
            default => $row->status,
        };

        $genderLabel = match ($row->gender) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => $row->gender ?? '-',
        };

        return [
            $this->rowNumber,
            $row->registration_number,
            $row->name,
            $row->nik,
            $row->family_card_number,
            $genderLabel,
            $row->birth_place,
            $row->birth_date?->format('d/m/Y') ?? '-',
            $row->ageAtRegistrationLabel(),
            $row->father_name,
            $row->mother_name,
            $row->father_occupation,
            $row->mother_occupation,
            $row->father_phone,
            $row->mother_phone,
            $row->address,
            $statusLabel,
            $row->validation_note ?? '-',
            $row->submitted_at?->format('d/m/Y H:i') ?? '-',
            $row->validated_at?->format('d/m/Y H:i') ?? '-',
        ];
    }

    public function title(): string
    {
        return match ($this->status) {
            'submitted' => 'Belum Validasi',
            'verified' => 'Terverifikasi',
            'lulus' => 'Lulus',
            'cadangan' => 'Cadangan',
            'ditolak' => 'Ditolak',
            default => 'Peserta SPMB',
        };
    }
}
