<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->numerify(),
            'name' => fake()->name(),
            'quantity' => rand(0, 12),
            'price' => fake()->randomFloat(2, 10, 1000),
            'description' => fake()->paragraph,
        ];
    }
}