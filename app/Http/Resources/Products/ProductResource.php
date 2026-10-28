<?php

declare(strict_types=1);

namespace App\Http\Resources\Products;

use App\Http\Resources\Currencies\CurrencyResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'currency_id' => $this->currency_id,
            'currency' => new CurrencyResource($this->whenLoaded('currency')),
            'tax_cost' => (float) $this->tax_cost,
            'manufacturing_cost' => (float) $this->manufacturing_cost,
            'prices' => ProductPriceResource::collection($this->whenLoaded('prices')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
