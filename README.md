# Materias — proyecto de referencia (PHP 8.3 + PDO)

## 1. Stack

PHP 8.3, PDO (SQLite embebido para simplificar el arranque), sesiones nativas, BCrypt, CSRF por token.

## 2. Requisitos previos

Docker Desktop 4.x (o PHP 8.3 CLI instalado localmente).

## 3. Arranque en un solo comando

```bash
cp .env.example .env
docker compose up -d --build
# esperar unos segundos y abrir http://localhost:8080/login
```


La base de datos SQLite y el usuario administrador se crean automáticamente
en el primer arranque (migración + seed en `src/Database.php`).

## 4. Credenciales del usuario semilla

- Usuario: `admin`
- Contraseña: `Admin*2026`

(Solo para pruebas locales.)

## 5. URL local

http://localhost:8080/login

## 6. Cómo probar el CRUD

1. Inicia sesión con las credenciales anteriores.
2. Ve a `/materias`, crea una materia nueva (código único de 6 caracteres).
3. Edítala y luego elimínala (eliminación lógica: `activa = 0`).



