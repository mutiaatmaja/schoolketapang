<?php

namespace App\Models;

use Database\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    /** @use HasFactory<StudentFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'school_class_id',
        'name',
        'nis',
        'nisn',
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
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }
}
