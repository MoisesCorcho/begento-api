<?php

declare(strict_types=1);

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('deletes a product and its associated prices', function (): void {
    $currency = Currency::factory()->create();
    $product = Product::factory()->create(['currency_id' => $currency->id]);

    $price = ProductPrice::factory()->create([
        'product_id' => $product->id,
        'currency_id' => $currency->id,
        'price' => 99.00,
    ]);

    $response = $this->deleteJson("/api/products/{$product->id}");

    $response->assertNoContent();

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
    $this->assertDatabaseMissing('product_prices', ['id' => $price->id]);
});

it('returns 404 when deleting a non-existent product', function (): void {
    $response = $this->deleteJson('/api/products/99999');

    $response->assertNotFound();
});
