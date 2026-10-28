# F01 — API de Catálogo de Productos y Precios Multi-Divisa

> **Estado:** En Especificación  
> **ID:** F01 · **Slug:** `01-products-catalog-api`  
> **Prerequisitos:** Scaffolding inicial (Laravel 12, PHP 8.5, Pest, Sail, MySQL)

---

## 1. Fuentes Canónicas

| Documento | Ubicación |
|-----------|-----------|
| Enunciado oficial de la prueba | `Prueba Backend.pdf` |
| Convenciones de arquitectura | `.ai/guidelines/project-conventions.md` / `AGENTS.md` |
| Criterios de calidad EARS | `specs/_global/02-feature-quality.md` |
| Roadmap | `specs/_global/01-product-and-roadmap.md` |

---

## 2. User Stories

1. **Como** cliente consumidor de la API, **quiero** listar los productos registrados con sus costos y su divisa base, **para** visualizar el catálogo disponible.
2. **Como** cliente de la API, **quiero** crear un producto especificando nombre, descripción, precio, divisa base, costo de impuestos y costo de fabricación, **para** incorporar nuevos ítems al catálogo.
3. **Como** cliente de la API, **quiero** consultar un producto específico por su identificador único, **para** obtener su detalle completo.
4. **Como** cliente de la API, **quiero** actualizar los datos de un producto existente, **para** mantener actualizada la información comercial y de costos.
5. **Como** cliente de la API, **quiero** eliminar un producto por su identificador, **para** retirarlo del catálogo junto con sus precios asociados.
6. **Como** cliente de la API, **quiero** consultar la lista de precios de un producto en diferentes divisas, **para** conocer las cotizaciones disponibles para venta internacional.
7. **Como** cliente de la API, **quiero** registrar o actualizar el precio de un producto en una divisa específica, **para** soportar ventas multi-moneda.

---

## 3. Criterios de Aceptación (EARS)

### Listado y Consulta de Productos

- **R1 (Event-driven):** WHEN a `GET /api/products` request is received, the system shall return HTTP 200 with the collection of products formatted as JSON, including related base currency details.
- **R2 (Event-driven):** WHEN a `GET /api/products/{id}` request is received with an existing product ID, the system shall return HTTP 200 with the product details in JSON format.
- **R3 (Unwanted behavior):** IF a `GET /api/products/{id}` request references a non-existent product ID, THEN the system shall return HTTP 404 with a structured JSON error message.

### Creación de Productos

- **R4 (Event-driven):** WHEN a `POST /api/products` request is received with valid payload (`name`, `description`, `price`, `currency_id`, `tax_cost`, `manufacturing_cost`), the system shall persist the product and return HTTP 201 Created with the created product in JSON format.
- **R5 (Unwanted behavior):** IF a `POST /api/products` request has missing required fields, non-numeric prices, negative values, or a `currency_id` that does not exist in `currencies`, THEN the system shall return HTTP 422 Unprocessable Entity with a validation errors bag.

### Actualización de Productos

- **R6 (Event-driven):** WHEN a `PUT /api/products/{id}` request is received with valid fields for an existing product, the system shall update the product attributes and return HTTP 200 with the updated product JSON.
- **R7 (Unwanted behavior):** IF a `PUT /api/products/{id}` request contains invalid data (negative amounts, invalid currency_id), THEN the system shall return HTTP 422 Unprocessable Entity.
- **R8 (Unwanted behavior):** IF a `PUT /api/products/{id}` request targets a non-existent product ID, THEN the system shall return HTTP 404 Not Found.

### Eliminación de Productos

- **R9 (Event-driven):** WHEN a `DELETE /api/products/{id}` request is received for an existing product, the system shall delete the product along with its associated records in `product_prices` and return HTTP 204 No Content.
- **R10 (Unwanted behavior):** IF a `DELETE /api/products/{id}` request targets a non-existent product ID, THEN the system shall return HTTP 404 Not Found.

### Gestión de Precios Multi-Divisa

- **R11 (Event-driven):** WHEN a `GET /api/products/{id}/prices` request is received for an existing product, the system shall return HTTP 200 with the list of registered prices in different currencies (including currency symbol and exchange rate).
- **R12 (Unwanted behavior):** IF a `GET /api/products/{id}/prices` request references a non-existent product ID, THEN the system shall return HTTP 404 Not Found.
- **R13 (Event-driven):** WHEN a `POST /api/products/{id}/prices` request is received with valid `currency_id` and positive `price` for an existing product, the system shall upsert the price record for that product and currency, returning HTTP 201 Created (or 200 OK if updated) with the price resource.
- **R14 (Unwanted behavior):** IF a `POST /api/products/{id}/prices` request references a non-existent product ID, THEN the system shall return HTTP 404 Not Found.
- **R15 (Unwanted behavior):** IF a `POST /api/products/{id}/prices` request contains an invalid or non-existent `currency_id`, or a non-numeric/negative `price`, THEN the system shall return HTTP 422 Unprocessable Entity.

### Invariantes Globales

- **R16 (Ubiquitous):** The system shall ensure that all monetary amounts (`price`, `tax_cost`, `manufacturing_cost`, `exchange_rate`) are non-negative decimals with two decimal precision (exchange_rate up to four decimals).
- **R17 (Ubiquitous):** The system shall respond with `Content-Type: application/json` on all endpoints and error states.
- **R18 (Ubiquitous):** The system shall support both `/api/products...` and `/products...` route aliases to guarantee 100% compliance with the exact routes defined in `Prueba Backend.pdf`.
