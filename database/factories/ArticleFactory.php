<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            [
                'title' => $this->faker->sentence(5),
                'perex' => $this->faker->sentence(10),
                'content' => $this->faker->paragraph(3),
                'author' => $this->faker->name(),
                'published_at' => now(),
            ]
        ];
    }
}
