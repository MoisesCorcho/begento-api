<?php

declare(strict_types=1);

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns product details by ID', function (): void {
    $currency = Currency::factory()->create([
        'name' => 'Euro',
        'symbol' => 'EUR',
    ]);

    $product = Product::factory()->create([
        'name' => 'Mechanical Keyboard',
        'description' => 'Tactile switches',
        'price' => 129.99,
        'currency_id' => $currency->id,
        'tax_cost' => 20.00,
        'manufacturing_cost' => 45.00,
    ]);

    $response = $this->getJson("/api/products/{$product->id}");

    $response->assertOk()
        ->assertJson([
            'data' => [
                'id' => $product->id,
                'name' => 'Mechanical Keyboard',
                'description' => 'Tactile switches',
                'price' => 129.99,
                'currency_id' => $currency->id,
                'currency' => [
                    'id' => $currency->id,
                    'name' => 'Euro',
                    'symbol' => 'EUR',
                ],
                'tax_cost' => 20.00,
                'manufacturing_cost' => 45.00,
            ],
        ]);
});

it('returns 404 when product ID does not exist', function (): void {
    $response = $this->getJson('/api/products/99999');

    $response->assertNotFound()
        ->assertExactJson([
            'message' => 'Resource not found.',
        ]);
});
