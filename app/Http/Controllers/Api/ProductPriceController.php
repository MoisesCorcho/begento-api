<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Products\UpsertProductPriceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreProductPriceRequest;
use App\Http\Resources\Products\ProductPriceResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductPriceController extends Controller
{
    /**
     * Display a listing of prices for the specified product.
     */
    public function index(Product $product): AnonymousResourceCollection
    {
        $prices = $product->prices()->with('currency')->get();

        return ProductPriceResource::collection($prices);
    }

    /**
     * Store or update a price for the specified product.
     */
    public function store(
        StoreProductPriceRequest $request,
        Product $product,
        UpsertProductPriceAction $action
    ): JsonResponse {
        $productPrice = $action($request->toDTO((int) $product->id));

        $statusCode = $productPrice->wasRecentlyCreated ? 201 : 200;

        return (new ProductPriceResource($productPrice->load('currency')))
            ->response()
            ->setStatusCode($statusCode);
    }
}
