<?php

declare(strict_types=1);

namespace App\DTOs\Products;

final readonly class UpdateProductDTO
{
    public function __construct(
        public string $name,
        public ?string $description,
        public float $price,
        public int $currencyId,
        public float $taxCost = 0.0,
        public float $manufacturingCost = 0.0,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            description: isset($data['description']) ? (string) $data['description'] : null,
            price: (float) $data['price'],
            currencyId: (int) $data['currency_id'],
            taxCost: isset($data['tax_cost']) ? (float) $data['tax_cost'] : 0.0,
            manufacturingCost: isset($data['manufacturing_cost']) ? (float) $data['manufacturing_cost'] : 0.0,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'currency_id' => $this->currencyId,
            'tax_cost' => $this->taxCost,
            'manufacturing_cost' => $this->manufacturingCost,
        ];
    }
}
