# Modelo de datos - Web inmobiliaria

## Objetivo

Definir la estructura inicial de la base de datos MySQL, sus entidades, relaciones y reglas de integridad.

Este documento complementa al documento funcional y servira como base para crear las migraciones, modelos y relaciones de Laravel.

## Tablas principales

- `usuarios`
- `tipos_propiedad`
- `ubicaciones`
- `propiedades`
- `operaciones_propiedad`
- `caracteristicas`
- `caracteristica_propiedad`
- `imagenes_propiedad`
- `videos_propiedad`
- `consultas`
- `tasaciones`

## Diagrama conceptual

```text
tipos_propiedad 1 ────── N propiedades
ubicaciones     1 ────── N propiedades
propiedades     1 ────── N operaciones_propiedad
propiedades     N ────── N caracteristicas
propiedades     1 ────── N imagenes_propiedad
propiedades     1 ────── N consultas
operaciones_propiedad 1 ────── N consultas
tipos_propiedad 1 ────── N tasaciones

usuarios
  └── Acceso independiente al panel administrador
```

La relacion entre `propiedades` y `consultas` es opcional del lado de la consulta, porque tambien pueden recibirse consultas generales.

## Relacion entre propiedades y caracteristicas

Una propiedad puede tener muchos servicios, ambientes y adicionales.

Tambien puede tener observaciones, preferencias de lote y amenities. Para la categoria `cartel`, la aplicacion permitira una sola opcion activa por propiedad.

Una caracteristica puede estar asociada con muchas propiedades.

La relacion se implementa mediante:

- `caracteristicas`
- `caracteristica_propiedad`

La tabla intermedia contiene:

- `propiedad_id`
- `caracteristica_id`

No se permite repetir la misma caracteristica dentro de una propiedad.

## Relacion entre propiedades y operaciones

Una propiedad representa el inmueble fisico y contiene sus datos, ubicacion, caracteristicas e imagenes.

Una operacion representa la forma en que ese inmueble se ofrece comercialmente.

Relacion:

- `propiedades.id`
- `operaciones_propiedad.propiedad_id`

Cardinalidad:

```text
propiedades 1 ────── N operaciones_propiedad
```

Reglas propuestas:

- Una propiedad puede tener operaciones de venta, alquiler y alquiler temporal simultaneamente.
- Cada operacion tiene su propio precio, moneda, estado y fecha de publicacion.
- No se puede repetir un mismo tipo de operacion para una propiedad.
- La combinacion `propiedad_id` y `tipo_operacion` debe tener un indice unico.
- Una propiedad necesita al menos una operacion para aparecer en la web publica.
- Al eliminar definitivamente una propiedad se eliminan sus operaciones.
- La eliminacion habitual de la propiedad sera logica.

Campos que pertenecen a `operaciones_propiedad` y no a `propiedades`:

- `tipo_operacion`
- `moneda`
- `precio`
- `estado`
- `publicada_en`

## Relacion entre tipos de propiedad y propiedades

Una propiedad pertenece a un tipo de propiedad.

Un tipo de propiedad puede estar asociado con muchas propiedades.

Relacion:

- `tipos_propiedad.id`
- `propiedades.tipo_propiedad_id`

Cardinalidad:

```text
tipos_propiedad 1 ────── N propiedades
```

Reglas propuestas:

- `tipo_propiedad_id` es obligatorio en una propiedad.
- Un tipo de propiedad no se puede eliminar si tiene propiedades asociadas.
- Un tipo de propiedad puede desactivarse para impedir su uso en nuevas propiedades.

Para permitir la desactivacion se agregara a `tipos_propiedad`:

- `activo`

## Relacion entre ubicaciones y propiedades

Una propiedad pertenece a una ubicacion.

Una ubicacion puede estar asociada con muchas propiedades.

Relacion:

- `ubicaciones.id`
- `propiedades.ubicacion_id`

Cardinalidad:

```text
ubicaciones 1 ────── N propiedades
```

Reglas propuestas:

- `ubicacion_id` es obligatorio en una propiedad.
- Una ubicacion no se puede eliminar si tiene propiedades asociadas.
- Una ubicacion puede desactivarse para que no aparezca en el selector de nuevas propiedades.
- Una propiedad existente conserva su ubicacion aunque esta sea desactivada.

## Relacion entre propiedades e imagenes

Una propiedad puede tener muchas imagenes.

Cada imagen pertenece a una sola propiedad.

Relacion:

- `propiedades.id`
- `imagenes_propiedad.propiedad_id`

Cardinalidad:

```text
propiedades 1 ────── N imagenes_propiedad
```

Reglas propuestas:

- `propiedad_id` es obligatorio en cada imagen.
- Una propiedad puede crearse inicialmente sin imagenes.
- Solo una imagen por propiedad debe estar marcada como portada.
- Al eliminar definitivamente una propiedad se eliminan sus registros de imagenes y sus archivos fisicos.
- La eliminacion habitual de propiedades sera logica mediante `deleted_at`.

## Relacion entre propiedades y videos

Una propiedad puede tener muchos videos.

Cada video pertenece a una sola propiedad.

Relacion:

- `propiedades.id`
- `videos_propiedad.propiedad_id`

Reglas propuestas:

- `propiedad_id` es obligatorio en cada video.
- Una propiedad puede crearse inicialmente sin videos.
- Los videos se cargan como enlaces de YouTube.
- Al eliminar definitivamente una propiedad se eliminan sus registros de videos.

## Relacion entre propiedades y consultas

Una propiedad puede recibir muchas consultas.

Una consulta puede pertenecer a una propiedad o ser una consulta general.

Tambien puede indicar la operacion concreta por la que se intereso el visitante.

Relacion:

- `propiedades.id`
- `consultas.propiedad_id`
- `operaciones_propiedad.id`
- `consultas.operacion_propiedad_id`

Cardinalidad:

```text
propiedades 1 ────── 0..N consultas
consultas   N ────── 0..1 propiedades
```

Reglas propuestas:

- `propiedad_id` puede ser nulo.
- `operacion_propiedad_id` puede ser nulo.
- Si tiene valor, la consulta proviene de la ficha de esa propiedad.
- Si el visitante selecciona una operacion concreta, se guarda su referencia.
- La aplicacion debe validar que la operacion seleccionada pertenezca a la propiedad consultada.
- Si es nulo, se considera una consulta general.
- La consulta debe conservarse si la propiedad se pausa, vende, alquila o elimina de forma logica.
- Una propiedad con consultas asociadas no debe eliminarse fisicamente.

## Relacion entre tipos de propiedad y tasaciones

Una solicitud de tasacion puede indicar un tipo de propiedad.

Un tipo de propiedad puede estar asociado con muchas solicitudes de tasacion.

Relacion:

- `tipos_propiedad.id`
- `tasaciones.tipo_propiedad_id`

Cardinalidad:

```text
tipos_propiedad 1 ────── 0..N tasaciones
tasaciones      N ────── 0..1 tipos_propiedad
```

Reglas propuestas:

- `tipo_propiedad_id` puede ser nulo para no bloquear el envio del formulario.
- Un tipo de propiedad relacionado con tasaciones historicas no debe eliminarse fisicamente.
- Si deja de utilizarse, debe marcarse como inactivo.

## Ubicacion de tasaciones

Las solicitudes de tasacion utilizaran el campo libre:

- `ubicacion_texto`

No tendran una relacion obligatoria con la tabla `ubicaciones`.

Esta decision permite que un visitante solicite una tasacion aunque la zona, barrio o desarrollo todavia no exista en las ubicaciones precargadas del sistema.

## Usuarios administradores

En la primera version, los usuarios no necesitan relacionarse con otras tablas para determinar permisos.

Todos los usuarios activos tendran acceso administrativo completo.

Reglas propuestas:

- El correo electronico debe ser unico.
- No existira registro publico.
- Un usuario inactivo no puede iniciar sesion.
- La contrasenia siempre se guarda mediante el sistema de hashing de Laravel.
- Las consultas y tasaciones no se asignaran a un administrador en la primera version.

Como ampliacion futura se podran registrar responsables y auditoria mediante campos como:

- `creada_por_usuario_id`
- `actualizada_por_usuario_id`
- `usuario_asignado_id`

Estos campos no se incluiran en la primera version para mantener el modelo simple.

## Resumen de claves foraneas

| Tabla | Campo | Referencia | Admite nulo | Al eliminar |
|---|---|---|---|---|
| `propiedades` | `tipo_propiedad_id` | `tipos_propiedad.id` | No | Restringir |
| `propiedades` | `ubicacion_id` | `ubicaciones.id` | No | Restringir |
| `operaciones_propiedad` | `propiedad_id` | `propiedades.id` | No | Cascada al eliminar definitivamente |
| `imagenes_propiedad` | `propiedad_id` | `propiedades.id` | No | Cascada al eliminar definitivamente |
| `consultas` | `propiedad_id` | `propiedades.id` | Si | Restringir |
| `consultas` | `operacion_propiedad_id` | `operaciones_propiedad.id` | Si | Restringir |
| `tasaciones` | `tipo_propiedad_id` | `tipos_propiedad.id` | Si | Restringir |

## Criterio de eliminacion

Se aplicaran tres comportamientos diferentes:

- **Eliminacion logica:** para propiedades, mediante `deleted_at`.
- **Desactivacion:** para tipos de propiedad, ubicaciones y usuarios.
- **Eliminacion fisica controlada:** para imagenes que el administrador quite de una propiedad.

Las consultas y tasaciones se conservaran como historial comercial. No se eliminaran automaticamente por cambios en propiedades, tipos o ubicaciones.

## Seguimiento comercial inicial

En la primera version no se creara una tabla de historial de seguimiento.

Las tablas `consultas` y `tasaciones` guardaran directamente:

- `estado_seguimiento`
- `notas_internas`
- `leida_en`
- `atendida_en`

Estos campos representan la situacion actual del contacto. Si en el futuro se necesita registrar cada cambio, fecha, nota y usuario responsable, se agregara una tabla relacionada de historial.

## Definicion tecnica

Los tipos MySQL, campos opcionales, valores predeterminados e indices se encuentran definidos en `esquema-base-datos.md`.

Las decisiones principales del modelo de datos quedan cerradas para preparar las migraciones iniciales.
