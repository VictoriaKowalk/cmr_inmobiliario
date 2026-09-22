# Ubicaciones de Nordelta de Tokko

`nordelta.json` es la respuesta original de la búsqueda pública de Tokko para `nordelta`, descargada el 22 de septiembre de 2026.

El seeder `UbicacionesTokkoNordeltaSeeder` usa únicamente los registros cuyo tipo es `Barrio`:

- Los que cuelgan directamente de Nordelta se crean como **barrios**.
- Los que cuelgan directamente de uno de esos barrios se crean como **subbarrios**.

Las áreas, condominios y niveles más profundos se conservan en el JSON como referencia, pero no se importan todavía. Esto evita mezclar categorías hasta que el CRM incorpore más niveles de ubicación.

Para cargarlo en una base nueva:

```bash
php artisan migrate --seed
```

Para cargar solo estas ubicaciones, después de tener creada la jerarquía base:

```bash
php artisan db:seed --class=UbicacionesTokkoNordeltaSeeder
```

## Zona Norte

La carpeta `zona-norte/` conserva las fichas raíz de Tigre, San Fernando, San Isidro, Vicente López, Pilar, Escobar, Malvinas Argentinas, San Miguel y José Clemente Paz.

Tokko limita la cantidad de consultas seguidas. Para continuar la descarga de sus fichas hijas sin perder lo ya obtenido, ejecutar por lotes:

```bash
php artisan ubicaciones:descargar-tokko-zona-norte --limite=20 --pausa=3
```

Se puede repetir el comando: omite los archivos ya descargados y avanza con los pendientes. Una vez reunidos, se clasifican e importan solo barrios y subbarrios.

El comando verifica la conexión HTTPS mediante `database/certificados/cacert.pem`; ese archivo se incluye para que funcione aunque PHP local no tenga configurado un certificado raíz.
