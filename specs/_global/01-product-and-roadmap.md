# Visión del Producto y Roadmap (Begento API)

## Visión del Producto

Desarrollar una **API RESTful robusta, predecible y de alto rendimiento en Laravel 12** para la gestión de productos y precios multi-divisa, siguiendo principios de arquitectura limpia pragmática:
- **Separación de responsabilidades:** Thin Controllers que delegan en Form Requests para validación perimetral, DTOs inmutables para transporte tipado, y Actions invokables (`__invoke`) para la ejecución del caso de uso de dominio.
- **Eloquent e integridad de datos:** Claves foráneas, índices de búsqueda y transacciones de base de datos (`DB::transaction`).
- **Respuesta JSON consistente:** Eloquent API Resources para serialización, con códigos HTTP semánticos (200, 201, 204, 404, 422).
- **Documentación y pruebas:** Cobertura de tests feature con Pest y especificación OpenAPI/Swagger y Postman.

---

## Modelo de Datos y Esquema

```
[ currencies ]
  - id: bigint unsigned (PK)
  - name: string(100)
  - symbol: string(10)
  - exchange_rate: decimal(12, 4)
  - timestamps

[ products ]
  - id: bigint unsigned (PK)
  - name: string(255)
  - description: text (nullable)
  - price: decimal(12, 2)
  - currency_id: bigint unsigned (FK -> currencies.id)
  - tax_cost: decimal(12, 2)
  - manufacturing_cost: decimal(12, 2)
  - timestamps

[ product_prices ]
  - id: bigint unsigned (PK)
  - product_id: bigint unsigned (FK -> products.id, cascade on delete)
  - currency_id: bigint unsigned (FK -> currencies.id, cascade on delete)
  - price: decimal(12, 2)
  - timestamps
  - UNIQUE(product_id, currency_id)
```

---

## Roadmap de Features

| ID | Slug | Estado | Descripción | Dependencias |
|----|------|--------|-------------|--------------|
| **F01** | `01-products-catalog-api` | En Especificación | Modelos (`Currency`, `Product`, `ProductPrice`), migraciones, seeders, CRUD de productos (`/products`), endpoints de precios multi-divisa (`/products/{id}/prices`), validaciones DTO/FormRequests, y suite Pest. | Ninguna |
| **F02** | `02-api-documentation` | Planificada | Documentación interactiva Swagger/OpenAPI y colección JSON para Postman/Insomnia. | F01 |
