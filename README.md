# GCREGULATORY

Sitio web de GCREGULATORY, asesoría en asuntos regulatorios para productos
farmacéuticos, cosméticos y dispositivos médicos. Incluye formulario de contacto
por correo electrónico.

## Estructura

```
index.html      Página principal (portada con carrusel, servicios, equipo, contacto)
css/style.css   Estilos. Los colores están en las variables de :root, al inicio
js/main.js      Carrusel, menú en celulares y envío del formulario sin recargar
contacto.php    Envía el correo (requiere hosting con PHP)
assets/         Logos en SVG (símbolo y logo horizontal, versión clara y oscura)
```

## Antes de publicar

1. Abre `contacto.php` y cambia:
   - `$correo_destino`: el correo donde quieres recibir los mensajes.
   - `$correo_remitente`: un correo de tu propio dominio (por ejemplo `contacto@tudominio.cl`).
2. En `index.html`, sección Contacto: cambia el correo, el teléfono y la dirección.
3. En la sección Equipo: pon los nombres, cargos y descripciones reales.
4. Cambia los textos de ejemplo por los tuyos.

## Publicar en el hosting

Sube todo el contenido del repositorio a la carpeta `public_html` de tu hosting
(por el Administrador de archivos de cPanel o por FTP).

## Ver la página

- **GitHub Pages:** en *Settings → Pages*, elige la rama `main` y la carpeta raíz.
  La página se ve, pero el formulario no envía correos, porque GitHub Pages no ejecuta PHP.
- **Codespaces o VS Code:** abre `index.html` con la extensión Live Server.

El envío del correo solo se puede probar con la página publicada en un hosting con PHP.

## Paleta

| Color | Uso |
|---|---|
| `#0F2A3D` azul marino | Portada, pie y títulos |
| `#17706F` verde azulado | Botones, enlaces y números |
| `#5CC2BC` aqua | Botones sobre fondo oscuro y detalles |
| `#E6F3F1` menta | Fondos de secciones alternas |
| `#F3D3C4` durazno | Ilustración de la tercera diapositiva |
