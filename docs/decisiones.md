# Decisiones técnicas

1. **SQLite embebido en lugar de MySQL/PostgreSQL.** Elegì este para que el proyecto
   arranque de una con con un solo comando sin depender de un contenedor de base de datos aparte.
   El esquema (`db/schema.sql`) usa tipos estándar SQL fácilmente portables a
   PostgreSQL o MySQL cambiando `INTEGER PRIMARY KEY AUTOINCREMENT` por
   `BIGSERIAL`/`AUTO_INCREMENT` según el motor.

2. **Router manual en `public/index.php` en vez de un framework.** Al ser un
   micro-CRUD de 7 rutas, un router basado en `preg_match` es más simple de leer
   y auditar que introducir un framework completo, y deja explícito el flujo
   request → validar sesión → delegar en el servicio.

3. **Autoloader PSR-4 simple en `vendor/autoload.php` en vez de Composer real.**
   Evita depender de que el evaluador tenga Composer instalado; el proyecto
   solo tiene una dependencia (su propio código), así que un autoloader de
   ~10 líneas cubre la misma necesidad sin `composer install`.

4. **Sesión nativa de PHP con `HttpOnly` + `SameSite=Strict`.** No se usa
   `Secure=true` por defecto porque el entorno de desarrollo local corre en
   HTTP; se documenta en el código dónde activarlo para producción/HTTPS.

5. **Eliminación lógica en vez de física.** El campo `activa` se pone en `0`
   en lugar de borrar la fila, cumpliendo el requisito de trazabilidad y
   evitando pérdida accidental de datos.
