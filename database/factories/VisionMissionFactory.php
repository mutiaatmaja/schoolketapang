<?php

namespace Database\Factories;

use App\Models\VisionMission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VisionMission>
 */
class VisionMissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['visi', 'misi']),
            'content' => fake()->sentence(12),
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }

    public function vision(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'visi',
        ]);
    }

    public function mission(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'misi',
        ]);
    }
}
