<?php
/**
 * Usuarios de la intranet.
 *
 * Para agregar a alguien:
 * 1) Genera el hash de su contraseña (en cualquier computador con PHP):
 *      php -r "echo password_hash('LaContraseñaElegida', PASSWORD_DEFAULT);"
 * 2) Agrega una línea abajo con su correo, su nombre y el hash.
 *
 * Nunca escribas la contraseña directamente aquí: solo el hash.
 * Mientras la lista esté vacía, nadie puede ingresar.
 */
return [
    'usuarios' => [
        // 'correo@tudominio.cl' => ['nombre' => 'Gabriela', 'hash' => '$2y$10$...pega_aqui_el_hash...'],
    ],
];
