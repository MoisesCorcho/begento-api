<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\DTOs\Products\UpsertProductPriceDTO;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;

final class UpsertProductPriceAction
{
    /**
     * Execute the product price upsert.
     */
    public function __invoke(UpsertProductPriceDTO $dto): ProductPrice
    {
        return DB::transaction(function () use ($dto): ProductPrice {
            return ProductPrice::updateOrCreate(
                [
                    'product_id' => $dto->productId,
                    'currency_id' => $dto->currencyId,
                ],
                [
                    'price' => $dto->price,
                ]
            );
        });
    }
}
