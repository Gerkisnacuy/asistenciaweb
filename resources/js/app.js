import './bootstrap';

import Alpine from 'alpinejs';

// Esto ayuda a que Alpine sea reconocido en todo el proyecto
window.Alpine = Alpine;

// OPCIONAL: Descomenta la línea de abajo para ver errores de Alpine en la consola (F12)
// Alpine.setDebug(true);

Alpine.start();

console.log('Alpine.js se ha cargado correctamente');