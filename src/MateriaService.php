<?php
declare(strict_types=1);

namespace App;

final class MateriaService
{
    private MateriaRepository $repo;

    public function __construct()
    {
        $this->repo = new MateriaRepository();
    }

    /** @return string[] lista de errores, vacía si es válido */
    public function validar(array $data, ?int $idActual = null): array
    {
        $errores = [];

        $codigo = trim((string) ($data['codigo'] ?? ''));
        $nombre = trim((string) ($data['nombre'] ?? ''));
        $creditos = $data['creditos'] ?? null;
        $semestre = $data['semestre'] ?? null;

        if (strlen($codigo) !== 6) {
            $errores[] = 'El código debe tener exactamente 6 caracteres.';
        } elseif ($this->repo->codigoExiste($codigo, $idActual)) {
            $errores[] = 'El código ya existe.';
        }

        if (strlen($nombre) < 5 || strlen($nombre) > 80) {
            $errores[] = 'El nombre debe tener entre 5 y 80 caracteres.';
        }

        if (!is_numeric($creditos) || (int) $creditos < 1 || (int) $creditos > 6) {
            $errores[] = 'Los créditos deben estar entre 1 y 6.';
        }

        if (!is_numeric($semestre) || (int) $semestre < 1 || (int) $semestre > 10) {
            $errores[] = 'El semestre debe estar entre 1 y 10.';
        }

        return $errores;
    }

    public function listar(): array
    {
        return $this->repo->findAllActivas();
    }

    public function buscar(int $id): ?array
    {
        return $this->repo->find($id);
    }

    public function crear(array $data): void
    {
        $this->repo->crear(
            trim($data['codigo']),
            trim($data['nombre']),
            (int) $data['creditos'],
            (int) $data['semestre']
        );
    }

    public function actualizar(int $id, array $data): void
    {
        $this->repo->actualizar(
            $id,
            trim($data['codigo']),
            trim($data['nombre']),
            (int) $data['creditos'],
            (int) $data['semestre']
        );
    }

    public function eliminarLogico(int $id): void
    {
        $this->repo->eliminarLogico($id);
    }
}
