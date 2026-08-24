# Mejoras futuras

## Recuperación automática de contraseña por correo

Agregar la opción **Olvidé mi contraseña** en el ingreso al panel para que administradores, supervisores y asesores puedan recuperar el acceso sin intervención de otro administrador.

Alcance previsto:

- Solicitud mediante el correo registrado del usuario.
- Respuesta genérica que no permita descubrir si una cuenta existe.
- Enlace de recuperación con token único, temporal y de un solo uso.
- Vencimiento configurable del enlace.
- Formulario para definir y confirmar la nueva contraseña.
- Aplicación de las mismas reglas de seguridad usadas al crear usuarios.
- Invalidación del token después del cambio.
- Limitación de solicitudes para evitar abuso.
- Envío mediante la cola de correos del sistema.
- Registro de la solicitud y del cambio de contraseña para auditoría, sin almacenar contraseñas ni tokens en texto plano.
- Pruebas automatizadas del circuito completo, incluyendo tokens vencidos, inválidos y reutilizados.

Mientras esta mejora no esté implementada, un Administrador deberá restablecer manualmente la contraseña desde **Mi empresa → Usuarios y equipo**.
