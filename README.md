# CMR Inmobiliario

Sistema inmobiliario desarrollado con Laravel 12, Blade, Tailwind CSS, Vite y MySQL.

Incluye un panel privado para administrar propiedades, operaciones comerciales, imágenes, videos, ubicaciones, características, administradores, consultas y tasaciones.

## Requisitos locales

- PHP 8.2 o superior.
- Composer 2.
- Node.js 20 o superior.
- MySQL.
- Extensiones PHP indicadas por Composer.
- `pdo_sqlite` y `sqlite3` para ejecutar las pruebas en Windows.

## Instalación

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
php artisan migrate
php artisan db:seed
php artisan storage:link
```

Antes de ejecutar los seeders, configurar en `.env`:

```dotenv
ADMIN_NOMBRE=Administrador
ADMIN_APELLIDO=
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=UnaClaveSegura123
```

## Desarrollo

En Windows:

```powershell
composer run dev
```

En Linux o WSL, incluyendo Laravel Pail:

```bash
composer run dev:linux
```

El panel queda disponible en:

```text
http://127.0.0.1:8000/administracion
```

## Pruebas

Windows:

```powershell
composer run test:windows
```

Linux:

```bash
composer test
```

Controles adicionales:

```powershell
.\vendor\bin\pint --test
npm run build
composer validate --strict
```

## Módulos

- Dashboard y alertas operativas.
- Propiedades, papelera y restauración.
- Venta, alquiler y alquiler temporal.
- Imágenes, portada y ordenamiento.
- Videos de YouTube.
- Tipos de propiedad.
- Ubicaciones jerárquicas y autocomplete.
- Servicios, ambientes, amenities y características.
- CRM de oportunidades para consultas y tasaciones.
- Embudo comercial, prioridades, asesores, próximas tareas e historial.
- Agenda de visitas con conflictos horarios, confirmaciones y resultados.
- Recordatorios de visitas por correo mediante colas.
- Dashboard comercial con conversiones, demanda, tiempos y rendimiento por asesor.
- Administradores y cambio de contraseña.
- Notificaciones por correo mediante colas.
- Health check en `/estado`.
- Backups programados.

## Producción

La guía completa se encuentra en [DESPLIEGUE.md](DESPLIEGUE.md).

Usar `.env.production.example` como referencia. Nunca subir un `.env` real ni credenciales al repositorio.

## Documentación funcional

- [documento-funcional.md](documento-funcional.md)
- [arquitectura-laravel.md](arquitectura-laravel.md)
- [modelo-datos.md](modelo-datos.md)
- [esquema-base-datos.md](esquema-base-datos.md)
