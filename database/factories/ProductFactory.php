<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
            'kodebarang' => strtoupper(fake()->bothify('PRD-####')),
            'namabarang' => fake()->words(3, true),
            'harga'      => fake()->numberBetween(10000, 2500000),
        ];
    }
}
