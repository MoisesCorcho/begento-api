<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\DTOs\Products\CreateProductDTO;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

final class CreateProductAction
{
    /**
     * Execute the product creation.
     */
    public function __invoke(CreateProductDTO $dto): Product
    {
        return DB::transaction(function () use ($dto): Product {
            return Product::create($dto->toArray());
        });
    }
}
