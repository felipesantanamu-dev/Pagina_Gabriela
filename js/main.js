// Año automático en el pie de página
document.getElementById('anio').textContent = new Date().getFullYear();

const form = document.getElementById('form-contacto');
const aviso = document.getElementById('aviso');

function mostrarAviso(texto, tipo) {
  aviso.textContent = texto;
  aviso.className = 'aviso ' + tipo;
  aviso.hidden = false;
  aviso.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

form.addEventListener('submit', async (e) => {
  e.preventDefault();

  if (!form.checkValidity()) {
    mostrarAviso('Por favor completa todos los campos con un correo válido.', 'error');
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
      mostrarAviso(datos.mensaje || 'No se pudo enviar el mensaje.', 'error');
    }
  } catch (err) {
    mostrarAviso('No se pudo enviar el mensaje. Inténtalo de nuevo más tarde.', 'error');
  } finally {
    boton.disabled = false;
    boton.textContent = 'Enviar mensaje';
  }
});
