# F02 — Documentación de la API · Diseño Técnico

> **ID:** F02 · **Slug:** `02-api-documentation`  
> **Requirements:** [`requirements.md`](requirements.md)  
> **Stack:** OpenAPI 3.1, Postman Collection v2.1.0, Scramble / L5-Swagger o especificación estática OpenAPI

---

## 1. Enfoque Técnico

Para brindar la máxima claridad y facilidad al evaluador:
1. **Colección Postman (`docs/postman_collection.json`):**
   - Formato Postman Collection v2.1.0.
   - Variable de entorno `baseUrl`: `http://localhost` (o `http://localhost:8000`).
   - Colección organizada en carpetas `Products` y `Product Prices`.
   - Ejemplos de payload válidos e inválidos (para validar respuestas 200, 201, 404 y 422).
2. **Especificación OpenAPI 3.1 (`docs/openapi.yaml` / Swagger UI):**
   - Definición formal de schemas (`Product`, `ProductPrice`, `Currency`, `ValidationError`).
   - Parámetros de ruta (`{id}`).
   - Documentación de status codes (200, 201, 204, 404, 422).
