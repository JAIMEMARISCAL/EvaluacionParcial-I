<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?= $materia ? 'Editar' : 'Nueva' ?> materia</title>
<style>
body{font-family:sans-serif;max-width:420px;margin:40px auto}
input{display:block;width:100%;margin:8px 0;padding:8px}
.error{color:#b00020}
</style>
</head>
<body>
<p><a href="/materias">&larr; Volver al listado</a></p>
<h1><?= $materia ? 'Editar' : 'Nueva' ?> materia</h1>

<?php if (!empty($errores)): ?>
    <ul class="error">
        <?php foreach ($errores as $e): ?>
            <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" action="<?= $materia ? '/materias/' . (int) $materia['id'] : '/materias' ?>">
    <?= \App\Csrf::field() ?>
    <label>Código (6 caracteres)
        <input type="text" name="codigo" minlength="6" maxlength="6" required
               value="<?= htmlspecialchars($materia['codigo'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <label>Nombre
        <input type="text" name="nombre" minlength="5" maxlength="80" required
               value="<?= htmlspecialchars($materia['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <label>Créditos (1-6)
        <input type="number" name="creditos" min="1" max="6" required
               value="<?= htmlspecialchars((string) ($materia['creditos'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <label>Semestre (1-10)
        <input type="number" name="semestre" min="1" max="10" required
               value="<?= htmlspecialchars((string) ($materia['semestre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <button type="submit">Guardar</button>
</form>
</body>
</html>
