# Begento API — Prueba Técnica Backend

API RESTful desarrollada en **Laravel 12** con **PHP 8.5**, ejecutada en contenedores con **Laravel Sail** (MySQL 8.4), testeada con **Pest** y construida bajo **Spec-Driven Development (SDD)** con arquitectura limpia (DTOs, Actions invokables y Form Requests perimetrales).

---

## Requisitos y Stack

- **PHP**: 8.5
- **Framework**: Laravel 12 con Laravel Boost
- **Base de Datos**: MySQL 8.4 (en Sail) / SQLite (para tests en memoria)
- **Contenedores**: Docker y Laravel Sail (`compose.yaml`)
- **Testing**: Pest PHP
- **Estilo de Código**: Laravel Pint (`strict_types=1`, PSR-12, convenciones)

---

## Puesta en Marcha con Sail

1. **Clonar el repositorio y entrar al proyecto:**
   ```bash
   cd begento_api
   ```

2. **Copiar variables de entorno:**
   ```bash
   cp .env.example .env
   ```

3. **Iniciar los contenedores en segundo plano:**
   ```bash
   ./vendor/bin/sail up -d
   ```

4. **Ejecutar migraciones y seeders (divisas iniciales USD, EUR, COP, GBP):**
   ```bash
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```

5. **Acceder a la API y Documentación:**
   - **Root API Status:** [http://localhost](http://localhost)
   - **Documentación Interactiva Swagger / OpenAPI:** [http://localhost/docs](http://localhost/docs)
   - **Especificación OpenAPI YAML:** [http://localhost/docs/openapi.yaml](http://localhost/docs/openapi.yaml)
   - **Colección Postman:** [docs/postman_collection.json](docs/postman_collection.json)

---

## Pruebas Automatizadas (Pest)

La suite de pruebas cubre el 100% de los endpoints, incluyendo caminos felices (200/201/204), validaciones de negocio (422), manejo de no encontrados (404) e integridad referencial en cascada.

Para ejecutar los tests:
```bash
./vendor/bin/sail bin pest
# O directamente en el host:
vendor/bin/pest
```

---

## Endpoints de la API

Todos los endpoints responden en formato JSON y están disponibles tanto bajo el prefijo canónico `/api/` como directamente en la raíz para cumplimiento literal con la especificación técnica:

| Método | Endpoint | Descripción | Status |
|--------|----------|-------------|--------|
| `GET` | `/api/products` | Obtener lista de productos con su divisa base | `200 OK` |
| `POST` | `/api/products` | Crear un nuevo producto | `201 Created` |
| `GET` | `/api/products/{id}` | Obtener detalle de un producto por ID | `200 OK` / `404` |
| `PUT` | `/api/products/{id}` | Actualizar un producto existente | `200 OK` / `422` / `404` |
| `DELETE` | `/api/products/{id}` | Eliminar un producto y sus precios en cascada | `204 No Content` / `404` |
| `GET` | `/api/products/{id}/prices` | Obtener lista de precios en diferentes divisas | `200 OK` / `404` |
| `POST` | `/api/products/{id}/prices` | Crear o actualizar (upsert) precio en divisa | `201 Created` / `200 OK` |

---

## Documentación y Postman

1. **Swagger UI:** Navegar a [http://localhost/docs](http://localhost/docs).
2. **Postman / Insomnia:**
   - Importar el archivo [`docs/postman_collection.json`](docs/postman_collection.json).
   - La variable `baseUrl` viene preconfigurada como `http://localhost`.

---

## Decisiones de Arquitectura y Convenciones

- **Layout de Código:** *Tipo primero, área después* (`app/Actions/Products/`, `app/DTOs/Products/`, `app/Http/Requests/Products/`, `app/Http/Resources/Products/`).
- **Thin Controllers:** Los controladores se limitan a validar a través de Form Requests, transformar a DTOs tipados inmutables y delegar a Actions invokables (`__invoke`).
- **Integridad y Transacciones:** Las operaciones de persistencia multi-modelo se ejecutan dentro de `DB::transaction`.
- **Spec-Driven Development (SDD):** Las especificaciones de cada feature se encuentran en [`specs/`](specs/).
