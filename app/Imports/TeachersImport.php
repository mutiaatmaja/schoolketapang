<?php

namespace App\Imports;

use App\Models\Teacher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class TeachersImport implements ToCollection, WithHeadingRow
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

                Teacher::query()->updateOrCreate(
                    ['nik' => trim((string) $data['nik'])],
                    [
                        'name' => trim((string) $data['nama']),
                        'nuptk' => $this->nullableString($data['nuptk'] ?? null),
                        'nip' => $this->nullableString($data['nip'] ?? null),
                        'gender' => trim((string) $data['jenis_kelamin']),
                        'birth_place' => trim((string) $data['tempat_lahir']),
                        'birth_date' => $this->parseDate($data['tanggal_lahir']),
                        'employment_status' => trim((string) $data['status_kepegawaian']),
                        'religion' => trim((string) $data['agama']),
                        'address' => trim((string) $data['alamat']),
                        'phone' => trim((string) $data['hp']),
                        'email' => trim((string) $data['email']),
                    ],
                );

                $this->processedRows++;
            });
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'nuptk' => ['nullable', 'string', 'max:30'],
            'nip' => ['nullable', 'string', 'max:30'],
            'nik' => ['required', 'string', 'max:30'],
            'jenis_kelamin' => ['required', 'string', 'in:Laki-Laki,Perempuan'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required'],
            'status_kepegawaian' => ['required', 'string', 'max:100'],
            'agama' => ['required', 'string', 'max:50'],
            'alamat' => ['required', 'string'],
            'hp' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function validationAttributes(): array
    {
        return [
            'nama' => 'nama',
            'nuptk' => 'NUPTK',
            'nip' => 'NIP',
            'nik' => 'NIK',
            'jenis_kelamin' => 'jenis kelamin',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'status_kepegawaian' => 'status kepegawaian',
            'agama' => 'agama',
            'alamat' => 'alamat',
            'hp' => 'HP',
            'email' => 'email',
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
                'tanggal_lahir' => 'Format tanggal lahir guru tidak valid. Gunakan format YYYY-MM-DD.',
            ]);
        }
    }
}
