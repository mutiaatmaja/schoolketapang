<?php

namespace App\Models;

use Database\Factories\SpmbRegistrationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class SpmbRegistration extends Model
{
    /** @use HasFactory<SpmbRegistrationFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'registration_number',
        'name',
        'birth_place',
        'birth_date',
        'nik',
        'family_card_number',
        'gender',
        'religion',
        'father_name',
        'mother_name',
        'father_occupation',
        'mother_occupation',
        'father_phone',
        'mother_phone',
        'address',
        'notes',
        'birth_certificate_path',
        'family_card_path',
        'student_photo_path',
        'kindergarten_certificate_path',
        'status',
        'validated_by_user_id',
        'validation_note',
        'validated_at',
        'submitted_at',
    ];

    protected $attributes = [
        'status' => 'submitted',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'validated_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by_user_id');
    }

    public function registrationReferenceDate(): ?Carbon
    {
        $referenceDate = $this->submitted_at ?? $this->created_at;

        if ($this->birth_date === null || $referenceDate === null) {
            return null;
        }

        return $referenceDate->copy()->startOfDay();
    }

    public function ageAtRegistrationDays(): ?int
    {
        $referenceDate = $this->registrationReferenceDate();

        if ($referenceDate === null) {
            return null;
        }

        return $this->birth_date?->copy()->startOfDay()->diffInDays($referenceDate);
    }

    public function ageAtRegistrationLabel(): string
    {
        $referenceDate = $this->registrationReferenceDate();

        if ($referenceDate === null || $this->birth_date === null) {
            return '-';
        }

        $age = $this->birth_date->copy()->startOfDay()->diff($referenceDate);

        return trim(sprintf('%d th %d bln', $age->y, $age->m));
    }
}
