<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (['1', '2', '3', '4', '5', '6'] as $name) {
            SchoolClass::query()->updateOrCreate(
                ['name' => $name],
                ['teacher_id' => null],
            );
        }
    }
}
