<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_class_id' => SchoolClass::factory(),
            'name' => fake()->name(),
            'nis' => fake()->unique()->numerify('S#######'),
            'nisn' => fake()->boolean(70) ? fake()->unique()->numerify('##########') : null,
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-15 years', '-6 years')->format('Y-m-d'),
            'nik' => fake()->unique()->numerify('################'),
            'gender' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'religion' => fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),
            'father_occupation' => fake()->optional()->jobTitle(),
            'mother_occupation' => fake()->optional()->jobTitle(),
            'father_phone' => fake()->optional()->phoneNumber(),
            'mother_phone' => fake()->optional()->phoneNumber(),
            'address' => fake()->optional()->address(),
            'notes' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['AKTIF', 'LULUS', 'KELUAR']),
        ];
    }
}
