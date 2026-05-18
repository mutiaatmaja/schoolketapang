<?php

namespace Database\Factories;

use App\Models\SpmbRegistration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SpmbRegistration>
 */
class SpmbRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'registration_number' => 'SPMB-'.now()->format('Y').'-'.fake()->unique()->numerify('####'),
            'class_level' => fake()->randomElement(['1', '2', '3', '4', '5', '6']),
            'name' => fake()->name(),
            'nis' => fake()->boolean(40) ? fake()->numerify('#######') : null,
            'nisn' => fake()->boolean(60) ? fake()->numerify('##########') : null,
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-12 years', '-5 years')->format('Y-m-d'),
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
            'birth_certificate_path' => 'spmb/birth-certificates/sample.pdf',
            'family_card_path' => 'spmb/family-cards/sample.pdf',
            'student_photo_path' => 'spmb/student-photos/sample.jpg',
            'kindergarten_certificate_path' => fake()->boolean(50) ? 'spmb/kindergarten-certificates/sample.pdf' : null,
            'status' => 'submitted',
            'submitted_at' => now(),
        ];
    }
}
