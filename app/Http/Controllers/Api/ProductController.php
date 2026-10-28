<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Products\CreateProductAction;
use App\Actions\Products\DeleteProductAction;
use App\Actions\Products\UpdateProductAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Resources\Products\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(): AnonymousResourceCollection
    {
        $products = Product::with('currency')->latest()->get();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductRequest $request, CreateProductAction $action): JsonResponse
    {
        $product = $action($request->toDTO());

        return (new ProductResource($product->load('currency')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->load(['currency', 'prices.currency']));
    }

    /**
     * Update the specified product.
     */
    public function update(
        UpdateProductRequest $request,
        Product $product,
        UpdateProductAction $action
    ): ProductResource {
        $updatedProduct = $action($product, $request->toDTO());

        return new ProductResource($updatedProduct->load('currency'));
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product, DeleteProductAction $action): Response
    {
        $action($product);

        return response()->noContent();
    }
}
