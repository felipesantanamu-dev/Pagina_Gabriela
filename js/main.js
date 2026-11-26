// Año automático en el pie de página
document.getElementById('anio').textContent = new Date().getFullYear();

// ---------- Menú en celulares ----------
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

// ---------- Carrusel de la portada ----------
const INTERVALO = 5000; // milisegundos entre diapositivas (5 segundos)

const carrusel = document.querySelector('.carrusel');
const ventana = carrusel.querySelector('.carrusel-ventana');
const slides = Array.from(carrusel.querySelectorAll('.slide'));
const puntos = carrusel.querySelector('.puntos');
const contador = carrusel.querySelector('.contador');
const botonPausa = carrusel.querySelector('.flecha-pausa');
const iconoPausa = botonPausa.querySelector('.icono-pausa');
const iconoPlay = botonPausa.querySelector('.icono-play');

const reducirMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

let actual = 0;
let temporizador = null;
let detenido = reducirMovimiento;  // la persona (o su dispositivo) pidió que no avance solo
let enPausa = false;               // pausa temporal: mouse encima, teclado dentro o pestaña oculta

slides.forEach((_, i) => {
  const boton = document.createElement('button');
  boton.type = 'button';
  boton.className = 'punto';
  boton.setAttribute('aria-label', 'Ir a la diapositiva ' + (i + 1));
  boton.appendChild(document.createElement('span'));
  boton.addEventListener('click', () => irPorUsuario(i));
  puntos.appendChild(boton);
});

function programar() {
  clearTimeout(temporizador);
  if (detenido || enPausa) return;
  temporizador = setTimeout(() => mostrar(actual + 1, false), INTERVALO);
}

function actualizarBotonPausa() {
  iconoPausa.hidden = detenido;
  iconoPlay.hidden = !detenido;
  botonPausa.setAttribute('aria-label', detenido ? 'Reanudar el avance automático' : 'Pausar el avance automático');
}

function mostrar(indice, avisar) {
  actual = (indice + slides.length) % slides.length;
  slides.forEach((slide, i) => { slide.hidden = i !== actual; });
  Array.from(puntos.children).forEach((punto, i) => {
    if (i === actual) punto.setAttribute('aria-current', 'true');
    else punto.removeAttribute('aria-current');
  });
  contador.textContent = (actual + 1) + ' / ' + slides.length;
  // Solo se anuncia a lectores de pantalla cuando la persona cambia de diapositiva
  ventana.setAttribute('aria-live', avisar ? 'polite' : 'off');
  programar();
}

// Si la persona elige una diapositiva, el avance automático se detiene
function irPorUsuario(indice) {
  detenido = true;
  actualizarBotonPausa();
  mostrar(indice, true);
}

carrusel.querySelector('.flecha-ant').addEventListener('click', () => irPorUsuario(actual - 1));
carrusel.querySelector('.flecha-sig').addEventListener('click', () => irPorUsuario(actual + 1));

botonPausa.addEventListener('click', () => {
  detenido = !detenido;
  actualizarBotonPausa();
  programar();
});

carrusel.addEventListener('keydown', (e) => {
  if (e.key === 'ArrowLeft') irPorUsuario(actual - 1);
  if (e.key === 'ArrowRight') irPorUsuario(actual + 1);
});

// Pausa temporal: mouse encima, foco del teclado dentro o pestaña oculta
carrusel.addEventListener('pointerenter', (e) => { if (e.pointerType === 'mouse') { enPausa = true; programar(); } });
carrusel.addEventListener('pointerleave', (e) => { if (e.pointerType === 'mouse') { enPausa = false; programar(); } });
carrusel.addEventListener('focusin', () => { enPausa = true; programar(); });
carrusel.addEventListener('focusout', (e) => {
  if (!carrusel.contains(e.relatedTarget)) { enPausa = false; programar(); }
});
document.addEventListener('visibilitychange', () => { enPausa = document.hidden; programar(); });

// Deslizar con el dedo en celulares
let inicioX = null;
ventana.addEventListener('touchstart', (e) => { inicioX = e.touches[0].clientX; }, { passive: true });
ventana.addEventListener('touchend', (e) => {
  if (inicioX === null) return;
  const dx = e.changedTouches[0].clientX - inicioX;
  if (Math.abs(dx) > 50) irPorUsuario(actual + (dx < 0 ? 1 : -1));
  inicioX = null;
});

// Con "reducir movimiento" activado no hay avance automático ni botón de pausa
if (reducirMovimiento) botonPausa.hidden = true;
actualizarBotonPausa();
mostrar(0, false);

// ---------- Formulario de contacto ----------
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
