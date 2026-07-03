<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Materias</title>
<style>
body{font-family:sans-serif;max-width:800px;margin:40px auto}
table{width:100%;border-collapse:collapse}
th,td{border:1px solid #ccc;padding:8px;text-align:left}
form.inline{display:inline}
</style>
</head>
<body>
<p>Sesión: <?= htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>
    | <form class="inline" method="post" action="/logout"><?= \App\Csrf::field() ?><button>Salir</button></form>
</p>
<h1>Materias</h1>
<p><a href="/materias/nueva">+ Nueva materia</a></p>
<table>
<tr><th>Código</th><th>Nombre</th><th>Créditos</th><th>Semestre</th><th>Acciones</th></tr>
<?php foreach ($materias as $m): ?>
<tr>
    <td><?= htmlspecialchars($m['codigo'], ENT_QUOTES, 'UTF-8') ?></td>
    <td><?= htmlspecialchars($m['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
    <td><?= (int) $m['creditos'] ?></td>
    <td><?= (int) $m['semestre'] ?></td>
    <td>
        <a href="/materias/<?= (int) $m['id'] ?>/editar">Editar</a>
        <form class="inline" method="post" action="/materias/<?= (int) $m['id'] ?>/eliminar"
              onsubmit="return confirm('¿Eliminar esta materia?');">
            <?= \App\Csrf::field() ?>
            <button>Eliminar</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
