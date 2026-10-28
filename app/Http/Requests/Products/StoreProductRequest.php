<?php

declare(strict_types=1);

namespace App\Http\Requests\Products;

use App\DTOs\Products\CreateProductDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'tax_cost' => ['nullable', 'numeric', 'min:0'],
            'manufacturing_cost' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Convert validated data to DTO.
     */
    public function toDTO(): CreateProductDTO
    {
        return CreateProductDTO::fromArray($this->validated());
    }
}
