<?php

namespace App\Imports;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class StudentsImport implements ToCollection, WithHeadingRow
{
    private int $processedRows = 0;

    public function collection(Collection $collection): void
    {

        $collection
            ->map(fn (array|Collection $row): Collection => collect($row))
            ->map(fn (Collection $row): Collection => $this->normalizeRow($row))
            ->filter(fn (Collection $row): bool => $row->filter(fn ($value) => $value !== null && $value !== '')->isNotEmpty())
            ->each(function (Collection $row): void {
                $data = Validator::make($row->all(), $this->rules(), [], $this->validationAttributes())->validate();
                $schoolClass = SchoolClass::query()->firstOrCreate(['name' => trim((string) $data['kelas'])]);

                Student::query()->updateOrCreate(
                    ['nis' => trim((string) $data['nis'])],
                    [
                        'school_class_id' => $schoolClass->id,
                        'name' => trim((string) $data['nama_siswa']),
                        'nisn' => $this->nullableString($data['nisn'] ?? null),
                        'birth_place' => trim((string) $data['tempat_lahir']),
                        'birth_date' => $this->parseDate($data['tanggal_lahir']),
                        'nik' => $this->nullableString($data['nik'] ?? null),
                        'gender' => trim((string) $data['jenis_kelamin']),
                        'religion' => trim((string) $data['agama']),
                        'father_name' => $this->nullableString($data['nama_ayah'] ?? null),
                        'mother_name' => $this->nullableString($data['nama_ibu'] ?? null),
                        'father_occupation' => $this->nullableString($data['pekerjaan_ayah'] ?? null),
                        'mother_occupation' => $this->nullableString($data['pekerjaan_ibu'] ?? null),
                        'father_phone' => $this->nullableString($data['no_hp_ayah'] ?? null),
                        'mother_phone' => $this->nullableString($data['no_hp_ibu'] ?? null),
                        'address' => $this->nullableString($data['alamat'] ?? null),
                        'notes' => $this->nullableString($data['keterangan'] ?? null),
                        'status' => strtoupper(trim((string) $data['status'])),
                    ],
                );

                $this->processedRows++;
            });
    }

    public function rules(): array
    {
        return [
            'nama_siswa' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:30'],
            'nisn' => ['nullable', 'string', 'max:30'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required'],
            'nik' => ['nullable', 'string', 'max:30'],
            'jenis_kelamin' => ['required', 'string', 'in:Laki-Laki,Perempuan'],
            'agama' => ['required', 'string', 'max:50'],
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
            'pekerjaan_ayah' => ['nullable', 'string', 'max:255'],
            'pekerjaan_ibu' => ['nullable', 'string', 'max:255'],
            'no_hp_ayah' => ['nullable', 'string', 'max:30'],
            'no_hp_ibu' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
            'keterangan' => ['nullable', 'string'],
            'kelas' => ['required', 'string', 'in:1,2,3,4,5,6'],
            'status' => ['required', 'string', 'in:AKTIF,LULUS,KELUAR'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function validationAttributes(): array
    {
        return [
            'nama_siswa' => 'nama siswa',
            'nis' => 'NIS',
            'nisn' => 'NISN',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'nik' => 'NIK',
            'jenis_kelamin' => 'jenis kelamin',
            'agama' => 'agama',
            'nama_ayah' => 'nama ayah',
            'nama_ibu' => 'nama ibu',
            'pekerjaan_ayah' => 'pekerjaan ayah',
            'pekerjaan_ibu' => 'pekerjaan ibu',
            'no_hp_ayah' => 'no hp ayah',
            'no_hp_ibu' => 'no hp ibu',
            'alamat' => 'alamat',
            'keterangan' => 'keterangan',
            'kelas' => 'kelas',
            'status' => 'status',
        ];
    }

    public function processedRows(): int
    {
        return $this->processedRows;
    }

    private function normalizeRow(Collection $row): Collection
    {
        return $row->map(function (mixed $value): mixed {
            if ($value === null) {
                return null;
            }

            if (is_string($value)) {
                return trim($value);
            }

            if (is_int($value) || is_float($value)) {
                return (string) $value;
            }

            return $value;
        });
    }

    private function nullableString(mixed $value): ?string
    {
        $string = trim((string) $value);

        return $string === '' ? null : $string;
    }

    private function parseDate(mixed $value): string
    {
        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value))->toDateString();
        }

        try {
            return Carbon::parse((string) $value)->toDateString();
        } catch (\Throwable $throwable) {
            throw ValidationException::withMessages([
                'tanggal_lahir' => 'Format tanggal lahir siswa tidak valid. Gunakan format YYYY-MM-DD.',
            ]);
        }
    }
}
