# Despliegue y operación

## Requisitos

- PHP 8.2 o superior con `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`, `zip`, `gd` y OPcache.
- MySQL 8 o compatible.
- Composer 2.
- Node.js 20 o superior, solamente durante la construcción.
- `mysqldump` disponible para los backups.
- Un servidor web con HTTPS.

`gd` es necesario para redimensionar imágenes y convertirlas a WebP. Sin esa extensión el sistema conserva el archivo original.

## Preparación

1. Copiar `.env.production.example` como `.env`.
2. Configurar la URL, MySQL, SMTP y credenciales secretas.
3. Ejecutar:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link
npm ci
npm run build
php artisan optimize
```

El servidor web debe apuntar al directorio `public`, nunca a la raíz del proyecto.

## Permisos

El usuario del servidor web necesita escritura exclusivamente en:

- `storage`
- `bootstrap/cache`

No se debe dar permiso de escritura público al resto del proyecto.

## Cola

Las notificaciones de consultas y tasaciones utilizan la cola `notificaciones`.

Comando del worker:

```bash
php artisan queue:work database --queue=notificaciones,default --tries=3 --timeout=60
```

Debe ejecutarse mediante Supervisor, systemd o el administrador de procesos del hosting. Después de cada despliegue:

```bash
php artisan queue:restart
```

Para inspeccionar fallos:

```bash
php artisan queue:failed
php artisan queue:retry all
```

## Tareas programadas

Configurar una ejecución por minuto:

```cron
* * * * * cd /ruta/al/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

En Windows se puede crear una tarea programada que ejecute `php artisan schedule:run` cada minuto.

El scheduler:

- Crea el backup diario a las 02:00.
- Elimina trabajos fallidos con más de 14 días a las 03:00.

## Backups

Variables:

```dotenv
BACKUP_ENABLED=true
BACKUP_RETENTION_DAYS=14
MYSQLDUMP_PATH=mysqldump
```

Ejecución manual:

```bash
php artisan sistema:backup --force
```

Los archivos se guardan fuera del directorio público:

```text
storage/app/private/backups/
```

Cada ZIP contiene:

- `base-datos.sql`
- `archivos/` con las imágenes públicas

Para restaurar:

1. Detener temporalmente el worker.
2. Importar `base-datos.sql` en una base vacía.
3. Restaurar `archivos/` dentro de `storage/app/public/`.
4. Ejecutar `php artisan storage:link`.
5. Ejecutar `php artisan optimize:clear && php artisan optimize`.
6. Reiniciar el worker.

La restauración debe probarse periódicamente; un backup no verificado no garantiza recuperación.

## Monitoreo

El endpoint:

```text
GET /estado
```

devuelve HTTP `200` cuando MySQL y storage responden, o `503` cuando falla alguno.

El endpoint `/up` continúa disponible como comprobación básica de Laravel.

En producción se recomienda monitorear:

- `/estado`
- espacio libre del disco
- tabla `failed_jobs`
- existencia y antigüedad del último backup
- vencimiento del certificado HTTPS

## Correo

Configurar SMTP y mantener:

```dotenv
ADMIN_NOTIFICATIONS_ENABLED=true
```

Todos los administradores activos reciben un correo cuando entra una consulta o tasación. Para deshabilitar temporalmente los avisos sin perder registros:

```dotenv
ADMIN_NOTIFICATIONS_ENABLED=false
```

## Despliegue de actualizaciones

```bash
php artisan down
composer install --no-dev --optimize-autoloader
php artisan migrate --force
npm ci
npm run build
php artisan optimize
php artisan queue:restart
php artisan up
```

Antes de una actualización importante, crear y verificar un backup.
