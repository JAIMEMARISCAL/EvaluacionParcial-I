<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Auth;
use App\Csrf;
use App\MateriaService;

// --- Cabeceras de seguridad (OWASP) ---
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self'");
// Activar HSTS solo cuando se sirva realmente por HTTPS:
// header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

Auth::startSecureSession();

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

function render(string $view, array $data = []): void
{
    extract($data);
    require __DIR__ . '/../views/' . $view . '.php';
}

function requireCsrfOrFail(): void
{
    if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
        http_response_code(400);
        echo 'Token CSRF inválido.';
        exit;
    }
}

// --- Rutas públicas ---
if ($uri === '/login' && $method === 'GET') {
    render('login', ['error' => null]);
    exit;
}

if ($uri === '/login' && $method === 'POST') {
    requireCsrfOrFail();
    $ok = Auth::attempt($_POST['username'] ?? '', $_POST['password'] ?? '');
    if ($ok) {
        header('Location: /materias', true, 302);
        exit;
    }
    render('login', ['error' => 'Credenciales inválidas.']);
    exit;
}

if ($uri === '/logout' && $method === 'POST') {
    requireCsrfOrFail();
    Auth::logout();
    header('Location: /login', true, 302);
    exit;
}

// --- A partir de aquí, todas las rutas requieren sesión ---
Auth::requireLogin();

$service = new MateriaService();

if ($uri === '/materias' && $method === 'GET') {
    render('materias/list', ['materias' => $service->listar()]);
    exit;
}

if ($uri === '/materias/nueva' && $method === 'GET') {
    render('materias/form', ['materia' => null, 'errores' => []]);
    exit;
}

if ($uri === '/materias' && $method === 'POST') {
    requireCsrfOrFail();
    $errores = $service->validar($_POST);
    if ($errores) {
        http_response_code(422);
        render('materias/form', ['materia' => $_POST, 'errores' => $errores]);
        exit;
    }
    $service->crear($_POST);
    header('Location: /materias', true, 302);
    exit;
}

if (preg_match('#^/materias/(\d+)/editar$#', $uri, $m) && $method === 'GET') {
    $materia = $service->buscar((int) $m[1]);
    if (!$materia) {
        http_response_code(404);
        echo 'No encontrada.';
        exit;
    }
    render('materias/form', ['materia' => $materia, 'errores' => []]);
    exit;
}

if (preg_match('#^/materias/(\d+)$#', $uri, $m) && $method === 'POST') {
    requireCsrfOrFail();
    $id = (int) $m[1];
    $errores = $service->validar($_POST, $id);
    if ($errores) {
        http_response_code(422);
        $materia = $_POST;
        $materia['id'] = $id;
        render('materias/form', ['materia' => $materia, 'errores' => $errores]);
        exit;
    }
    $service->actualizar($id, $_POST);
    header('Location: /materias', true, 302);
    exit;
}

if (preg_match('#^/materias/(\d+)/eliminar$#', $uri, $m) && $method === 'POST') {
    requireCsrfOrFail();
    $service->eliminarLogico((int) $m[1]);
    header('Location: /materias', true, 302);
    exit;
}

http_response_code(404);
echo 'Ruta no encontrada.';
