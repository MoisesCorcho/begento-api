<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

final class DeleteProductAction
{
    /**
     * Execute the product deletion.
     */
    public function __invoke(Product $product): void
    {
        DB::transaction(function () use ($product): void {
            $product->delete();
        });
    }
}
