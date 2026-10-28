# Criterios de Calidad de Features (EARS y Estándares)

## Sintaxis EARS (Easy Approach to Requirements Syntax)

Todos los criterios de aceptación en `requirements.md` deben formularse usando uno de los siguientes patrones:

| Tipo | Patrón | Cuándo usarlo |
|------|--------|---------------|
| **Ubiquitous (Siempre activo)** | The system shall `<action>` | Comportamiento continuo o invariante general. |
| **Event-driven (Evento)** | WHEN `<trigger>`, the system shall `<action>` | Peticiones HTTP, invocación de acciones. |
| **State-driven (Estado)** | WHILE `<state>`, the system shall `<action>` | Comportamiento condicional al estado del recurso. |
| **Optional feature** | WHERE `<feature is included>`, the system shall `<action>` | Funcionalidades configurables. |
| **Unwanted behavior (Error)** | IF `<error / invalid condition>`, THEN the system shall `<action>` | Validaciones, 404, 422, excepciones de dominio. |

---

## Barra de Calidad para Begento API

1. **Tipado estricto:** `declare(strict_types=1);` en todos los archivos PHP nuevos.
2. **Form Requests perimetrales:** Ningún Controller o Action procesa `$request->all()` directo. Se valida en FormRequest y se mapea a un DTO `readonly`.
3. **Casos de uso desacoplados (Actions):** Los controllers únicamente coordinan:
   - Inyección de dependencias.
   - Llamada a `$dto = $request->toDTO()`.
   - Ejecución de `$action($dto)`.
   - Retorno de `$resource` con código HTTP correspondiente.
4. **Respuestas JSON estandarizadas:** Todo payload de respuesta pasa por un `JsonResource`.
5. **Cobertura con Pest:**
   - 200/201 en camino feliz.
   - 422 Unprocessable Entity en fallos de validación con detalle de campos.
   - 404 Not Found cuando el ID de producto o divisa no exista.
   - Integridad referencial al eliminar productos (los precios asociados deben eliminarse o manejarse limpiamente).
6. **Formato Pint:** `vendor/bin/pint --dirty --format agent` debe pasar en limpio.
