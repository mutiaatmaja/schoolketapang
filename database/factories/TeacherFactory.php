<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nuptk' => fake()->optional()->numerify('################'),
            'nip' => fake()->optional()->numerify('##################'),
            'nik' => fake()->unique()->numerify('################'),
            'gender' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-55 years', '-22 years')->format('Y-m-d'),
            'employment_status' => fake()->randomElement(['Tetap', 'Honorer', 'Kontrak']),
            'religion' => fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
