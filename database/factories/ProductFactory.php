<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'currency_id' => Currency::factory(),
            'tax_cost' => fake()->randomFloat(2, 1, 50),
            'manufacturing_cost' => fake()->randomFloat(2, 5, 200),
        ];
    }
}
