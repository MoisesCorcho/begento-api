# F01 — API de Catálogo de Productos y Precios · Diseño Técnico

> **ID:** F01 · **Slug:** `01-products-catalog-api`  
> **Requirements:** [`requirements.md`](requirements.md)  
> **Convenciones:** [`.ai/guidelines/project-conventions.md`](../../../.ai/guidelines/project-conventions.md)  
> **Stack:** Laravel 12, PHP 8.5, Pest, Eloquent, MySQL 8.4 via Sail

---

## 1. Arquitectura y Layout de Código

Siguiendo la regla de **tipo primero, área después** establecida en las convenciones:

```text
app/
  Models/
    Currency.php
    Product.php
    ProductPrice.php
  DTOs/
    Products/
      CreateProductDTO.php
      UpdateProductDTO.php
      UpsertProductPriceDTO.php
  Actions/
    Products/
      CreateProductAction.php
      UpdateProductAction.php
      DeleteProductAction.php
      UpsertProductPriceAction.php
  Http/
    Controllers/
      Api/
        ProductController.php
        ProductPriceController.php
    Requests/
      Products/
        StoreProductRequest.php
        UpdateProductRequest.php
        StoreProductPriceRequest.php
    Resources/
      Products/
        ProductResource.php
        ProductPriceResource.php
      Currencies/
        CurrencyResource.php
```

---

## 2. Esquema de Base de Datos y Migraciones

### Tabla `currencies`
```php
Schema::create('currencies', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100);
    $table->string('symbol', 10);
    $table->decimal('exchange_rate', 12, 4)->default(1.0000);
    $table->timestamps();
});
```

### Tabla `products`
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name', 255);
    $table->text('description')->nullable();
    $table->decimal('price', 12, 2);
    $table->foreignId('currency_id')->constrained('currencies')->restrictOnDelete();
    $table->decimal('tax_cost', 12, 2)->default(0.00);
    $table->decimal('manufacturing_cost', 12, 2)->default(0.00);
    $table->timestamps();
});
```

### Tabla `product_prices`
```php
Schema::create('product_prices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
    $table->foreignId('currency_id')->constrained('currencies')->cascadeOnDelete();
    $table->decimal('price', 12, 2);
    $table->timestamps();

    $table->unique(['product_id', 'currency_id']);
});
```

---

## 3. DTOs (Data Transfer Objects)

DTOs inmutables con `readonly class`, constructor promotion y tipos estrictos:

```php
declare(strict_types=1);

namespace App\DTOs\Products;

final readonly class CreateProductDTO
{
    public function __construct(
        public string $name,
        public ?string $description,
        public float $price,
        public int $currencyId,
        public float $taxCost,
        public float $manufacturingCost,
    ) {}
}
```

```php
declare(strict_types=1);

namespace App\DTOs\Products;

final readonly class UpdateProductDTO
{
    public function __construct(
        public string $name,
        public ?string $description,
        public float $price,
        public int $currencyId,
        public float $taxCost,
        public float $manufacturingCost,
    ) {}
}
```

```php
declare(strict_types=1);

namespace App\DTOs\Products;

final readonly class UpsertProductPriceDTO
{
    public function __construct(
        public int $productId,
        public int $currencyId,
        public float $price,
    ) {}
}
```

---

## 4. Actions de Dominio

- **`CreateProductAction`**: Invokable (`__invoke(CreateProductDTO $dto): Product`). Abre `DB::transaction` para persistir el producto.
- **`UpdateProductAction`**: Invokable (`__invoke(Product $product, UpdateProductDTO $dto): Product`). Abre `DB::transaction` y actualiza atributos.
- **`DeleteProductAction`**: Invokable (`__invoke(Product $product): void`). Abre `DB::transaction` y elimina el producto (la cascada en BD o modelo limpia `product_prices`).
- **`UpsertProductPriceAction`**: Invokable (`__invoke(UpsertProductPriceDTO $dto): ProductPrice`). Usa `updateOrCreate(['product_id' => ..., 'currency_id' => ...], ['price' => ...])`.

---

## 5. Capa HTTP (Controllers, Requests, Resources)

### Thin Controllers
Los controladores se mantienen delgados:
```php
public function store(StoreProductRequest $request, CreateProductAction $action): JsonResponse
{
    $product = $action($request->toDTO());

    return (new ProductResource($product->load('currency')))
        ->response()
        ->setStatusCode(201);
}
```

### Form Requests
- `StoreProductRequest`: Reglas `required`, `numeric`, `min:0`, `exists:currencies,id`. Método `toDTO(): CreateProductDTO`.
- `UpdateProductRequest`: Mismas reglas de consistencia de campos, método `toDTO(): UpdateProductDTO`.
- `StoreProductPriceRequest`: Reglas `required`, `exists:currencies,id`, `numeric`, `min:0`. Método `toDTO(int $productId): UpsertProductPriceDTO`.

### Resources
- `ProductResource`:
  - `id`, `name`, `description`, `price`, `currency_id`, `tax_cost`, `manufacturing_cost`, `currency` (CurrencyResource), `created_at`, `updated_at`.
- `ProductPriceResource`:
  - `id`, `product_id`, `currency_id`, `price`, `currency` (CurrencyResource), `created_at`, `updated_at`.
- `CurrencyResource`:
  - `id`, `name`, `symbol`, `exchange_rate`.

---

## 6. Rutas y Compatibilidad con el Enunciado

En `routes/api.php`:
```php
Route::apiResource('products', ProductController::class);
Route::get('products/{product}/prices', [ProductPriceController::class, 'index']);
Route::post('products/{product}/prices', [ProductPriceController::class, 'store']);
```

Para asegurar total cumplimiento con `Prueba Backend.pdf` (que menciona `/products` directamente), se incluye un fallback o prefixing transparente en `bootstrap/app.php` o `routes/web.php` para que tanto `/products` como `/api/products` funcionen idénticamente.

---

## 7. Manejo de Errores y Seguridad

- Excepciones de Eloquent (`ModelNotFoundException`) se capturan devolviendo JSON con status 404 estandarizado.
- Fallos de validación en Form Request devuelven 422 con formato JSON estándar de Laravel.
- Sanitización de strings en `name` y `description`.
- Validación de que los costos sean `>= 0`.
