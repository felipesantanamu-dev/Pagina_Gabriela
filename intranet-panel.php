<?php
/** Panel interno. Solo se ve con la sesión iniciada. */
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => !empty($_SERVER['HTTPS']),
]);
session_start();
header('Cache-Control: no-store');
header('X-Robots-Tag: noindex');

// Cerrar sesión (solo con POST y con la clave de seguridad de la sesión)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['csrf'])
    && hash_equals($_SESSION['csrf'], (string)($_POST['csrf'] ?? ''))) {
    $_SESSION = [];
    session_destroy();
    header('Location: intranet.html');
    exit;
}

if (empty($_SESSION['usuario'])) {
    header('Location: intranet.html');
    exit;
}

$nombre = htmlspecialchars($_SESSION['nombre'], ENT_QUOTES, 'UTF-8');
$csrf   = htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panel interno | GCREGULATORY</title>
  <meta name="robots" content="noindex">
  <script>
    try {
      if (localStorage.getItem('gc-tema') === 'oscuro') document.documentElement.setAttribute('data-tema', 'oscuro');
      if (localStorage.getItem('gc-idioma') === 'en') document.documentElement.lang = 'en';
    } catch (e) {}
  </script>
  <link rel="icon" href="assets/logo-simbolo.svg" type="image/svg+xml">
  <link rel="stylesheet" href="css/style.css?v=3">
</head>
<body id="top">

  <a class="saltar" href="#contenido">Saltar al contenido</a>

  <header class="cabecera">
    <div class="contenedor cabecera-interior">
      <a href="index.html" class="logo" aria-label="GCREGULATORY, ir al inicio">
        <svg viewBox="-2 -3 68 68" aria-hidden="true"><path d="M32 4 L56.2 18 V46 L32 60 L7.8 46 V18 Z" fill="none" stroke="currentColor" stroke-width="5" stroke-linejoin="round"/><circle cx="32" cy="4" r="5" fill="#5CC2BC"/><text x="32" y="40" text-anchor="middle" font-family="Georgia,Times New Roman,serif" font-size="22" font-weight="700" fill="currentColor">GC</text></svg>
        <span class="logo-texto">
          <span class="logo-nombre">REGULATORY</span>
          <span class="logo-lema">ASUNTOS REGULATORIOS</span>
        </span>
      </a>
      <div class="cabecera-acciones">
        <nav id="menu" class="menu" aria-label="Principal">
          <a href="index.html">Volver al sitio</a>
        </nav>
        <div class="herramientas">
          <button type="button" class="herramienta boton-idioma" data-no-trad lang="en" aria-label="Switch to English" title="Switch to English">
            <span class="idioma-texto" aria-hidden="true">EN</span>
          </button>
          <button type="button" class="herramienta boton-tema" data-no-trad aria-label="Activar modo noche" title="Activar modo noche">
            <svg class="icono-luna" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
            <svg class="icono-sol" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" hidden><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
          </button>
        </div>
      </div>
      <button class="menu-boton" type="button" aria-expanded="false" aria-controls="menu">Menú</button>
    </div>
  </header>

  <main id="contenido">
    <section class="seccion">
      <div class="contenedor angosto">
        <h1 class="titulo-pagina">Panel interno</h1>
        <p class="panel-datos"><span>Sesión iniciada como</span> <strong><?= $nombre ?></strong></p>
        <p class="intro">Aquí irán los documentos y las herramientas del equipo.</p>
        <form method="post" action="intranet-panel.php">
          <input type="hidden" name="csrf" value="<?= $csrf ?>">
          <button type="submit" class="boton boton-secundario">Cerrar sesión</button>
        </form>
      </div>
    </section>
  </main>

  <footer class="pie">
    <div class="contenedor pie-interior">
      <div class="logo" role="img" aria-label="GCREGULATORY">
        <svg viewBox="-2 -3 68 68" aria-hidden="true"><path d="M32 4 L56.2 18 V46 L32 60 L7.8 46 V18 Z" fill="none" stroke="currentColor" stroke-width="5" stroke-linejoin="round"/><circle cx="32" cy="4" r="5" fill="#5CC2BC"/><text x="32" y="40" text-anchor="middle" font-family="Georgia,Times New Roman,serif" font-size="22" font-weight="700" fill="currentColor">GC</text></svg>
        <span class="logo-texto">
          <span class="logo-nombre">REGULATORY</span>
          <span class="logo-lema">ASUNTOS REGULATORIOS</span>
        </span>
      </div>
      <p>&copy; <span id="anio"></span> GCREGULATORY. Todos los derechos reservados.</p>
    </div>
  </footer>

  <script src="js/main.js?v=3"></script>
</body>
</html>
