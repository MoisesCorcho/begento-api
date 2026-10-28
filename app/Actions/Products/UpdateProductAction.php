<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\DTOs\Products\UpdateProductDTO;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

final class UpdateProductAction
{
    /**
     * Execute the product update.
     */
    public function __invoke(Product $product, UpdateProductDTO $dto): Product
    {
        return DB::transaction(function () use ($product, $dto): Product {
            $product->update($dto->toArray());

            return $product->refresh();
        });
    }
}
