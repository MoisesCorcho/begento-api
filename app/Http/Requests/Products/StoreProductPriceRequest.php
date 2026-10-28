<?php

declare(strict_types=1);

namespace App\Http\Requests\Products;

use App\DTOs\Products\UpsertProductPriceDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductPriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Convert validated data to DTO.
     */
    public function toDTO(int $productId): UpsertProductPriceDTO
    {
        return UpsertProductPriceDTO::fromArray($productId, $this->validated());
    }
}
