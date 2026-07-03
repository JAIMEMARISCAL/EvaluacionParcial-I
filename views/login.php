<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Iniciar sesión</title>
<style>
body{font-family:sans-serif;max-width:360px;margin:80px auto}
input{display:block;width:100%;margin:8px 0;padding:8px}
.error{color:#b00020}
</style>
</head>
<body>
<h1>Iniciar sesión</h1>
<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
<form method="post" action="/login">
    <?= \App\Csrf::field() ?>
    <label>Usuario
        <input type="text" name="username" required>
    </label>
    <label>Contraseña
        <input type="password" name="password" required>
    </label>
    <button type="submit">Entrar</button>
</form>
</body>
</html>
