<?php

namespace App\Models;

use Database\Factories\VisionMissionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisionMission extends Model
{
    /** @use HasFactory<VisionMissionFactory> */
    use HasFactory;

    protected $fillable = [
        'type',
        'content',
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
        $query->orderBy('type')->orderBy('sort_order')->orderBy('id');
    }
}
