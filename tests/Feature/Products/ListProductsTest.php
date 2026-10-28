<?php

declare(strict_types=1);

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns an empty list when no products exist', function (): void {
    $response = $this->getJson('/api/products');

    $response->assertOk()
        ->assertJson([
            'data' => [],
        ]);
});

it('lists all products with their base currency', function (): void {
    $currency = Currency::factory()->create([
        'name' => 'US Dollar',
        'symbol' => 'USD',
        'exchange_rate' => 1.0000,
    ]);

    $products = Product::factory()->count(3)->create([
        'currency_id' => $currency->id,
    ]);

    $response = $this->getJson('/api/products');

    $response->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'currency_id',
                    'currency' => [
                        'id',
                        'name',
                        'symbol',
                        'exchange_rate',
                    ],
                    'tax_cost',
                    'manufacturing_cost',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
});

it('responds successfully on the /products alias route', function (): void {
    $response = $this->getJson('/products');

    $response->assertOk()
        ->assertJson([
            'data' => [],
        ]);
});
