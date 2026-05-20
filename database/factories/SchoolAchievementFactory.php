<?php

namespace Database\Factories;

use App\Models\SchoolAchievement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolAchievement>
 */
class SchoolAchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(['Juara 1', 'Juara 2', 'Juara 3', 'Medali Emas', 'Finalis Terbaik']),
            'description' => fake()->sentence(4),
            'level' => fake()->randomElement(['Sekolah', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional']),
            'year' => (int) fake()->year(),
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
