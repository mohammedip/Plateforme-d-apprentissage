<?php

namespace Database\Factories;

use App\Models\Cours;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'description' => fake()->name(),
            'cours_id'=> Cours::inRandomOrder()->first(),
            'url'=> fake()->url(),
        ];
    }
}
