<?php
declare(strict_types=1);

namespace App;

use PDO;

final class MateriaRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::get();
    }

    /** @return array<int, array<string, mixed>> */
    public function findAllActivas(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM materias WHERE activa = 1 ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM materias WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function codigoExiste(string $codigo, ?int $exceptId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM materias WHERE codigo = :codigo';
        $params = [':codigo' => $codigo];
        if ($exceptId !== null) {
            $sql .= ' AND id != :id';
            $params[':id'] = $exceptId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function crear(string $codigo, string $nombre, int $creditos, int $semestre): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO materias (codigo, nombre, creditos, semestre, activa)
             VALUES (:codigo, :nombre, :creditos, :semestre, 1)'
        );
        $stmt->execute([
            ':codigo' => $codigo,
            ':nombre' => $nombre,
            ':creditos' => $creditos,
            ':semestre' => $semestre,
        ]);
    }

    public function actualizar(int $id, string $codigo, string $nombre, int $creditos, int $semestre): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE materias SET codigo = :codigo, nombre = :nombre,
             creditos = :creditos, semestre = :semestre WHERE id = :id'
        );
        $stmt->execute([
            ':codigo' => $codigo,
            ':nombre' => $nombre,
            ':creditos' => $creditos,
            ':semestre' => $semestre,
            ':id' => $id,
        ]);
    }

    public function eliminarLogico(int $id): void
    {
        $stmt = $this->pdo->prepare('UPDATE materias SET activa = 0 WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
