# Pagina_Gabriela

Sitio web de una página con formulario de contacto por correo electrónico.

## Estructura

```
index.html      Página principal
css/style.css   Estilos (colores en las variables de :root)
js/main.js      Envío del formulario sin recargar la página
contacto.php    Envía el correo (requiere hosting con PHP)
```

## Antes de publicar

1. Abre `contacto.php` y cambia:
   - `$correo_destino`: el correo donde quieres recibir los mensajes.
   - `$correo_remitente`: un correo de tu propio dominio (por ejemplo `contacto@tudominio.cl`).
2. Cambia los textos de `index.html` por los tuyos.

## Publicar en el hosting

Sube todo el contenido del repositorio a la carpeta `public_html` de tu hosting
(por el Administrador de archivos de cPanel o por FTP).

## Ver cambios en vivo mientras trabajas

- **En el navegador:** GitHub Codespaces (botón verde *Code → Codespaces*). Abre `index.html` con la extensión Live Server.
- **En tu PC:** VS Code + extensión Live Server.

Nota: `contacto.php` solo funciona en un servidor con PHP. En la vista previa local la página se ve igual, pero el envío del correo se prueba ya publicada en el hosting.
