# Cómo usar estas specs (SDD)

## Qué es esto

Esta carpeta define **Spec-Driven Development (SDD)** para la API RESTful de Begento, alineada con las convenciones del proyecto sin duplicar lo canónico.

Cada feature se especifica en:

| Archivo | Propósito |
|---------|-----------|
| `requirements.md` | **QUÉ** — User stories + criterios EARS con identificadores `R1...Rn`. |
| `design.md` | **CÓMO** — Arquitectura, DTOs, Actions invokables, Form Requests, Resources, endpoints, base de datos. |
| `tasks.md` | **ORDEN** — Checklist ejecutable con trazabilidad `_(cubre Rx)_`, tests Pest y Definition of Done (DoD). |

Ubicación: `specs/features/<NN-slug>/`.

## Fuentes de verdad

| Tema | Fuente canónica |
|------|-----------------|
| Requerimientos del cliente | `Prueba Backend.pdf` |
| Convenciones de arquitectura (Actions, DTOs, layout) | `.ai/guidelines/project-conventions.md` / `AGENTS.md` |
| Framework y stack | Laravel 12 (PHP 8.5), Pest, Laravel Sail (MySQL 8.4) |
| Roadmap y dependencias | [`specs/_global/01-product-and-roadmap.md`](01-product-and-roadmap.md) |
| Criterios de calidad | [`specs/_global/02-feature-quality.md`](02-feature-quality.md) |

## Reglas del ciclo SDD

1. **Definición previa:** No se escribe código de producción sin `requirements.md`, `design.md` y `tasks.md` aprobados.
2. **Trazabilidad:** Cada tarea en `tasks.md` debe citar el criterio que satisface `_(cubre Rx)_`.
3. **Control de commits:** No se realizan commits a menos que el usuario lo solicite de manera explícita.
4. **Verificación:** Todo cambio de código debe tener tests Pest asociados que verifiquen el contrato.
