<?php
/**
 * Envío del formulario de contacto por correo.
 * Requiere un hosting con PHP (cualquier cPanel normal sirve).
 */

// ====== CONFIGURACIÓN: CAMBIA ESTOS DOS VALORES ======
$correo_destino = 'tu-correo@tudominio.cl';     // Aquí llegan los mensajes
$correo_remitente = 'contacto@tudominio.cl';    // Un correo de TU dominio (mejora la entrega)
// =====================================================

header('Content-Type: application/json; charset=utf-8');

// Idioma de las respuestas: español por defecto, inglés si la página lo envía
$en = (($_POST['idioma'] ?? '') === 'en');
function t($es, $ingles) {
    global $en;
    return $en ? $ingles : $es;
}

function responder($ok, $mensaje, $codigo = 200) {
    http_response_code($codigo);
    echo json_encode(['ok' => $ok, 'mensaje' => $mensaje], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(false, t('Método no permitido.', 'Method not allowed.'), 405);
}

// Campo trampa: si un robot lo llena, fingimos éxito y no enviamos nada
if (!empty($_POST['sitio'])) {
    responder(true, t('Mensaje enviado.', 'Message sent.'));
}

$nombre  = trim($_POST['nombre']  ?? '');
$correo  = trim($_POST['correo']  ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

// Quitar saltos de línea de campos que van en cabeceras (evita inyección de cabeceras)
$nombre = str_replace(["\r", "\n"], ' ', $nombre);
$correo = str_replace(["\r", "\n"], '', $correo);

if ($nombre === '' || $mensaje === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    responder(false, t('Revisa que todos los campos estén completos y el correo sea válido.', 'Check that all fields are filled in and the email is valid.'), 400);
}

if (mb_strlen($nombre) > 100 || mb_strlen($mensaje) > 3000) {
    responder(false, t('El mensaje o el nombre es demasiado largo.', 'The message or the name is too long.'), 400);
}

$asunto = 'Nuevo mensaje desde la página web - ' . $nombre;

$cuerpo  = "Has recibido un nuevo mensaje desde tu página web.\n\n";
$cuerpo .= "Nombre: $nombre\n";
$cuerpo .= "Correo: $correo\n\n";
$cuerpo .= "Mensaje:\n$mensaje\n";

$cabeceras  = "From: Página web <$correo_remitente>\r\n";
$cabeceras .= "Reply-To: $nombre <$correo>\r\n";
$cabeceras .= "Content-Type: text/plain; charset=UTF-8\r\n";

$asunto_codificado = '=?UTF-8?B?' . base64_encode($asunto) . '?=';

if (mail($correo_destino, $asunto_codificado, $cuerpo, $cabeceras)) {
    responder(true, t('¡Gracias! Tu mensaje fue enviado. Te responderé pronto.', 'Thank you! Your message was sent. We will reply soon.'));
}

responder(false, t('No se pudo enviar el mensaje. Inténtalo de nuevo más tarde.', 'The message could not be sent. Please try again later.'), 500);
