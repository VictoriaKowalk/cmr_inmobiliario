# Documento funcional - Web inmobiliaria con panel administrador

## Objetivo

Crear una web inmobiliaria administrable, sin conexion a CRM externo, donde el cliente pueda cargar, editar, pausar, vender/alquilar y destacar propiedades desde un panel privado.

El sistema debe guardar las propiedades en una base de datos propia y mostrarlas luego en la web publica.

## Usuarios principales

### Visitante web

Puede navegar propiedades, ver detalles, enviar consultas y solicitar tasaciones.

### Administrador

Puede ingresar al panel privado y administrar el contenido principal de la inmobiliaria.

## Modulos principales

## Web publica

- Home.
- Propiedades destacadas.
- Listado de propiedades.
- Filtros de busqueda.
- Detalle de propiedad.
- Formulario de consulta por propiedad.
- Formulario de contacto general.
- Formulario de tasacion.
- Paginas institucionales.

## Panel administrador

- Login.
- Dashboard inicial.
- Administracion de propiedades.
- Administracion de imagenes de propiedades.
- Administracion de tipos de propiedad.
- Administracion de ubicaciones/barrios.
- Bandeja privada de consultas y tasaciones recibidas.
- Seguimiento de consultas y tasaciones.

## Dashboard inicial

El panel administrador debe mostrar un resumen inicial con:

- Cantidad de propiedades publicadas.
- Cantidad de propiedades pausadas.
- Cantidad de propiedades destacadas.
- Ultimas consultas recibidas.
- Ultimas tasaciones recibidas.

Para los indicadores:

- Una propiedad se considera publicada si tiene al menos una operacion publicada.
- Una propiedad se considera pausada si no tiene operaciones publicadas y tiene al menos una operacion pausada.
- Una misma propiedad se cuenta una sola vez aunque tenga varias operaciones.

Las consultas y tasaciones mostradas en el dashboard son informacion privada y solo pueden ser vistas por usuarios administradores autenticados.

Cada registro del dashboard debe mostrar como minimo:

- Tipo de contacto: consulta por propiedad, consulta general o tasacion.
- Nombre del interesado.
- Telefono y correo electronico.
- Fecha y hora de recepcion.
- Estado de seguimiento.
- Referencia de la propiedad, cuando corresponda.

Al seleccionar un registro, el administrador accede al detalle completo del mensaje.

## Gestion de propiedades

El administrador debe poder:

- Crear propiedades.
- Editar propiedades.
- Pausar propiedades.
- Eliminar propiedades.
- Marcar propiedades como destacadas.
- Agregar una o varias operaciones comerciales.
- Cambiar el estado, precio y moneda de cada operacion.
- Cargar datos principales.
- Cargar imagenes.

## Tipos de operacion

El sistema debe contemplar los siguientes tipos de operacion:

- Venta.
- Alquiler.
- Alquiler temporal.

Una propiedad puede tener una o varias operaciones simultaneas.

Ejemplo:

- Venta en dolares.
- Alquiler en dolares.

Ambas operaciones pertenecen a la misma propiedad, comparten codigo interno, datos, ubicacion, caracteristicas e imagenes.

## Estados de operacion

Cada operacion de una propiedad puede tener uno de los siguientes estados:

- Publicada.
- Pausada.
- Vendida.
- Alquilada.

El estado `vendida` se utiliza para operaciones de venta y el estado `alquilada` para operaciones de alquiler o alquiler temporal.

## Tipos de propiedad iniciales

Los tipos de propiedad iniciales se tomaran como referencia desde Tokko Broker, usando principalmente el campo `name`.

- Terreno.
- Departamento.
- Casa.
- Quinta.
- Oficina.
- Amarra.
- Local.
- Edificio Comercial.
- Campo.
- Cochera.
- Hotel.
- Nave Industrial.
- PH.
- Deposito.
- Fondo de Comercio.
- Baulera.
- Bodega.
- Finca.
- Chacra.
- Cama nautica.
- Isla.
- Terraza.
- Galpon.
- Villa.
- Terreno comercial.
- Terreno industrial.
- Hacienda.
- Haras.
- Consultorio.
- Monoambiente.
- Terreno en condominio.

## Entidades principales

- Usuario administrador.
- Propiedad.
- Operacion de propiedad.
- Imagen de propiedad.
- Video de propiedad.
- Caracteristica de propiedad.
- Tipo de propiedad.
- Ubicacion/barrio.
- Consulta.
- Tasacion.

## Stack propuesto

- Laravel.
- PHP.
- MySQL.
- Blade para vistas publicas y panel administrador.
- Autenticacion con Laravel Breeze o sistema equivalente.
- Storage publico para imagenes.
- Migraciones, seeders y factories desde el inicio.

## Convenciones de desarrollo

El proyecto se desarrollara usando nombres en espanol para tablas, campos, variables, metodos y funciones.

Los nombres deben ser descriptivos y consistentes.

Ejemplos:

- `tipo_operacion`
- `venta`
- `alquiler`
- `alquiler_temporal`
- `tipos_propiedad`
- `nombre`

## Modelo inicial de tipos de operacion

Campo sugerido en `operaciones_propiedad`:

- `tipo_operacion`

Valores posibles:

- `venta`
- `alquiler`
- `alquiler_temporal`

## Modelo inicial de tipos de propiedad

Tabla sugerida:

- `tipos_propiedad`

Campos iniciales:

- `id`
- `nombre`

Nombre oficial de la tabla:

- `tipos_propiedad`

## Modelo inicial de propiedades

Tabla sugerida:

- `propiedades`

Campos iniciales:

- `id`
- `tipo_propiedad_id`
- `ubicacion_id`
- `titulo`
- `slug`
- `codigo_interno`
- `expensas`
- `expensas_moneda`
- `descripcion_corta`
- `descripcion`
- `direccion`
- `mostrar_direccion`
- `ambientes`
- `dormitorios`
- `banios`
- `cocheras`
- `superficie_total`
- `superficie_cubierta`
- `superficie_descubierta`
- `superficie_terreno`
- `antiguedad`
- `orientacion`
- `latitud`
- `longitud`
- `created_at`
- `updated_at`
- `deleted_at`

Nota: los campos propios del proyecto se nombran en espanol. Los timestamps estandar de Laravel se mantienen como `created_at`, `updated_at` y `deleted_at` para respetar convenciones del framework.

El campo `codigo_interno` identifica la propiedad para el administrador y debe ser unico. No cambia aunque la propiedad tenga varias operaciones.

## Modelo inicial de operaciones de propiedad

Tabla sugerida:

- `operaciones_propiedad`

Campos iniciales:

- `id`
- `propiedad_id`
- `tipo_operacion`
- `moneda`
- `precio`
- `estado`
- `publicada_en`
- `created_at`
- `updated_at`

Reglas:

- Una propiedad debe tener al menos una operacion para poder publicarse.
- Puede tener simultaneamente venta, alquiler y alquiler temporal.
- No se puede repetir el mismo `tipo_operacion` dentro de una propiedad.
- Cada operacion tiene su propio precio, moneda y estado.
- Si `precio` es nulo, en la web publica se muestra `Consultar`.
- La propiedad aparece una sola vez en los listados, aunque tenga varias operaciones.
- La ficha publica muestra todas sus operaciones disponibles.
- Los filtros por operacion buscan dentro de esta tabla.

En el formulario administrativo, el usuario seleccionara las operaciones mediante casillas:

- Venta.
- Alquiler.
- Alquiler temporal.

Al activar una operacion se mostraran sus campos de moneda, precio y estado.

## Modelo inicial de ubicaciones

La ubicacion de una propiedad debe poder representar una jerarquia como:

`Argentina | G.B.A. Zona Norte | Tigre | Countries/B.Cerrado (Tigre) | Nordelta | El Yacht`

Estructura conceptual:

- Pais.
- Zona o region.
- Localidad.
- Categoria o agrupador de barrio.
- Barrio cerrado / desarrollo / barrio principal.
- Barrio / sub-barrio / sector.

Tabla sugerida:

- `ubicaciones`

Campos iniciales:

- `id`
- `pais`
- `zona`
- `localidad`
- `categoria_barrio`
- `barrio_principal`
- `barrio`
- `nombre_completo`
- `activa`
- `created_at`
- `updated_at`

Ejemplo:

- `pais`: Argentina
- `zona`: G.B.A. Zona Norte
- `localidad`: Tigre
- `categoria_barrio`: Countries/B.Cerrado (Tigre)
- `barrio_principal`: Nordelta
- `barrio`: El Yacht
- `nombre_completo`: Argentina | G.B.A. Zona Norte | Tigre | Countries/B.Cerrado (Tigre) | Nordelta | El Yacht

La propiedad se relacionara con una ubicacion mediante:

- `ubicacion_id`

## Criterio definido para carga de ubicaciones

Para evitar errores de escritura, duplicados y variaciones manuales, las ubicaciones no deberian cargarse como texto libre en cada propiedad.

El panel administrador usara ubicaciones precargadas y seleccionables.

Funcionamiento definido:

- El sistema tendra ubicaciones tipicas precargadas al iniciar el proyecto.
- El administrador selecciona una ubicacion existente desde un buscador/autocomplete.
- La ubicacion se muestra como ruta completa.
- Si la ubicacion no existe, se podra crear desde una pantalla especifica de administracion.
- La creacion de ubicaciones debe respetar la estructura definida: pais, zona, localidad, categoria_barrio, barrio_principal y barrio.
- Las propiedades solo guardan `ubicacion_id`.

Esto permite mantener consistencia en filtros, listados, SEO y busquedas.

## Modelo inicial de imagenes de propiedad

Las imagenes seran subidas por el administrador desde su computadora, desde el formulario de creacion o edicion de una propiedad.

Laravel guardara los archivos en el storage publico del proyecto.

Carpeta sugerida:

- `storage/app/public/propiedades/{id_propiedad}/`

Ejemplo:

- `storage/app/public/propiedades/123/casa-nordelta-01.jpg`
- `storage/app/public/propiedades/123/casa-nordelta-02.jpg`

En la base de datos no se guarda la imagen completa, solo la ruta del archivo.

Tabla sugerida:

- `imagenes_propiedad`

Campos iniciales:

- `id`
- `propiedad_id`
- `ruta`
- `nombre_original`
- `orden`
- `portada`
- `created_at`
- `updated_at`

Ejemplo de ruta guardada en base de datos:

- `propiedades/123/casa-nordelta-01.jpg`

Ruta publica generada por Laravel:

- `/storage/propiedades/123/casa-nordelta-01.jpg`

Reglas:

- Una propiedad puede tener muchas imagenes.
- Una imagen puede marcarse como portada.
- Si no hay portada marcada, se usara la primera imagen segun el orden.
- Las imagenes deben poder ordenarse desde el panel administrador.
- Las imagenes deben poder eliminarse desde el panel administrador.

## Modelo inicial de videos de propiedad

Los videos se cargaran mediante enlaces de YouTube.

Tabla sugerida:

- `videos_propiedad`

Campos iniciales:

- `id`
- `propiedad_id`
- `tipo`
- `titulo`
- `url`
- `youtube_id`
- `orden`
- `created_at`
- `updated_at`

Reglas:

- Una propiedad puede tener muchos videos.
- `tipo` sera `youtube`.
- Se guarda la URL original y el identificador del video para embeberlo.
- Los videos deben poder eliminarse desde el panel administrador.

## Servicios y ambientes de propiedad

Los servicios y ambientes se cargaran mediante opciones fijas con casillas de seleccion. No se utilizaran campos de texto libre para esta informacion.

Servicios iniciales:

- Agua Corriente.
- Cloaca.
- Gas Natural.
- Internet.
- Electricidad.
- Pavimento.
- Telefono.
- Cable.

Ambientes iniciales:

- Altillo.
- Balcon.
- Baulera.
- Cocina.
- Comedor diario.
- Dependencia.
- Oficina.
- Hall.
- Jardin.
- Lavadero.
- Living comedor.
- Patio.
- Sotano.
- Terraza.
- Toilette.
- Vestidor.

El catalogo se almacenara en `caracteristicas` y se relacionara con las propiedades mediante `caracteristica_propiedad`.

Categorias previstas:

- `servicio`
- `ambiente`
- `cartel`
- `observacion`
- `preferencia_lote`
- `amenity`
- `adicional`

Cartel utiliza una seleccion excluyente:

- Tiene cartel.
- Sin cartel.

Observaciones, preferencias de lote y amenities utilizan casillas de seleccion multiple.

Observaciones iniciales:

- Oportunidad.
- Acepta Lote.
- Acepta Permuta.
- Apto Credito.
- Venta Con Renta.
- Acepta mascotas.
- Apto profesional.
- Propiedad destacada.

`Propiedad destacada` tambien se utilizara para el indicador del dashboard y para la accion rapida de destacar o quitar destaque desde el listado administrativo.

## Gestion privada de consultas y tasaciones

Las consultas y solicitudes de tasacion no se mostraran en la web publica ni en la ficha publica de una propiedad.

Solamente los usuarios administradores autenticados podran verlas desde:

- El resumen del dashboard.
- Una bandeja general de contactos.
- La pantalla de detalle de cada consulta o tasacion.

La bandeja debe funcionar como una lista de mensajes recibidos, ordenada inicialmente desde el mas reciente al mas antiguo.

Debe permitir:

- Buscar por nombre, correo electronico o telefono.
- Filtrar por tipo de contacto.
- Filtrar por estado de seguimiento.
- Identificar visualmente los mensajes nuevos.
- Abrir el detalle completo.
- Agregar notas internas.
- Cambiar el estado de seguimiento.

## Modelo inicial de consultas

Tabla sugerida:

- `consultas`

Campos iniciales:

- `id`
- `propiedad_id`
- `operacion_propiedad_id`
- `nombre`
- `email`
- `telefono`
- `mensaje`
- `estado_seguimiento`
- `notas_internas`
- `leida_en`
- `atendida_en`
- `created_at`
- `updated_at`

Reglas:

- `propiedad_id` puede ser nulo para permitir consultas generales.
- `operacion_propiedad_id` puede ser nulo si el interesado no consulta por una operacion concreta.
- Si la consulta proviene de la ficha de una propiedad, debe guardar la relacion con esa propiedad.
- Si el interesado elige venta, alquiler o alquiler temporal, debe guardar la operacion seleccionada.
- La operacion seleccionada debe pertenecer a la propiedad consultada.
- En el panel se debe mostrar el `codigo_interno` o el `id`, junto con el `titulo` de la propiedad.
- La consulta debe seguir existiendo aunque una operacion sea pausada, vendida o alquilada, o la propiedad sea eliminada logicamente.
- Las notas internas solo son visibles para los administradores.

Ejemplo de identificacion en el panel:

`Consulta por propiedad | COD-154 | Casa en Nordelta`

## Modelo inicial de tasaciones

Tabla sugerida:

- `tasaciones`

Campos iniciales:

- `id`
- `nombre`
- `email`
- `telefono`
- `tipo_propiedad_id`
- `ubicacion_texto`
- `direccion`
- `mensaje`
- `estado_seguimiento`
- `notas_internas`
- `leida_en`
- `atendida_en`
- `created_at`
- `updated_at`

Reglas:

- En el dashboard y en la bandeja debe identificarse claramente como una solicitud de tasacion.
- Debe mostrar los datos de contacto del posible cliente.
- Debe mostrar el tipo de propiedad, ubicacion, direccion y mensaje cuando hayan sido informados.
- La ubicacion se cargara mediante `ubicacion_texto`, como un campo libre del formulario publico.
- Las tasaciones no estaran obligadas a seleccionar una ubicacion precargada.
- Las notas internas solo son visibles para los administradores.

Ejemplo de identificacion en el panel:

`Tasacion | Juan Perez | Casa en Tigre`

## Estados de seguimiento

Las consultas y tasaciones compartiran los mismos estados:

- `nueva`
- `en_seguimiento`
- `contactada`
- `cerrada`

Funcionamiento:

- Todo contacto ingresa con estado `nueva`.
- Al abrir el detalle se registra la fecha y hora en `leida_en`.
- El administrador puede cambiar manualmente el estado.
- Cuando se realiza el primer contacto se puede registrar la fecha y hora en `atendida_en`.
- El administrador puede escribir observaciones en `notas_internas`.

En una primera version, el seguimiento sera compartido por todos los administradores. Si mas adelante se necesita asignar contactos a una persona concreta, se podra agregar `usuario_asignado_id`.

Las consultas y tasaciones no se asignaran a un administrador en la primera version.

En la primera version no se guardara un historial individual de cada cambio de estado. Se conservaran solamente:

- El estado de seguimiento actual.
- Las notas internas acumuladas.
- La fecha y hora de lectura.
- La fecha y hora de primera atencion.

Si mas adelante se necesita una trazabilidad completa, se podra agregar una tabla de historial de seguimientos.

## Usuarios administradores y permisos

En la primera version del sistema existira un unico tipo de usuario administrador.

Todos los administradores tendran acceso completo al panel y podran:

- Ver el dashboard.
- Crear, editar, pausar y eliminar propiedades.
- Administrar imagenes.
- Administrar tipos de propiedad.
- Administrar ubicaciones.
- Ver y gestionar consultas.
- Ver y gestionar tasaciones.
- Agregar notas internas y modificar estados de seguimiento.

No se implementaran inicialmente roles ni permisos diferenciados.

Tabla sugerida:

- `usuarios`

Campos iniciales:

- `id`
- `nombre`
- `apellido`
- `email`
- `contrasenia`
- `activo`
- `ultimo_acceso_en`
- `recordar_token`
- `created_at`
- `updated_at`

Reglas:

- El correo electronico debe ser unico.
- Solo los usuarios activos pueden ingresar al panel.
- Las contrasenias deben guardarse cifradas mediante el sistema de hashing de Laravel.
- El panel completo requiere autenticacion.
- No existira registro publico de administradores.
- El primer administrador se creara mediante un seeder o un comando interno.

Aunque inicialmente todos los administradores tendran los mismos permisos, el codigo se organizara usando middleware y politicas de autorizacion de Laravel. Esto permitira incorporar roles con permisos limitados en el futuro sin rehacer los modulos existentes.

Posible ampliacion futura:

- Tabla `roles`.
- Relacion entre usuarios y roles.
- Administrador general.
- Gestor de propiedades.
- Gestor comercial.

## Etapas sugeridas

1. Definir modelo de datos de propiedades.
2. Definir modelo de datos de consultas y tasaciones.
3. Definir arquitectura de rutas y modulos.
4. Instalar Laravel.
5. Implementar login administrador.
6. Crear migraciones principales.
7. Crear CRUD de tipos de propiedad.
8. Crear CRUD de ubicaciones/barrios.
9. Crear CRUD de propiedades.
10. Implementar carga de imagenes.
11. Implementar dashboard.
12. Implementar web publica.
13. Implementar formularios.
14. Pulir diseno, SEO y performance.

La arquitectura tecnica acordada se encuentra en `arquitectura-laravel.md`.

## Pendientes definidos

- Revisar y mejorar la forma de agregar ubicaciones desde el panel administrador.
- Evaluar si conviene simplificar la jerarquia para carga rapida o sumar ubicaciones precargadas mas completas por zona.
- Mantener en propiedades el selector/autocomplete, evitando texto libre para `ubicacion_id`.
