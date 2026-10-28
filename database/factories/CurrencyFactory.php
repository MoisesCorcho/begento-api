<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word().' Currency',
            'symbol' => strtoupper(fake()->lexify('???')),
            'exchange_rate' => fake()->randomFloat(4, 0.5, 4500.0),
        ];
    }
}
