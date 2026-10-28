<?php

declare(strict_types=1);

namespace App\DTOs\Products;

final readonly class UpsertProductPriceDTO
{
    public function __construct(
        public int $productId,
        public int $currencyId,
        public float $price,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(int $productId, array $data): self
    {
        return new self(
            productId: $productId,
            currencyId: (int) $data['currency_id'],
            price: (float) $data['price'],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'currency_id' => $this->currencyId,
            'price' => $this->price,
        ];
    }
}
