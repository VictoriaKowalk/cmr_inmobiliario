# Esquema de base de datos MySQL

## Objetivo

Definir los tipos de datos, nulabilidad, valores predeterminados, indices y claves foraneas de la primera version del sistema.

Las migraciones de Laravel se construiran a partir de este esquema.

## Criterios generales

- Motor de base de datos: MySQL.
- Codificacion: `utf8mb4`.
- Los identificadores usan `BIGINT UNSIGNED`.
- Los importes usan `DECIMAL` y no `FLOAT`, para evitar errores de precision.
- Los valores booleanos usan `BOOLEAN`, que MySQL representa como `TINYINT(1)`.
- Los campos `created_at`, `updated_at` y `deleted_at` conservan los nombres estandar de Laravel.
- Los estados y tipos se guardan como `VARCHAR` y se validan desde Laravel.
- Los campos marcados como nulos son opcionales.

## Tabla `usuarios`

| Campo | Tipo MySQL | Nulo | Valor predeterminado | Indice |
|---|---|---:|---|---|
| `id` | `BIGINT UNSIGNED` | No | Autoincremental | Primario |
| `nombre` | `VARCHAR(100)` | No | - | - |
| `apellido` | `VARCHAR(100)` | Si | `NULL` | - |
| `email` | `VARCHAR(255)` | No | - | Unico |
| `contrasenia` | `VARCHAR(255)` | No | - | - |
| `activo` | `BOOLEAN` | No | `TRUE` | Indice |
| `ultimo_acceso_en` | `TIMESTAMP` | Si | `NULL` | - |
| `recordar_token` | `VARCHAR(100)` | Si | `NULL` | - |
| `created_at` | `TIMESTAMP` | Si | `NULL` | - |
| `updated_at` | `TIMESTAMP` | Si | `NULL` | - |

Reglas:

- `email` debe ser unico.
- `contrasenia` guarda el hash generado por Laravel, nunca texto plano.
- Solo los usuarios con `activo = TRUE` pueden ingresar.
- En la primera version no hay roles ni asignacion de contactos.

## Tabla `tipos_propiedad`

| Campo | Tipo MySQL | Nulo | Valor predeterminado | Indice |
|---|---|---:|---|---|
| `id` | `BIGINT UNSIGNED` | No | Autoincremental | Primario |
| `nombre` | `VARCHAR(100)` | No | - | Unico |
| `activo` | `BOOLEAN` | No | `TRUE` | Indice |
| `created_at` | `TIMESTAMP` | Si | `NULL` | - |
| `updated_at` | `TIMESTAMP` | Si | `NULL` | - |

Reglas:

- No se permiten dos tipos con el mismo nombre.
- Un tipo inactivo no aparece al cargar nuevas propiedades.
- No se elimina fisicamente si tiene propiedades o tasaciones relacionadas.

## Tabla `ubicaciones`

| Campo | Tipo MySQL | Nulo | Valor predeterminado | Indice |
|---|---|---:|---|---|
| `id` | `BIGINT UNSIGNED` | No | Autoincremental | Primario |
| `pais` | `VARCHAR(100)` | No | - | Indice |
| `zona` | `VARCHAR(150)` | Si | `NULL` | Indice |
| `localidad` | `VARCHAR(150)` | Si | `NULL` | Indice |
| `categoria_barrio` | `VARCHAR(150)` | Si | `NULL` | - |
| `barrio_principal` | `VARCHAR(150)` | Si | `NULL` | Indice |
| `barrio` | `VARCHAR(150)` | Si | `NULL` | Indice |
| `nombre_completo` | `VARCHAR(700)` | No | - | - |
| `activa` | `BOOLEAN` | No | `TRUE` | Indice |
| `created_at` | `TIMESTAMP` | Si | `NULL` | - |
| `updated_at` | `TIMESTAMP` | Si | `NULL` | - |

Reglas:

- `nombre_completo` se genera desde los niveles informados.
- La aplicacion debe evitar ubicaciones duplicadas comparando su estructura completa.
- Una ubicacion inactiva no aparece en el selector de nuevas propiedades.
- Los campos opcionales permiten representar ubicaciones con menor profundidad.

## Tabla `propiedades`

| Campo | Tipo MySQL | Nulo | Valor predeterminado | Indice |
|---|---|---:|---|---|
| `id` | `BIGINT UNSIGNED` | No | Autoincremental | Primario |
| `tipo_propiedad_id` | `BIGINT UNSIGNED` | No | - | Foraneo |
| `ubicacion_id` | `BIGINT UNSIGNED` | No | - | Foraneo |
| `titulo` | `VARCHAR(180)` | No | - | Indice |
| `slug` | `VARCHAR(200)` | No | - | Unico |
| `codigo_interno` | `VARCHAR(50)` | No | - | Unico |
| `expensas` | `DECIMAL(15,2) UNSIGNED` | Si | `NULL` | - |
| `expensas_moneda` | `VARCHAR(10)` | Si | `NULL` | - |
| `descripcion_corta` | `VARCHAR(500)` | Si | `NULL` | - |
| `descripcion` | `TEXT` | Si | `NULL` | - |
| `direccion` | `VARCHAR(255)` | Si | `NULL` | - |
| `mostrar_direccion` | `BOOLEAN` | No | `FALSE` | - |
| `ambientes` | `SMALLINT UNSIGNED` | Si | `NULL` | Indice |
| `dormitorios` | `SMALLINT UNSIGNED` | Si | `NULL` | Indice |
| `banios` | `SMALLINT UNSIGNED` | Si | `NULL` | - |
| `cocheras` | `SMALLINT UNSIGNED` | Si | `NULL` | - |
| `superficie_total` | `DECIMAL(12,2) UNSIGNED` | Si | `NULL` | - |
| `superficie_cubierta` | `DECIMAL(12,2) UNSIGNED` | Si | `NULL` | - |
| `superficie_descubierta` | `DECIMAL(12,2) UNSIGNED` | Si | `NULL` | - |
| `superficie_terreno` | `DECIMAL(12,2) UNSIGNED` | Si | `NULL` | - |
| `antiguedad` | `SMALLINT UNSIGNED` | Si | `NULL` | - |
| `orientacion` | `VARCHAR(50)` | Si | `NULL` | - |
| `latitud` | `DECIMAL(10,7)` | Si | `NULL` | - |
| `longitud` | `DECIMAL(10,7)` | Si | `NULL` | - |
| `created_at` | `TIMESTAMP` | Si | `NULL` | - |
| `updated_at` | `TIMESTAMP` | Si | `NULL` | - |
| `deleted_at` | `TIMESTAMP` | Si | `NULL` | Indice |

Claves foraneas:

- `tipo_propiedad_id` referencia `tipos_propiedad.id` con eliminacion restringida.
- `ubicacion_id` referencia `ubicaciones.id` con eliminacion restringida.

Reglas:

- `codigo_interno` identifica un inmueble y debe ser unico.
- `slug` identifica su URL publica y debe ser unico.
- Los valores de superficie se expresan inicialmente en metros cuadrados.
- `antiguedad` representa cantidad de anios.
- `latitud` admite valores entre `-90` y `90`.
- `longitud` admite valores entre `-180` y `180`.
- La eliminacion habitual es logica mediante `deleted_at`.

## Tabla `operaciones_propiedad`

| Campo | Tipo MySQL | Nulo | Valor predeterminado | Indice |
|---|---|---:|---|---|
| `id` | `BIGINT UNSIGNED` | No | Autoincremental | Primario |
| `propiedad_id` | `BIGINT UNSIGNED` | No | - | Foraneo |
| `tipo_operacion` | `VARCHAR(30)` | No | - | Indice compuesto |
| `moneda` | `VARCHAR(10)` | Si | `NULL` | - |
| `precio` | `DECIMAL(15,2) UNSIGNED` | Si | `NULL` | Indice |
| `estado` | `VARCHAR(30)` | No | `pausada` | Indice |
| `publicada_en` | `TIMESTAMP` | Si | `NULL` | Indice |
| `created_at` | `TIMESTAMP` | Si | `NULL` | - |
| `updated_at` | `TIMESTAMP` | Si | `NULL` | - |

Valores permitidos para `tipo_operacion`:

- `venta`
- `alquiler`
- `alquiler_temporal`

Valores permitidos para `estado`:

- `publicada`
- `pausada`
- `vendida`
- `alquilada`

Valores iniciales sugeridos para `moneda`:

- `USD`
- `ARS`

Claves e indices:

- `propiedad_id` referencia `propiedades.id`.
- Al eliminar fisicamente una propiedad, sus operaciones se eliminan en cascada.
- Indice unico compuesto: `propiedad_id`, `tipo_operacion`.
- Indice compuesto para filtros publicos: `tipo_operacion`, `estado`.

Reglas:

- `moneda` y `precio` pueden ser nulos para mostrar `Consultar`.
- El estado inicial es `pausada` para evitar publicaciones accidentales.
- `publicada_en` se completa cuando una operacion se publica por primera vez.

## Tabla `caracteristicas`

| Campo | Tipo MySQL | Nulo | Valor predeterminado | Indice |
|---|---|---:|---|---|
| `id` | `BIGINT UNSIGNED` | No | Autoincremental | Primario |
| `nombre` | `VARCHAR(100)` | No | - | Unico compuesto |
| `categoria` | `VARCHAR(30)` | No | - | Indice |
| `activa` | `BOOLEAN` | No | `TRUE` | Indice |
| `created_at` | `TIMESTAMP` | Si | `NULL` | - |
| `updated_at` | `TIMESTAMP` | Si | `NULL` | - |

Valores de `categoria`:

- `servicio`
- `ambiente`
- `cartel`
- `observacion`
- `preferencia_lote`
- `amenity`
- `adicional`

La combinacion `nombre`, `categoria` debe ser unica.

## Tabla `caracteristica_propiedad`

| Campo | Tipo MySQL | Nulo | Valor predeterminado | Indice |
|---|---|---:|---|---|
| `id` | `BIGINT UNSIGNED` | No | Autoincremental | Primario |
| `propiedad_id` | `BIGINT UNSIGNED` | No | - | Foraneo |
| `caracteristica_id` | `BIGINT UNSIGNED` | No | - | Foraneo |
| `created_at` | `TIMESTAMP` | Si | `NULL` | - |
| `updated_at` | `TIMESTAMP` | Si | `NULL` | - |

La combinacion `propiedad_id`, `caracteristica_id` debe ser unica.

## Tabla `imagenes_propiedad`

| Campo | Tipo MySQL | Nulo | Valor predeterminado | Indice |
|---|---|---:|---|---|
| `id` | `BIGINT UNSIGNED` | No | Autoincremental | Primario |
| `propiedad_id` | `BIGINT UNSIGNED` | No | - | Foraneo |
| `ruta` | `VARCHAR(500)` | No | - | - |
| `nombre_original` | `VARCHAR(255)` | Si | `NULL` | - |
| `orden` | `SMALLINT UNSIGNED` | No | `0` | Indice compuesto |
| `portada` | `BOOLEAN` | No | `FALSE` | Indice compuesto |
| `created_at` | `TIMESTAMP` | Si | `NULL` | - |
| `updated_at` | `TIMESTAMP` | Si | `NULL` | - |

Claves e indices:

- `propiedad_id` referencia `propiedades.id`.
- Al eliminar fisicamente una propiedad, sus imagenes se eliminan en cascada.
- Indice compuesto: `propiedad_id`, `orden`.
- Indice compuesto: `propiedad_id`, `portada`.

Reglas:

- La aplicacion garantiza una sola portada por propiedad.
- El archivo fisico se elimina cuando el administrador elimina la imagen.

## Tabla `videos_propiedad`

| Campo | Tipo MySQL | Nulo | Valor predeterminado | Indice |
|---|---|---:|---|---|
| `id` | `BIGINT UNSIGNED` | No | Autoincremental | Primario |
| `propiedad_id` | `BIGINT UNSIGNED` | No | - | Foraneo |
| `tipo` | `VARCHAR(30)` | No | - | Indice compuesto |
| `titulo` | `VARCHAR(255)` | Si | `NULL` | - |
| `ruta` | `VARCHAR(500)` | Si | `NULL` | - |
| `url` | `VARCHAR(500)` | Si | `NULL` | - |
| `youtube_id` | `VARCHAR(50)` | Si | `NULL` | - |
| `orden` | `SMALLINT UNSIGNED` | No | `0` | Indice compuesto |
| `created_at` | `TIMESTAMP` | Si | `NULL` | - |
| `updated_at` | `TIMESTAMP` | Si | `NULL` | - |

Valores permitidos para `tipo`:

- `youtube`

Reglas:

- `propiedad_id` referencia `propiedades.id`.
- Al eliminar fisicamente una propiedad, sus videos se eliminan en cascada.
- Se guarda `url` y `youtube_id`.

## Tabla `consultas`

| Campo | Tipo MySQL | Nulo | Valor predeterminado | Indice |
|---|---|---:|---|---|
| `id` | `BIGINT UNSIGNED` | No | Autoincremental | Primario |
| `propiedad_id` | `BIGINT UNSIGNED` | Si | `NULL` | Foraneo |
| `operacion_propiedad_id` | `BIGINT UNSIGNED` | Si | `NULL` | Foraneo |
| `nombre` | `VARCHAR(150)` | No | - | Indice |
| `email` | `VARCHAR(255)` | Si | `NULL` | Indice |
| `telefono` | `VARCHAR(50)` | Si | `NULL` | Indice |
| `mensaje` | `TEXT` | No | - | - |
| `estado_seguimiento` | `VARCHAR(30)` | No | `nueva` | Indice |
| `notas_internas` | `TEXT` | Si | `NULL` | - |
| `leida_en` | `TIMESTAMP` | Si | `NULL` | - |
| `atendida_en` | `TIMESTAMP` | Si | `NULL` | - |
| `created_at` | `TIMESTAMP` | Si | `NULL` | Indice |
| `updated_at` | `TIMESTAMP` | Si | `NULL` | - |

Valores permitidos para `estado_seguimiento`:

- `nueva`
- `en_seguimiento`
- `contactada`
- `cerrada`

Claves foraneas:

- `propiedad_id` referencia `propiedades.id` con eliminacion restringida.
- `operacion_propiedad_id` referencia `operaciones_propiedad.id` con eliminacion restringida.

Reglas:

- Una consulta general tiene ambas claves foraneas en `NULL`.
- Si se informa `operacion_propiedad_id`, tambien debe informarse `propiedad_id`.
- Laravel debe validar que la operacion pertenezca a la propiedad consultada.
- Debe informarse al menos `email` o `telefono`.
- No se asigna la consulta a un administrador en la primera version.

## Tabla `tasaciones`

| Campo | Tipo MySQL | Nulo | Valor predeterminado | Indice |
|---|---|---:|---|---|
| `id` | `BIGINT UNSIGNED` | No | Autoincremental | Primario |
| `nombre` | `VARCHAR(150)` | No | - | Indice |
| `email` | `VARCHAR(255)` | Si | `NULL` | Indice |
| `telefono` | `VARCHAR(50)` | Si | `NULL` | Indice |
| `tipo_propiedad_id` | `BIGINT UNSIGNED` | Si | `NULL` | Foraneo |
| `ubicacion_texto` | `VARCHAR(500)` | No | - | - |
| `direccion` | `VARCHAR(255)` | Si | `NULL` | - |
| `mensaje` | `TEXT` | Si | `NULL` | - |
| `estado_seguimiento` | `VARCHAR(30)` | No | `nueva` | Indice |
| `notas_internas` | `TEXT` | Si | `NULL` | - |
| `leida_en` | `TIMESTAMP` | Si | `NULL` | - |
| `atendida_en` | `TIMESTAMP` | Si | `NULL` | - |
| `created_at` | `TIMESTAMP` | Si | `NULL` | Indice |
| `updated_at` | `TIMESTAMP` | Si | `NULL` | - |

Claves foraneas:

- `tipo_propiedad_id` referencia `tipos_propiedad.id` con eliminacion restringida.

Reglas:

- `ubicacion_texto` es libre y no se relaciona con `ubicaciones`.
- Debe informarse al menos `email` o `telefono`.
- Utiliza los mismos estados de seguimiento que `consultas`.
- No se asigna la tasacion a un administrador en la primera version.

## Orden de creacion de migraciones

Las tablas deben crearse en este orden:

1. `usuarios`
2. `tipos_propiedad`
3. `ubicaciones`
4. `propiedades`
5. `operaciones_propiedad`
6. `imagenes_propiedad`
7. `videos_propiedad`
8. `consultas`
9. `tasaciones`

Este orden garantiza que cada tabla referenciada exista antes de crear sus claves foraneas.

## Indices principales

- Busqueda administrativa por `propiedades.codigo_interno`.
- URL publica por `propiedades.slug`.
- Filtros por tipo y estado en `operaciones_propiedad`.
- Filtros por ubicacion y tipo de propiedad en `propiedades`.
- Orden de imagenes por propiedad.
- Bandeja comercial por estado y fecha de recepcion.

## Validaciones que corresponden a Laravel

Algunas reglas se controlaran en la aplicacion porque no se expresan de forma sencilla con una clave foranea:

- Una propiedad debe tener al menos una operacion para publicarse.
- Solo una imagen puede ser portada.
- La operacion de una consulta debe pertenecer a la propiedad indicada.
- Debe existir al menos un medio de contacto entre email y telefono.
- Los estados deben ser compatibles con el tipo de operacion.
- Las superficies, precios y cantidades no pueden ser negativos.
