<?php
/**
 * Inicio de sesión de la intranet. Responde JSON.
 * Requiere hosting con PHP. Los usuarios se configuran en intranet-config.php.
 */
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => !empty($_SERVER['HTTPS']),
]);
session_start();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$en = (($_POST['idioma'] ?? '') === 'en');
function t($es, $ingles) { global $en; return $en ? $ingles : $es; }
function responder($ok, $mensaje, $extra = [], $codigo = 200) {
    http_response_code($codigo);
    echo json_encode(array_merge(['ok' => $ok, 'mensaje' => $mensaje], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(false, t('Método no permitido.', 'Method not allowed.'), [], 405);
}

// Bloqueo temporal después de 5 intentos fallidos seguidos (por navegador)
$max_intentos = 5;
$espera = 300; // segundos
if (($_SESSION['intentos'] ?? 0) >= $max_intentos && time() - ($_SESSION['ultimo_intento'] ?? 0) < $espera) {
    responder(false, t('Demasiados intentos. Espera unos minutos e inténtalo de nuevo.', 'Too many attempts. Wait a few minutes and try again.'), [], 429);
}

$correo = strtolower(trim($_POST['correo'] ?? ''));
$clave  = (string)($_POST['clave'] ?? '');

$config   = require __DIR__ . '/intranet-config.php';
$usuarios = $config['usuarios'] ?? [];
$usuario  = $usuarios[$correo] ?? null;

// Se verifica siempre una contraseña, exista o no el usuario, para no delatar qué correos existen
$hash = $usuario['hash'] ?? password_hash('relleno-sin-uso', PASSWORD_DEFAULT);
$valido = password_verify($clave, $hash) && $usuario !== null;

if (!$valido) {
    $_SESSION['intentos'] = ($_SESSION['intentos'] ?? 0) + 1;
    $_SESSION['ultimo_intento'] = time();
    usleep(500000);
    responder(false, t('Correo o contraseña incorrectos.', 'Incorrect email or password.'), [], 401);
}

session_regenerate_id(true);
$_SESSION = [
    'usuario' => $correo,
    'nombre'  => $usuario['nombre'] ?? $correo,
    'csrf'    => bin2hex(random_bytes(16)),
];

responder(true, t('Ingreso correcto.', 'Signed in.'), ['redirigir' => 'intranet-panel.php']);
