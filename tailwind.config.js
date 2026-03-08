import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // ACTIVAR MODO OSCURO POR CLASE
    darkMode: 'class', 

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js', // Añadido para asegurar que detecte JS
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // Añadimos los colores dinámicos como extensiones
            colors: {
                primary: 'var(--primary-color)',
                secondary: 'var(--secondary-color)',
                // FIX: Agregamos 'custom-primary' para que coincida con tus clases en Blade
                'custom-primary': 'var(--primary-color)', 
            },
        },
    },

    plugins: [forms],
};