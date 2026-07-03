<?php
declare(strict_types=1);

namespace App;

use PDO;

final class Auth
{
    public static function startSecureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'httponly' => true,
                'samesite' => 'Strict',
                // 'secure' => true, // activar cuando se sirva por HTTPS
            ]);
            session_start();
        }
    }

    public static function attempt(string $username, string $password): bool
    {
        $pdo = Database::get();
        $stmt = $pdo->prepare('SELECT id, password_hash, rol FROM usuarios WHERE username = :u');
        $stmt->execute([':u' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['rol'] = $user['rol'];
            return true;
        }

        return false;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /login', true, 302);
            exit;
        }
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}
