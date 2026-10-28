<?php

declare(strict_types=1);

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('updates product attributes successfully', function (): void {
    $currency = Currency::factory()->create();
    $newCurrency = Currency::factory()->create();

    $product = Product::factory()->create([
        'name' => 'Old Name',
        'price' => 100.00,
        'currency_id' => $currency->id,
    ]);

    $payload = [
        'name' => 'Updated Name',
        'description' => 'Updated Description',
        'price' => 150.00,
        'currency_id' => $newCurrency->id,
        'tax_cost' => 12.00,
        'manufacturing_cost' => 30.00,
    ];

    $response = $this->putJson("/api/products/{$product->id}", $payload);

    $response->assertOk()
        ->assertJsonPath('data.name', 'Updated Name')
        ->assertJsonPath('data.price', fn ($price) => (float) $price === 150.0)
        ->assertJsonPath('data.currency_id', $newCurrency->id);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Updated Name',
        'price' => 150.00,
        'currency_id' => $newCurrency->id,
    ]);
});

it('fails validation with 422 when updating with invalid values', function (): void {
    $currency = Currency::factory()->create();
    $product = Product::factory()->create(['currency_id' => $currency->id]);

    $response = $this->putJson("/api/products/{$product->id}", [
        'name' => '',
        'price' => -50,
        'currency_id' => 99999,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'price', 'currency_id']);
});

it('returns 404 when updating non-existent product', function (): void {
    $currency = Currency::factory()->create();

    $response = $this->putJson('/api/products/99999', [
        'name' => 'Ghost Product',
        'price' => 100.00,
        'currency_id' => $currency->id,
    ]);

    $response->assertNotFound();
});
