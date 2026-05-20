<?php

namespace App\Models;

use Database\Factories\SchoolAchievementFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolAchievement extends Model
{
    /** @use HasFactory<SchoolAchievementFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'level',
        'year',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderByDesc('year')->orderBy('sort_order')->orderBy('id');
    }
}
