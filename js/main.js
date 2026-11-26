// Año automático en el pie de página
document.getElementById('anio').textContent = new Date().getFullYear();

// Menú en celulares
const botonMenu = document.querySelector('.menu-boton');
const menu = document.getElementById('menu');

function cerrarMenu() {
  menu.classList.remove('abierto');
  botonMenu.setAttribute('aria-expanded', 'false');
}

botonMenu.addEventListener('click', () => {
  const abierto = menu.classList.toggle('abierto');
  botonMenu.setAttribute('aria-expanded', String(abierto));
});
menu.querySelectorAll('a').forEach((enlace) => enlace.addEventListener('click', cerrarMenu));
document.addEventListener('keydown', (e) => { if (e.key === 'Escape') cerrarMenu(); });

// Formulario de contacto
const form = document.getElementById('form-contacto');
const aviso = document.getElementById('aviso');

function mostrarAviso(texto, tipo) {
  aviso.textContent = texto;
  aviso.className = 'aviso ' + tipo;
  aviso.hidden = false;
  aviso.focus();
}

form.addEventListener('submit', async (e) => {
  e.preventDefault();

  if (!form.checkValidity()) {
    mostrarAviso('Completa los tres campos e ingresa un correo válido, por ejemplo nombre@correo.cl.', 'error');
    return;
  }

  const boton = form.querySelector('button[type="submit"]');
  boton.disabled = true;
  boton.textContent = 'Enviando...';

  try {
    const respuesta = await fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'Accept': 'application/json' }
    });
    const datos = await respuesta.json();

    if (datos.ok) {
      mostrarAviso(datos.mensaje, 'ok');
      form.reset();
    } else {
      mostrarAviso(datos.mensaje || 'No se pudo enviar el mensaje. Inténtalo de nuevo.', 'error');
    }
  } catch (err) {
    mostrarAviso('No se pudo enviar el mensaje. Revisa tu conexión e inténtalo de nuevo.', 'error');
  } finally {
    boton.disabled = false;
    boton.textContent = 'Enviar mensaje';
  }
});
