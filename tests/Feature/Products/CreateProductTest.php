<?php

declare(strict_types=1);

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a product successfully with valid payload', function (): void {
    $currency = Currency::factory()->create();

    $payload = [
        'name' => 'Wireless Mouse',
        'description' => 'Ergonomic 2.4GHz mouse',
        'price' => 49.99,
        'currency_id' => $currency->id,
        'tax_cost' => 8.50,
        'manufacturing_cost' => 15.00,
    ];

    $response = $this->postJson('/api/products', $payload);

    $response->assertCreated()
        ->assertJsonPath('data.name', 'Wireless Mouse')
        ->assertJsonPath('data.price', 49.99)
        ->assertJsonPath('data.currency_id', $currency->id);

    $this->assertDatabaseHas('products', [
        'name' => 'Wireless Mouse',
        'currency_id' => $currency->id,
    ]);
});

it('fails validation with 422 when required fields are missing', function (): void {
    $response = $this->postJson('/api/products', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'price', 'currency_id']);
});

it('fails validation with 422 when price is negative', function (): void {
    $currency = Currency::factory()->create();

    $response = $this->postJson('/api/products', [
        'name' => 'Invalid Product',
        'price' => -10.00,
        'currency_id' => $currency->id,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['price']);
});

it('fails validation with 422 when currency_id does not exist', function (): void {
    $response = $this->postJson('/api/products', [
        'name' => 'Product without valid currency',
        'price' => 25.00,
        'currency_id' => 99999,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['currency_id']);
});
