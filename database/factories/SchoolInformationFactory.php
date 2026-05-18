<?php

namespace Database\Factories;

use App\Models\SchoolInformation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolInformation>
 */
class SchoolInformationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'label' => fake()->unique()->randomElement([
                'NPSN',
                'Nama Sekolah',
                'Alamat',
                'No. Telepon',
                'Email',
                'Website',
                'Akreditasi',
            ]),
            'value' => fake()->sentence(3),
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
