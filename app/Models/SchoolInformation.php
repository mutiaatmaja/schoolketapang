<?php

namespace App\Models;

use Database\Factories\SchoolInformationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolInformation extends Model
{
    /** @use HasFactory<SchoolInformationFactory> */
    use HasFactory;

    protected $table = 'school_information';

    protected $fillable = [
        'label',
        'value',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }
}
