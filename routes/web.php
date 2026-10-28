<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductPriceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'version' => '1.0.0',
        'status' => 'active',
        'documentation_url' => url('/docs'),
    ]);
});

// Swagger UI & OpenAPI Specification
Route::get('/docs', function () {
    return view('swagger');
});

Route::get('/docs/openapi.yaml', function () {
    return response(file_get_contents(base_path('docs/openapi.yaml')), 200, [
        'Content-Type' => 'text/yaml; charset=utf-8',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, OPTIONS',
    ]);
});

// Alias routes without /api prefix for literal compliance with Prueba Backend.pdf
Route::middleware('api')->name('alias.')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::get('products/{product}/prices', [ProductPriceController::class, 'index'])->name('products.prices.index');
    Route::post('products/{product}/prices', [ProductPriceController::class, 'store'])->name('products.prices.store');
});
