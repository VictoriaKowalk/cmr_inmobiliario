# Checklist de QA y salida

## Verificado automáticamente

- [x] Suite PHP completa.
- [x] Migraciones desde una base vacía con SQLite.
- [x] Estado de migraciones MySQL.
- [x] Compilación Blade.
- [x] Compilación Vite de producción.
- [x] Formato Laravel Pint.
- [x] Validez de `composer.json`.
- [x] Rutas privadas protegidas.
- [x] Throttling de login y formularios públicos.
- [x] Honeypot de formularios.
- [x] Cabeceras de seguridad.
- [x] Health check de base de datos y storage.
- [x] Notificaciones dirigidas sólo a administradores activos.
- [x] Fallback de imágenes cuando GD no está habilitado.
- [x] Protección de estados comerciales incompatibles.
- [x] Protección para no desactivar la cuenta propia.

## Revisión manual local

- [ ] Ingresar y cerrar sesión.
- [ ] Cambiar la contraseña y volver a ingresar.
- [ ] Crear, editar, pausar, destacar, eliminar y restaurar una propiedad.
- [ ] Crear venta y alquiler simultáneos.
- [ ] Editar precio, moneda y estado desde el detalle.
- [ ] Cargar imágenes JPG, PNG y WebP.
- [ ] Reordenar imágenes y cambiar portada.
- [ ] Probar el límite de peso y dimensiones.
- [ ] Agregar y eliminar un video de YouTube.
- [ ] Crear y seleccionar una ubicación.
- [ ] Crear y seleccionar una característica adicional.
- [ ] Enviar contacto, consulta por propiedad y tasación.
- [ ] Cambiar seguimiento y notas internas.
- [ ] Revisar dashboard y bandeja unificada.
- [ ] Crear, editar y desactivar otro administrador.

## Responsive y accesibilidad

Probar como mínimo en 375, 768, 1024 y 1440 píxeles.

- [ ] Navegación utilizable sin desbordes bloqueantes.
- [ ] Tablas desplazables horizontalmente.
- [ ] Formularios sin campos cortados.
- [ ] Ordenamiento de imágenes utilizable en dispositivo táctil.
- [ ] Navegación completa mediante teclado.
- [ ] Foco visible en enlaces, botones y campos.
- [ ] Lectura correcta con zoom al 200%.
- [ ] Contraste y mensajes de error comprensibles.
- [ ] Etiquetas asociadas a todos los campos.

## Infraestructura final

- [ ] Dominio y HTTPS configurados.
- [ ] `APP_DEBUG=false`.
- [ ] Usuario MySQL con permisos mínimos.
- [ ] SMTP real probado.
- [ ] Worker permanente supervisado.
- [ ] Scheduler ejecutándose cada minuto.
- [ ] GD y OPcache habilitados.
- [ ] `mysqldump` disponible.
- [ ] Backup real generado.
- [ ] Restauración probada en una base vacía.
- [ ] Monitor externo consultando `/estado`.
- [ ] Alertas de disco, errores y backups configuradas.

La publicación debe aprobarse solamente cuando todos los elementos de infraestructura final estén marcados.
