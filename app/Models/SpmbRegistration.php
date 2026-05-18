<?php

namespace App\Models;

use Database\Factories\SpmbRegistrationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpmbRegistration extends Model
{
    /** @use HasFactory<SpmbRegistrationFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'registration_number',
        'name',
        'birth_place',
        'birth_date',
        'nik',
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
            'submitted_at' => 'datetime',
        ];
    }

    public function scopeLatestFirst($query)
    {
        return $query->latest('submitted_at')->latest('id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
