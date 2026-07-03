<?php
declare(strict_types=1);

namespace App;

use PDO;

final class Database
{
    private static ?PDO $instance = null;

    public static function get(): PDO
    {
        if (self::$instance === null) {
            $dbPath = __DIR__ . '/../db/database.sqlite';
            $isNew = !file_exists($dbPath);

            $pdo = new PDO('sqlite:' . $dbPath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('PRAGMA foreign_keys = ON;');

            if ($isNew) {
                $schema = file_get_contents(__DIR__ . '/../db/schema.sql');
                $pdo->exec($schema);
                self::seedAdmin($pdo);
            }

            self::$instance = $pdo;
        }

        return self::$instance;
    }

    private static function seedAdmin(PDO $pdo): void
    {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE username = :u');
        $stmt->execute([':u' => 'admin']);

        if ((int) $stmt->fetchColumn() === 0) {
            $hash = password_hash('Admin*2026', PASSWORD_BCRYPT);
            $insert = $pdo->prepare(
                'INSERT INTO usuarios (username, password_hash, rol) VALUES (:u, :h, :r)'
            );
            $insert->execute([':u' => 'admin', ':h' => $hash, ':r' => 'ADMIN']);
        }
    }
}
