<?php

return [
    'habilitado' => (bool) env('BACKUP_ENABLED', false),
    'ruta' => storage_path('app/private/backups'),
    'retencion_dias' => (int) env('BACKUP_RETENTION_DAYS', 14),
    'mysqldump' => env('MYSQLDUMP_PATH', 'mysqldump'),
];
