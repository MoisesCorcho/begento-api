<?php

declare(strict_types=1);

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists all multi-currency prices for a product', function (): void {
    $usd = Currency::factory()->create(['name' => 'US Dollar', 'symbol' => 'USD']);
    $eur = Currency::factory()->create(['name' => 'Euro', 'symbol' => 'EUR']);

    $product = Product::factory()->create(['currency_id' => $usd->id]);

    ProductPrice::factory()->create([
        'product_id' => $product->id,
        'currency_id' => $eur->id,
        'price' => 89.00,
    ]);

    $response = $this->getJson("/api/products/{$product->id}/prices");

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.currency.symbol', 'EUR')
        ->assertJsonPath('data.0.price', fn ($price) => (float) $price === 89.0);
});

it('returns 404 when listing prices for non-existent product', function (): void {
    $response = $this->getJson('/api/products/99999/prices');

    $response->assertNotFound();
});

it('creates a new price for a product in a currency', function (): void {
    $usd = Currency::factory()->create();
    $cop = Currency::factory()->create(['name' => 'Colombian Peso', 'symbol' => 'COP']);

    $product = Product::factory()->create(['currency_id' => $usd->id]);

    $response = $this->postJson("/api/products/{$product->id}/prices", [
        'currency_id' => $cop->id,
        'price' => 450000.00,
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.product_id', $product->id)
        ->assertJsonPath('data.currency_id', $cop->id)
        ->assertJsonPath('data.price', fn ($price) => (float) $price === 450000.0);

    $this->assertDatabaseHas('product_prices', [
        'product_id' => $product->id,
        'currency_id' => $cop->id,
        'price' => 450000.00,
    ]);
});

it('updates existing price when adding price with same currency (upsert)', function (): void {
    $usd = Currency::factory()->create();
    $eur = Currency::factory()->create();

    $product = Product::factory()->create(['currency_id' => $usd->id]);

    ProductPrice::factory()->create([
        'product_id' => $product->id,
        'currency_id' => $eur->id,
        'price' => 90.00,
    ]);

    $response = $this->postJson("/api/products/{$product->id}/prices", [
        'currency_id' => $eur->id,
        'price' => 95.50,
    ]);

    $response->assertOk()
        ->assertJsonPath('data.price', 95.50);

    $this->assertDatabaseCount('product_prices', 1);
    $this->assertDatabaseHas('product_prices', [
        'product_id' => $product->id,
        'currency_id' => $eur->id,
        'price' => 95.50,
    ]);
});

it('fails with 422 when creating price with invalid currency or negative price', function (): void {
    $product = Product::factory()->create();

    $response = $this->postJson("/api/products/{$product->id}/prices", [
        'currency_id' => 99999,
        'price' => -10,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['currency_id', 'price']);
});

it('returns 404 when creating price for non-existent product', function (): void {
    $currency = Currency::factory()->create();

    $response = $this->postJson('/api/products/99999/prices', [
        'currency_id' => $currency->id,
        'price' => 100,
    ]);

    $response->assertNotFound();
});
