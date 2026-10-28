# F01 — API de Catálogo de Productos y Precios · Tasks

> **ID:** F01 · **Slug:** `01-products-catalog-api`  
> **Requirements:** [`requirements.md`](requirements.md) · **Design:** [`design.md`](design.md)  
> **Convenciones:** [`.ai/guidelines/project-conventions.md`](../../../.ai/guidelines/project-conventions.md)  
> **Calidad:** [`specs/_global/02-feature-quality.md`](../../_global/02-feature-quality.md)

---

## 1. Migraciones, Modelos y Seeders

- [x] 1.1 Crear migración de `currencies` y modelo `App\Models\Currency` con casts (`exchange_rate` => 'decimal:4'). _(cubre R1, R4, R16)_
- [x] 1.2 Crear migración de `products` con FK a `currencies` y modelo `App\Models\Product` con relaciones `currency()` y `prices()`. _(cubre R1, R2, R4, R6, R9, R16)_
- [x] 1.3 Crear migración de `product_prices` con FKs en cascada, restricción unique `[product_id, currency_id]`, y modelo `App\Models\ProductPrice`. _(cubre R11, R13, R16)_
- [x] 1.4 Crear factories `CurrencyFactory`, `ProductFactory` y `ProductPriceFactory` para la suite de tests. _(cubre R1–R16)_
- [x] 1.5 Crear `CurrencySeeder` con divisas base (USD, EUR, COP, etc.) y registrar en `DatabaseSeeder`. _(cubre R1, R4)_

## 2. DTOs de Dominio

- [x] 2.1 Crear `App\DTOs\Products\CreateProductDTO`. _(cubre R4, R16)_
- [x] 2.2 Crear `App\DTOs\Products\UpdateProductDTO`. _(cubre R6, R16)_
- [x] 2.3 Crear `App\DTOs\Products\UpsertProductPriceDTO`. _(cubre R13, R16)_

## 3. Actions de Dominio

- [x] 3.1 Implementar `App\Actions\Products\CreateProductAction` con `DB::transaction`. _(cubre R4, R16)_
- [x] 3.2 Implementar `App\Actions\Products\UpdateProductAction` con `DB::transaction`. _(cubre R6, R16)_
- [x] 3.3 Implementar `App\Actions\Products\DeleteProductAction` con `DB::transaction`. _(cubre R9, R16)_
- [x] 3.4 Implementar `App\Actions\Products\UpsertProductPriceAction` con `updateOrCreate`. _(cubre R13, R16)_

## 4. Capa HTTP (Requests y Resources)

- [x] 4.1 Crear `App\Http\Requests\Products\StoreProductRequest` con método `toDTO()`. _(cubre R4, R5, R15, R16)_
- [x] 4.2 Crear `App\Http\Requests\Products\UpdateProductRequest` con método `toDTO()`. _(cubre R6, R7, R16)_
- [x] 4.3 Crear `App\Http\Requests\Products\StoreProductPriceRequest` con método `toDTO(int $productId)`. _(cubre R13, R15, R16)_
- [x] 4.4 Crear `App\Http\Resources\Currencies\CurrencyResource`. _(cubre R1, R2, R11, R17)_
- [x] 4.5 Crear `App\Http\Resources\Products\ProductResource`. _(cubre R1, R2, R4, R6, R17)_
- [x] 4.6 Crear `App\Http\Resources\Products\ProductPriceResource`. _(cubre R11, R13, R17)_

## 5. Controladores y Rutas

- [x] 5.1 Implementar `App\Http\Controllers\Api\ProductController` (`index`, `store`, `show`, `update`, `destroy`). _(cubre R1, R2, R3, R4, R5, R6, R7, R8, R9, R10, R17)_
- [x] 5.2 Implementar `App\Http\Controllers\Api\ProductPriceController` (`index`, `store`). _(cubre R11, R12, R13, R14, R15, R17)_
- [x] 5.3 Registrar rutas en `routes/api.php` y habilitar alias para que respondan tanto en `/api/products` como en `/products`. _(cubre R18)_
- [x] 5.4 Configurar renderizado JSON en `bootstrap/app.php` para excepciones `NotFoundHttpException` y `ValidationException`. _(cubre R3, R5, R7, R8, R10, R12, R14, R15, R17)_

## 6. Pruebas Automatizadas con Pest

- [x] 6.1 Feature test: `tests/Feature/Products/ListProductsTest.php` (verifica listado, paginación o colección, divisa base, 200 OK). _(cubre R1, R17, R18)_
- [x] 6.2 Feature test: `tests/Feature/Products/GetProductTest.php` (verifica producto por ID 200 OK y no encontrado 404). _(cubre R2, R3, R17)_
- [x] 6.3 Feature test: `tests/Feature/Products/CreateProductTest.php` (creación exitosa 201, validaciones 422 por campos faltantes/negativos/divisa inexistente). _(cubre R4, R5, R16, R17)_
- [x] 6.4 Feature test: `tests/Feature/Products/UpdateProductTest.php` (actualización exitosa 200, validaciones 422, ID inexistente 404). _(cubre R6, R7, R8, R16, R17)_
- [x] 6.5 Feature test: `tests/Feature/Products/DeleteProductTest.php` (eliminación 204 No Content, cascada en precios, ID inexistente 404). _(cubre R9, R10, R16, R17)_
- [x] 6.6 Feature test: `tests/Feature/Products/ProductPricesTest.php` (listado de precios 200, creación/upsert de precio 201/200, validaciones 422, 404 en producto no encontrado). _(cubre R11, R12, R13, R14, R15, R16, R17)_

## 7. Verificación y Estilo

- [x] 7.1 Ejecutar suite completa con `vendor/bin/pest` asegurando 100% verde.
- [x] 7.2 Formatear código con `vendor/bin/pint --dirty --format agent`.

---

## Definition of Done (DoD)

- [x] Todos los 7 endpoints requeridos por `Prueba Backend.pdf` funcionan y devuelven JSON.
- [x] Las migraciones se ejecutan limpiamente en MySQL y SQLite.
- [x] La suite de tests Pest cubre todos los escenarios (caminos felices, 422, 404, cascadas).
- [x] Pint no reporta problemas de estilo.
