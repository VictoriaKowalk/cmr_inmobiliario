# Arquitectura Laravel

## Objetivo

Definir la organizacion tecnica de la aplicacion antes de instalar Laravel y comenzar las migraciones.

La primera version sera una aplicacion monolitica Laravel con:

- Web publica.
- Panel administrador privado.
- Vistas Blade.
- Base de datos MySQL.
- Archivos de imagen en el storage publico.

No se desarrollara inicialmente una API separada ni un frontend independiente.

## Principios

- Mantener separadas la web publica y la administracion.
- Usar nombres en espanol para clases, variables, metodos y archivos propios.
- Mantener los controladores pequenos.
- Colocar las validaciones en solicitudes de formulario.
- Colocar la logica que modifica varias entidades en servicios.
- Usar relaciones Eloquent para acceder a los datos.
- Evitar abstracciones que la primera version no necesita.
- Mantener las convenciones obligatorias de Laravel cuando el framework las requiera.

## Modulos

### Web publica

- Inicio.
- Listado de propiedades.
- Filtros de propiedades.
- Ficha de propiedad.
- Consulta por propiedad.
- Consulta general.
- Solicitud de tasacion.
- Paginas institucionales.

### Panel administrador

- Inicio de sesion.
- Dashboard.
- Propiedades.
- Operaciones comerciales.
- Imagenes.
- Tipos de propiedad.
- Ubicaciones.
- Consultas.
- Tasaciones.

## Estructura propuesta

```text
app/
|-- Enums/
|   |-- EstadoOperacion.php
|   |-- EstadoSeguimiento.php
|   |-- Moneda.php
|   `-- TipoOperacion.php
|-- Http/
|   |-- Controllers/
|   |   |-- Administracion/
|   |   `-- Publico/
|   |-- Middleware/
|   `-- Requests/
|       |-- Administracion/
|       `-- Publico/
|-- Models/
|-- Policies/
|-- Services/
`-- View/
    `-- Components/

database/
|-- factories/
|-- migrations/
`-- seeders/

resources/
|-- views/
|   |-- administracion/
|   |-- publico/
|   |-- componentes/
|   `-- layouts/
|-- css/
`-- js/

routes/
|-- web.php
`-- administracion.php

tests/
|-- Feature/
|   |-- Administracion/
|   `-- Publico/
`-- Unit/
```

## Modelos Eloquent

Los modelos se ubicaran en `app/Models`.

### `Usuario`

Tabla:

- `usuarios`

Responsabilidades:

- Autenticacion del administrador.
- Verificar si el usuario esta activo.
- Registrar el ultimo acceso.

Consideraciones:

- Extiende la clase de autenticacion de Laravel.
- Se configurara para utilizar `contrasenia` en lugar de `password`.
- Se configurara para utilizar `recordar_token` en lugar de `remember_token`.

### `TipoPropiedad`

Tabla:

- `tipos_propiedad`

Relaciones:

- Tiene muchas propiedades.
- Tiene muchas tasaciones.

Metodos descriptivos:

- `propiedades()`
- `tasaciones()`
- `estaActivo()`

### `Ubicacion`

Tabla:

- `ubicaciones`

Relaciones:

- Tiene muchas propiedades.

Metodos descriptivos:

- `propiedades()`
- `generarNombreCompleto()`
- `estaActiva()`

### `Propiedad`

Tabla:

- `propiedades`

Relaciones:

- Pertenece a un tipo de propiedad.
- Pertenece a una ubicacion.
- Tiene muchas operaciones.
- Tiene muchas imagenes.
- Tiene muchas consultas.

Metodos descriptivos:

- `tipoPropiedad()`
- `ubicacion()`
- `operaciones()`
- `imagenes()`
- `consultas()`
- `imagenPortada()`
- `operacionesPublicadas()`
- `estaPublicada()`
- `estaPausada()`

Utilizara eliminacion logica mediante `SoftDeletes`.

### `OperacionPropiedad`

Tabla:

- `operaciones_propiedad`

Relaciones:

- Pertenece a una propiedad.
- Tiene muchas consultas.

Metodos descriptivos:

- `propiedad()`
- `consultas()`
- `estaPublicada()`
- `publicar()`
- `pausar()`
- `marcarComoVendida()`
- `marcarComoAlquilada()`

### `ImagenPropiedad`

Tabla:

- `imagenes_propiedad`

Relaciones:

- Pertenece a una propiedad.

Metodos descriptivos:

- `propiedad()`
- `esPortada()`
- `obtenerUrlPublica()`

### `Consulta`

Tabla:

- `consultas`

Relaciones:

- Puede pertenecer a una propiedad.
- Puede pertenecer a una operacion.

Metodos descriptivos:

- `propiedad()`
- `operacionPropiedad()`
- `marcarComoLeida()`
- `actualizarSeguimiento()`
- `esConsultaGeneral()`

### `Tasacion`

Tabla:

- `tasaciones`

Relaciones:

- Puede pertenecer a un tipo de propiedad.

Metodos descriptivos:

- `tipoPropiedad()`
- `marcarComoLeida()`
- `actualizarSeguimiento()`

## Enumeraciones

Se usaran enumeraciones de PHP para centralizar valores permitidos.

### `TipoOperacion`

- `VENTA`
- `ALQUILER`
- `ALQUILER_TEMPORAL`

Valores almacenados:

- `venta`
- `alquiler`
- `alquiler_temporal`

### `EstadoOperacion`

- `PUBLICADA`
- `PAUSADA`
- `VENDIDA`
- `ALQUILADA`

### `EstadoSeguimiento`

- `NUEVA`
- `EN_SEGUIMIENTO`
- `CONTACTADA`
- `CERRADA`

### `Moneda`

- `USD`
- `ARS`

Las enumeraciones se utilizaran en validaciones, formularios, filtros y conversiones de Eloquent.

## Controladores publicos

Ubicacion:

- `app/Http/Controllers/Publico`

### `InicioController`

Metodos:

- `mostrarInicio()`

Responsabilidades:

- Mostrar propiedades destacadas.
- Mostrar accesos principales de busqueda.

### `PropiedadController`

Metodos:

- `listar()`
- `mostrar(Propiedad $propiedad)`

Responsabilidades:

- Listar solamente propiedades con operaciones publicadas.
- Aplicar filtros.
- Mostrar la ficha y sus operaciones disponibles.

### `ConsultaController`

Metodos:

- `guardarConsultaPropiedad()`
- `guardarConsultaGeneral()`

Responsabilidades:

- Validar y registrar consultas publicas.
- No mostrar informacion administrativa.

### `TasacionController`

Metodos:

- `mostrarFormulario()`
- `guardar()`

Responsabilidades:

- Mostrar el formulario publico.
- Registrar la solicitud con estado `nueva`.

## Controladores administrativos

Ubicacion:

- `app/Http/Controllers/Administracion`

Todos requieren autenticacion.

### `AutenticacionController`

Metodos:

- `mostrarIngreso()`
- `ingresar()`
- `cerrarSesion()`

### `DashboardController`

Metodos:

- `mostrar()`

Responsabilidades:

- Calcular cantidades de propiedades publicadas, pausadas y destacadas.
- Obtener las consultas y tasaciones mas recientes.

### `PropiedadController`

Metodos:

- `listar()`
- `crear()`
- `guardar()`
- `mostrar()`
- `editar()`
- `actualizar()`
- `eliminar()`
- `restaurar()`

Responsabilidades:

- Coordinar el CRUD administrativo.
- Delegar el guardado conjunto al servicio de propiedades.

### `ImagenPropiedadController`

Metodos:

- `guardar()`
- `ordenar()`
- `marcarComoPortada()`
- `eliminar()`

### `TipoPropiedadController`

Metodos:

- `listar()`
- `crear()`
- `guardar()`
- `editar()`
- `actualizar()`
- `cambiarEstado()`

Los tipos relacionados no se eliminaran fisicamente.

### `UbicacionController`

Metodos:

- `listar()`
- `crear()`
- `guardar()`
- `editar()`
- `actualizar()`
- `cambiarEstado()`
- `buscar()`

El metodo `buscar()` alimentara el selector/autocomplete de propiedades.

### `ConsultaController`

Metodos:

- `listar()`
- `mostrar()`
- `actualizarSeguimiento()`

Al mostrar una consulta nueva se registra `leida_en`.

### `TasacionController`

Metodos:

- `listar()`
- `mostrar()`
- `actualizarSeguimiento()`

Al mostrar una tasacion nueva se registra `leida_en`.

## Solicitudes de validacion

Las validaciones se ubicaran en `app/Http/Requests`.

Clases iniciales:

- `GuardarPropiedadRequest`
- `ActualizarPropiedadRequest`
- `GuardarImagenPropiedadRequest`
- `GuardarTipoPropiedadRequest`
- `GuardarUbicacionRequest`
- `ActualizarSeguimientoConsultaRequest`
- `ActualizarSeguimientoTasacionRequest`
- `IngresarAdministradorRequest`
- `GuardarConsultaPublicaRequest`
- `GuardarTasacionPublicaRequest`

Las solicitudes publicas incluiran:

- Validacion de campos.
- Proteccion CSRF.
- Limite de frecuencia.
- Campo trampa invisible para reducir envios automatizados.

## Servicios

Ubicacion:

- `app/Services`

### `ServicioPropiedades`

Responsabilidades:

- Crear una propiedad junto con sus operaciones.
- Actualizar datos y operaciones dentro de una transaccion.
- Evitar operaciones repetidas.
- Generar un `slug` unico.
- Validar que exista al menos una operacion antes de publicar.

Metodos propuestos:

- `crearPropiedad(array $datos): Propiedad`
- `actualizarPropiedad(Propiedad $propiedad, array $datos): Propiedad`
- `eliminarPropiedad(Propiedad $propiedad): void`
- `restaurarPropiedad(Propiedad $propiedad): void`

### `ServicioImagenesPropiedad`

Responsabilidades:

- Guardar archivos.
- Registrar sus rutas.
- Reordenar imagenes.
- Cambiar la portada.
- Eliminar el archivo fisico y su registro.

Metodos propuestos:

- `guardarImagenes(Propiedad $propiedad, array $archivos): void`
- `reordenarImagenes(Propiedad $propiedad, array $orden): void`
- `marcarPortada(ImagenPropiedad $imagen): void`
- `eliminarImagen(ImagenPropiedad $imagen): void`

### `ServicioUbicaciones`

Responsabilidades:

- Normalizar niveles.
- Generar `nombre_completo`.
- Evitar duplicados.

Metodos propuestos:

- `crearUbicacion(array $datos): Ubicacion`
- `actualizarUbicacion(Ubicacion $ubicacion, array $datos): Ubicacion`
- `generarNombreCompleto(array $datos): string`

No se crearan repositorios en la primera version. Los modelos Eloquent y consultas especializadas cubriran el acceso a datos.

## Politicas y seguridad

Clases iniciales:

- `PropiedadPolicy`
- `TipoPropiedadPolicy`
- `UbicacionPolicy`
- `ConsultaPolicy`
- `TasacionPolicy`

En la primera version todas permitiran el acceso a usuarios administradores activos. Esta capa queda preparada para incorporar roles posteriormente.

Middleware:

- `auth`
- `VerificarUsuarioActivo`
- Limite de frecuencia para formularios publicos.

Reglas:

- No existe registro publico de administradores.
- Las rutas administrativas usan el prefijo `/administracion`.
- Las consultas, tasaciones y notas internas nunca se exponen en rutas publicas.
- La carga de imagenes valida extension, tipo MIME y peso maximo.

## Rutas publicas

Archivo:

- `routes/web.php`

Rutas propuestas:

| Metodo | URL | Nombre |
|---|---|---|
| `GET` | `/` | `inicio` |
| `GET` | `/propiedades` | `propiedades.listar` |
| `GET` | `/propiedades/{propiedad:slug}` | `propiedades.mostrar` |
| `POST` | `/propiedades/{propiedad}/consultar` | `consultas.propiedad.guardar` |
| `POST` | `/contacto` | `consultas.general.guardar` |
| `GET` | `/tasacion` | `tasaciones.formulario` |
| `POST` | `/tasacion` | `tasaciones.guardar` |

## Rutas administrativas

Archivo:

- `routes/administracion.php`

Prefijo:

- `/administracion`

Nombres:

- `administracion.*`

Grupos:

- Rutas de ingreso y cierre de sesion.
- Dashboard.
- Propiedades.
- Imagenes.
- Tipos de propiedad.
- Ubicaciones.
- Consultas.
- Tasaciones.

Las rutas se declararan explicitamente para poder utilizar nombres de metodos en espanol.

## Vistas Blade

### Layouts

- `layouts/publico.blade.php`
- `layouts/administracion.blade.php`
- `layouts/autenticacion.blade.php`

### Web publica

```text
resources/views/publico/
|-- inicio.blade.php
|-- propiedades/
|   |-- listar.blade.php
|   `-- mostrar.blade.php
|-- tasaciones/
|   `-- formulario.blade.php
`-- paginas/
```

### Administracion

```text
resources/views/administracion/
|-- autenticacion/
|-- dashboard/
|-- propiedades/
|-- tipos-propiedad/
|-- ubicaciones/
|-- consultas/
`-- tasaciones/
```

Los formularios de crear y editar reutilizaran vistas parciales para evitar duplicacion.

## Componentes Blade

Componentes iniciales:

- Campo de texto.
- Selector.
- Casilla.
- Area de texto.
- Mensaje de error.
- Estado de operacion.
- Estado de seguimiento.
- Tarjeta de propiedad.
- Paginacion.
- Modal de confirmacion.
- Cargador de imagenes.
- Selector de ubicacion con autocomplete.

## Consultas y filtros

Los filtros publicos se implementaran inicialmente con Eloquent y parametros `GET`.

Parametros previstos:

- `tipo_operacion`
- `tipo_propiedad`
- `ubicacion`
- `precio_desde`
- `precio_hasta`
- `ambientes`
- `dormitorios`
- `apto_credito`

La URL conservara los filtros para permitir compartir y paginar resultados.

Las consultas reutilizables se podran implementar como scopes descriptivos en `Propiedad`:

- `publicadas()`
- `destacadas()`
- `porTipoOperacion()`
- `porTipoPropiedad()`
- `porUbicacion()`
- `porRangoPrecio()`

## Seeders

Clases iniciales:

- `UsuarioAdministradorSeeder`
- `TiposPropiedadSeeder`
- `UbicacionesSeeder`

El administrador inicial utilizara credenciales configuradas mediante variables de entorno y debera cambiar su contrasenia.

## Pruebas

Pruebas funcionales prioritarias:

- Un visitante solo ve operaciones publicadas.
- Una propiedad con venta y alquiler aparece una sola vez.
- Los filtros encuentran la propiedad por cualquiera de sus operaciones publicadas.
- Un administrador activo puede ingresar.
- Un usuario inactivo no puede ingresar.
- Una propiedad se guarda con varias operaciones sin duplicarlas.
- Solo una imagen queda marcada como portada.
- Una consulta por propiedad conserva la referencia correcta.
- Las consultas y tasaciones no son accesibles publicamente.
- El dashboard no duplica propiedades con varias operaciones.

## Flujo de guardado de una propiedad

1. El administrador completa los datos generales.
2. Selecciona una ubicacion existente.
3. Activa una o varias operaciones.
4. Completa moneda, precio y estado por operacion.
5. Laravel valida todos los datos.
6. `ServicioPropiedades` inicia una transaccion.
7. Se guarda la propiedad.
8. Se crean o actualizan sus operaciones.
9. Se confirma la transaccion.
10. Las imagenes se cargan y relacionan con la propiedad.

Si falla el guardado de los datos u operaciones, la transaccion se revierte.

## Orden de implementacion

1. Instalar Laravel y configurar MySQL.
2. Configurar autenticacion administrativa.
3. Crear migraciones y modelos.
4. Crear enumeraciones.
5. Crear seeders.
6. Implementar tipos de propiedad.
7. Implementar ubicaciones y autocomplete.
8. Implementar propiedades y operaciones.
9. Implementar imagenes.
10. Implementar consultas y tasaciones.
11. Implementar dashboard.
12. Implementar listado y ficha publica.
13. Implementar filtros publicos.
14. Agregar pruebas y ajustes de seguridad.

## Decisiones cerradas

- Arquitectura monolitica Laravel con Blade.
- MySQL como base de datos.
- Web publica y panel separados por controladores, vistas y rutas.
- Un unico tipo de administrador con acceso completo.
- Sin historial detallado de seguimiento.
- Sin asignacion de contactos.
- Sin API publica en la primera version.
- Sin repositorios en la primera version.
- Servicios solamente para procesos que coordinan varias responsabilidades.
