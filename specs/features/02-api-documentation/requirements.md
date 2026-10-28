# F02 — Documentación de la API (OpenAPI/Swagger & Postman)

> **Estado:** En Especificación  
> **ID:** F02 · **Slug:** `02-api-documentation`  
> **Prerequisitos:** F01 completada o en fase final

---

## 1. Fuentes Canónicas

| Documento | Ubicación |
|-----------|-----------|
| Enunciado oficial de la prueba | `Prueba Backend.pdf` (Criterios de Entrega) |
| Feature F01 | `specs/features/01-products-catalog-api/` |

---

## 2. User Stories

1. **Como** evaluador técnico o desarrollador frontend, **quiero** una documentación OpenAPI / Swagger interactiva, **para** explorar y probar los endpoints de la API en tiempo real.
2. **Como** desarrollador que prueba la API, **quiero** una colección JSON exportable para Postman/Insomnia con ejemplos de petición y respuesta, **para** ejecutar las pruebas de integración de inmediato.

---

## 3. Criterios de Aceptación (EARS)

- **R1 (Event-driven):** WHEN a user navigates to `/docs/api` (o interactúa con Swagger UI), the system shall display the interactive OpenAPI specification detailing all 7 endpoints, request payloads, response schemas, and status codes.
- **R2 (Ubiquitous):** The repository shall include a valid, self-contained `docs/postman_collection.json` containing pre-configured requests for all endpoints with sample payloads and environment variables (`{{base_url}}`).
- **R3 (Ubiquitous):** The repository README shall document how to access the API documentation and import the Postman collection.
