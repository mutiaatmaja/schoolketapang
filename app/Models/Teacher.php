<?php

namespace App\Models;

use Database\Factories\TeacherFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    /** @use HasFactory<TeacherFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nuptk',
        'nip',
        'nik',
        'gender',
        'birth_place',
        'birth_date',
        'employment_status',
        'religion',
        'address',
        'phone',
        'email',
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

    public function homeroomClass(): HasOne
    {
        return $this->hasOne(SchoolClass::class, 'teacher_id');
    }
}
