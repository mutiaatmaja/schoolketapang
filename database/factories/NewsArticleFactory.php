<?php

namespace Database\Factories;

use App\Models\NewsArticle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NewsArticle>
 */
class NewsArticleFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(5);

        return [
            'title' => $title,
            'slug' => str(fake()->slug(5))->lower()->toString(),
            'category' => fake()->randomElement(['Pengumuman', 'Kegiatan Sekolah', 'Prestasi', 'Agenda']),
            'excerpt' => fake()->paragraph(),
            'content' => implode("\n\n", fake()->paragraphs(3)),
            'status' => fake()->randomElement(['draft', 'published']),
            'published_at' => now(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}
