# Ruta de preparación para la certificación Laravel

Esta es la fuente de verdad para avanzar en este repositorio. El orden es obligatorio: primero se completa **Junior (Level 1)** y, después de superar su evaluación, se continúa con **Mid-Level (Level 2)**.

El proyecto de práctica será una aplicación de gestión de proyectos y tareas. Cada fase amplía la misma aplicación para ejercitar los conceptos en contexto, en lugar de resolver ejemplos aislados.

## Cómo estudiar cada bloque

Para cada bloque se sigue este ciclo:

1. Leer la documentación oficial enlazada.
2. Construir la funcionalidad indicada sin copiar una solución completa.
3. Añadir o actualizar pruebas Pest que demuestren el comportamiento.
4. Ejecutar `php artisan test --compact` y corregir todos los fallos.
5. Explicar de memoria las preguntas de comprobación y marcar el bloque como completado.

No se avanza por tiempo transcurrido. Se avanza cuando se cumple la definición de terminado del bloque. Como referencia, se pueden trabajar uno o dos bloques por semana.

## Aplicación práctica

La aplicación tendrá usuarios, proyectos, tareas, etiquetas y actividad. Durante Junior se construirá un CRUD web funcional. Durante Mid-Level se convertirá en una aplicación más robusta, autorizada, asíncrona, testeada y con API.

Reglas de trabajo:

- Crear archivos de Laravel con comandos `php artisan make:* --no-interaction` cuando exista un generador.
- Mantener cada cambio pequeño y cubierto por pruebas.
- Usar factories en pruebas y datos de ejemplo.
- Consultar la documentación de la versión instalada antes de usar una API de Laravel.
- Hacer un commit por bloque completado, con un mensaje que indique el concepto practicado.

# Etapa 1: Junior Laravel Developer

## J1. Entorno, configuración y Artisan

Temas:

- Configuración en `config/` y variables en `.env`.
- Diferencia entre `config()` y `env()`; `env()` debe quedar limitado a archivos de configuración.
- Comandos esenciales: `make:model`, `make:controller`, `make:migration`, `migrate`, `serve`, `tinker` y `route:list`.
- Creación y estructura básica de un comando Artisan propio.
- Visión general de Composer, Sail, Sanctum y Telescope.

Práctica:

- Configurar el nombre de la aplicación mediante `config()`.
- Inspeccionar configuración y rutas desde Artisan.
- Crear un comando que muestre un resumen de proyectos y tareas.

Documentación: [Configuration](https://laravel.com/docs/configuration), [Artisan Console](https://laravel.com/docs/artisan).

Terminado cuando se puede explicar por qué no se debe llamar a `env()` desde el código de la aplicación y crear/ejecutar un comando propio sin ayuda.

## J2. Base de datos, migraciones y Eloquent CRUD

Temas:

- Migraciones, Schema Builder, columnas, índices y claves foráneas.
- Modelos y operaciones de creación, lectura, actualización y eliminación.
- Asignación masiva y protección de atributos.
- Relaciones `hasOne`, `hasMany`, `belongsTo` y `belongsToMany`.
- Carga anticipada con `with()` y scopes locales básicos.

Práctica:

- Crear las tablas y modelos `Project`, `Task` y `Tag` con sus relaciones.
- Añadir factories y seeders útiles.
- Implementar consultas para proyectos con tareas y tareas filtradas por estado.
- Identificar y eliminar al menos un problema N+1.

Documentación: [Migrations](https://laravel.com/docs/migrations), [Eloquent](https://laravel.com/docs/eloquent), [Eloquent Relationships](https://laravel.com/docs/eloquent-relationships).

Terminado cuando las migraciones se pueden reconstruir desde cero y las relaciones funcionan en ambos sentidos.

## J3. Routing y controllers

Temas:

- Rutas web y API, parámetros requeridos y opcionales, nombres y grupos.
- Middleware aplicado a rutas y grupos.
- Controllers de acción única y resource controllers.
- Inyección de dependencias en métodos de controller.

Práctica:

- Crear rutas resource para proyectos y tareas con nombres consistentes.
- Agrupar las rutas protegidas por autenticación.
- Usar generación de URLs por nombre de ruta.
- Comparar una acción invocable con un controller resource y justificar cada uso.

Documentación: [Routing](https://laravel.com/docs/routing), [Controllers](https://laravel.com/docs/controllers).

Terminado cuando `php artisan route:list --except-vendor` refleja una interfaz coherente y se puede explicar cómo viaja una petición desde la ruta hasta la respuesta.

## J4. Blade, formularios y validación

Temas:

- Salida escapada `{{ }}` frente a salida sin escapar `{!! !!}`.
- Condicionales, bucles, `@forelse`, herencia de plantillas y componentes.
- Validación desde el controller y con Form Requests.
- Reglas `required`, `email`, `min`, `max`, `unique` y `exists`.
- Errores de validación y conservación de valores anteriores.

Práctica:

- Construir vistas para listar, crear, editar y ver proyectos y tareas.
- Extraer layout, navegación, mensajes y campos repetidos a componentes Blade.
- Validar la creación y edición con Form Requests.
- Mostrar errores accesibles junto a cada campo.

Documentación: [Blade](https://laravel.com/docs/blade), [Validation](https://laravel.com/docs/validation).

Terminado cuando nunca se confía en datos del navegador y las vistas no ejecutan consultas.

## J5. Autenticación y middleware

Temas:

- Sistema de autenticación, usuario autenticado, middleware `auth` y guards.
- Diferencia entre autenticación y autorización.
- Panorama general de los starter kits y Sanctum.

Práctica:

- Proteger la gestión de proyectos para usuarios autenticados.
- Asociar cada proyecto con su propietario.
- Mostrar navegación distinta a invitados y usuarios autenticados.

Documentación: [Authentication](https://laravel.com/docs/authentication), [Starter Kits](https://laravel.com/docs/starter-kits), [Sanctum](https://laravel.com/docs/sanctum).

Terminado cuando se puede describir la diferencia entre sesión, guard, provider y token, aunque en esta etapa solo se implemente autenticación web.

## J6. Collections, cache y colas

Temas:

- Collections: `map()`, `filter()`, `pluck()`, `reduce()`, `each()`, `first()` y `groupBy()`.
- Diferencia entre arrays, Collections y consultas Eloquent.
- Cache: almacenar, recuperar, eliminar y `remember()`.
- Drivers de cache e invalidación básica.
- Propósito de las colas, creación y despacho de jobs, y queue worker.

Práctica:

- Crear un resumen de tareas agrupadas por estado usando Collections.
- Cachear un resumen del dashboard e invalidarlo cuando cambien tareas.
- Despachar un job de ejemplo al crear un proyecto y procesarlo con un worker.

Documentación: [Collections](https://laravel.com/docs/collections), [Cache](https://laravel.com/docs/cache), [Queues](https://laravel.com/docs/queues).

Terminado cuando se puede decidir si una operación corresponde a SQL, a una Collection, a cache o a una cola.

## J7. Proyecto y evaluación Junior

Entregable:

- CRUD web de proyectos, tareas y etiquetas.
- Autenticación y separación de datos por usuario.
- Validación, relaciones, scopes, vistas Blade y componentes.
- Dashboard cacheado y al menos un job en cola.
- Factories, seeders y pruebas de los recorridos principales.

Evaluación:

- Reconstruir la base de datos y ejecutar toda la aplicación desde cero.
- Resolver una funcionalidad pequeña sin tutorial: por ejemplo, fechas límite y filtro de tareas vencidas.
- Explicar cada tema Junior con un ejemplo del repositorio.
- Obtener todas las pruebas en verde con `php artisan test --compact`.

Solo se pasa a Mid-Level si el CRUD puede modificarse con seguridad y se comprenden los motivos de las decisiones, no solo su sintaxis.

# Etapa 2: Mid-Level Laravel Developer

## M1. Eloquent intermedio y rendimiento

Temas:

- Carga anticipada con restricciones; diferencias entre `with()`, `whereHas()` y `withWhereHas()`.
- Pivot tables, `withPivot()`, `withTimestamps()`, `attach()`, `detach()`, `sync()` y `toggle()`.
- `latestOfMany()`, `oldestOfMany()`, `withCount()`, `withSum()` y `withAvg()`.
- `$touches`, accessors y mutators con `Attribute`, casts y scopes con parámetros.
- Soft deletes y serialización con `$hidden`, `$visible`, `$appends`, `makeHidden()` y `makeVisible()`.
- `firstOrCreate()`, `firstOrNew()`, `updateOrCreate()`, `pluck()`, `chunk()` y `cursor()`.
- Estado de atributos con `isDirty()`, `wasChanged()` y `getOriginal()`.

Práctica:

- Añadir miembros a proyectos mediante una relación many-to-many con rol en el pivot.
- Crear actividad reciente con `latestOfMany()` y métricas con agregados de relaciones.
- Incorporar soft deletes y restauración de proyectos.
- Procesar un conjunto grande de tareas sin cargarlo completo en memoria.

Documentación: [Eloquent Relationships](https://laravel.com/docs/eloquent-relationships), [Mutators and Casting](https://laravel.com/docs/eloquent-mutators), [Serialization](https://laravel.com/docs/eloquent-serialization).

Terminado cuando se puede predecir el número de consultas de una pantalla y justificar la estrategia de carga elegida.

## M2. Routing, requests y middleware intermedios

Temas:

- Route model binding implícito y explícito, claves personalizadas y modelos ausentes.
- Resource routes con `only()`, `except()`, nombres y parámetros personalizados.
- Middleware propio, parámetros, aliases y middleware terminable.
- Request: `only()`, `except()`, `has()`, `hasAny()`, `filled()`, `whenFilled()` y `boolean()`.
- Helpers frecuentes: `collect()`, `now()`, `today()`, `config()`, `cache()`, `session()`, `request()`, `response()`, `redirect()`, `back()`, `view()`, `abort()`, `tap()`, `rescue()`, `optional()`, `blank()` y `filled()`.

Práctica:

- Usar slugs para resolver proyectos y personalizar respuestas de modelos ausentes.
- Crear middleware parametrizado para registrar actividad según el tipo de operación.
- Simplificar rutas resource para exponer únicamente acciones reales.

Documentación: [Route Model Binding](https://laravel.com/docs/routing#route-model-binding), [Middleware](https://laravel.com/docs/middleware), [Requests](https://laravel.com/docs/requests), [Helpers](https://laravel.com/docs/helpers).

Terminado cuando los controllers reciben modelos resueltos, datos validados y una petición ya atravesada por middleware bien delimitado.

## M3. Validación y autorización

Temas:

- Form Requests: `rules()`, `authorize()`, `validated()` y mensajes propios.
- Validación condicional, `sometimes`, `bail`, arrays, `exists`, `unique` y `required_if`.
- Gates, policies, métodos convencionales y personalizados, y auto-discovery.
- Autorización desde controllers, middleware, Blade y el modelo de usuario.

Práctica:

- Crear policies para proyectos y tareas según propietario, miembro y rol.
- Combinar autorización y validación en Form Requests.
- Validar asignaciones y etiquetas enviadas como arrays.
- Probar accesos permitidos y prohibidos.

Documentación: [Authorization](https://laravel.com/docs/authorization), [Form Requests](https://laravel.com/docs/validation#form-request-validation).

Terminado cuando ninguna operación sensible depende solo de ocultar un botón en la interfaz.

## M4. Events, listeners, queues y jobs

Temas:

- Eventos, listeners, despacho y auto-discovery.
- Eventos para desacoplar efectos secundarios.
- Listeners asíncronos con `ShouldQueue`.
- Jobs con demora, `onQueue()`, `$tries`, `$timeout`, `$backoff` y `failed()`.
- Ciclo de vida, fallos y reintentos de jobs.

Práctica:

- Emitir un evento cuando una tarea se complete.
- Añadir listeners independientes para actividad y notificación.
- Encolar el listener costoso y configurar sus reintentos y fallos.
- Evitar duplicar efectos secundarios al reintentar.

Documentación: [Events](https://laravel.com/docs/events), [Queues](https://laravel.com/docs/queues).

Terminado cuando el flujo síncrono sigue siendo correcto aunque el worker esté detenido y los jobs se pueden reintentar de forma segura.

## M5. API Resources y Sanctum

Temas:

- API Resources, transformación de modelos y resource collections.
- Atributos condicionales con `when()` y relaciones con `whenLoaded()`.
- Autenticación ligera de API con Sanctum.
- Evitar N+1 y filtraciones de atributos al serializar.

Práctica:

- Exponer una API versionada para proyectos y tareas.
- Protegerla con Sanctum.
- Diseñar resources que incluyan relaciones y métricas solo cuando hayan sido cargadas.
- Crear pruebas JSON de autenticación, validación y estructura.

Documentación: [API Resources](https://laravel.com/docs/eloquent-resources), [Sanctum](https://laravel.com/docs/sanctum).

Terminado cuando el contrato JSON no depende accidentalmente de la serialización directa del modelo.

## M6. Testing con Pest

Temas:

- Pruebas HTTP con `get()`, `post()`, `actingAs()` y aserciones específicas.
- Pruebas JSON, redirecciones, validación y autorización.
- `RefreshDatabase`, `assertDatabaseHas()` y `assertDatabaseMissing()`.
- Factories: `make()`, `create()`, states, sequences y relaciones.
- Fakes de Queue y Event y sus aserciones.

Práctica:

- Cubrir el recorrido completo de proyectos y tareas.
- Crear datasets para reglas de validación repetidas.
- Probar policies para cada rol.
- Verificar eventos y jobs con fakes; probar por separado el comportamiento real de cada listener/job.

Documentación: [HTTP Tests](https://laravel.com/docs/http-tests), [Database Testing](https://laravel.com/docs/database-testing), [Factories](https://laravel.com/docs/eloquent-factories), [Mocking](https://laravel.com/docs/mocking), [Pest](https://pestphp.com/docs).

Terminado cuando una regresión de comportamiento se detecta con una prueba y no solo al navegar manualmente.

## M7. Integridad, depuración y evaluación Mid-Level

Temas:

- Transacciones con `DB::transaction()` y rollback por excepciones.
- Inspección de consultas mediante `DB::listen()` y `toSql()`.
- Fundamentos de índices, número de consultas y uso de memoria.
- Uso intermedio de Telescope, Debugbar, Pint, Pest y Tinker.

Práctica:

- Hacer transaccional una operación que modifica proyecto, miembros y actividad.
- Medir las consultas del dashboard y corregir N+1 o cargas innecesarias.
- Ejecutar Pint y toda la suite antes de cerrar el proyecto.

Evaluación final:

- Implementar sin tutorial una funcionalidad transversal, por ejemplo invitaciones a proyectos con expiración, autorización, evento, job, API Resource y pruebas.
- Explicar qué ocurre si falla cada paso y qué garantiza la integridad de los datos.
- Revisar una pantalla o endpoint y detectar problemas de autorización, N+1, validación y serialización.
- Ejecutar `vendor/bin/pint --dirty --format agent` y `php artisan test --compact` sin errores.
- Poder relacionar cada punto del temario Mid-Level con código y pruebas del repositorio.

# Seguimiento

Usar esta tabla como marcador de progreso:

| Bloque | Estado | Evidencia |
| --- | --- | --- |
| J1. Entorno, configuración y Artisan | Completado | Configuración mediante `config()`; comando `study:status` con argumento, opción y 3 pruebas Pest |
| J2. Base de datos, migraciones y Eloquent CRUD | Completado | 4 migraciones; relaciones, factories, seeders, scopes y eager loading; 7 pruebas y 23 aserciones |
| J3. Routing y controllers | Pendiente | |
| J4. Blade, formularios y validación | Pendiente | |
| J5. Autenticación y middleware | Pendiente | |
| J6. Collections, cache y colas | Pendiente | |
| J7. Proyecto y evaluación Junior | Pendiente | |
| M1. Eloquent intermedio y rendimiento | Bloqueado hasta completar Junior | |
| M2. Routing, requests y middleware intermedios | Bloqueado hasta completar Junior | |
| M3. Validación y autorización | Bloqueado hasta completar Junior | |
| M4. Events, listeners, queues y jobs | Bloqueado hasta completar Junior | |
| M5. API Resources y Sanctum | Bloqueado hasta completar Junior | |
| M6. Testing con Pest | Bloqueado hasta completar Junior | |
| M7. Integridad, depuración y evaluación Mid-Level | Bloqueado hasta completar Junior | |

En la columna **Evidencia** se añade el commit, archivo o prueba que demuestra el dominio del bloque. Los estados admitidos son `Pendiente`, `En curso`, `Completado` y `Bloqueado hasta completar Junior`.
