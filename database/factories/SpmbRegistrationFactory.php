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
            'registration_number' => 'SPMB-'.now()->format('Y').'-'.fake()->unique()->numerify('#####'),
            'name' => fake()->name(),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-12 years', '-5 years')->format('Y-m-d'),
            'nik' => fake()->unique()->numerify('################'),
            'family_card_number' => fake()->unique()->numerify('################'),
            'gender' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'religion' => fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),
            'father_occupation' => fake()->jobTitle(),
            'mother_occupation' => fake()->jobTitle(),
            'father_phone' => fake()->phoneNumber(),
            'mother_phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'notes' => fake()->sentence(),
            'birth_certificate_path' => 'spmb/birth-certificates/sample.pdf',
            'family_card_path' => 'spmb/family-cards/sample.pdf',
            'student_photo_path' => 'spmb/student-photos/sample.jpg',
            'kindergarten_certificate_path' => 'spmb/kindergarten-certificates/sample.pdf',
            'status' => fake()->randomElement(['submitted', 'verified', 'rejected']),
            'submitted_at' => now(),
        ];
    }
}
