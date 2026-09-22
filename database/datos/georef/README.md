# Datos fuente de Georef

Los archivos CSV de esta carpeta provienen de la API Georef de Argentina.

Se descargan localmente para validar y ejecutar la importación de ubicaciones,
pero no se incluyen en el repositorio por su tamaño. La fuente oficial es:

https://www.argentina.gob.ar/georef/descarga-de-la-base-completa

Archivos requeridos para la primera importación:

- provincias.csv
- departamentos.csv
- municipios.csv
- localidades.csv

Para importar los cuatro archivos en la base local:

```text
php artisan ubicaciones:importar-georef
```
