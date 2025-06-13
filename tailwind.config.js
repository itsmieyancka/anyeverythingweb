import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        // Laravel pagination and compiled view stubs (already present)
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',

        // Your own Blade views:
        './resources/views/**/*.blade.php',

        // Filament admin panel views (make sure to pick up any Filament‐published views)
        './vendor/filament/**/*.blade.php',
        './app/Filament/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,            // if you want Tailwind Forms support (optional)
        require('daisyui'), // DaisyUI must be here
    ],

    daisyui: {
        themes: ['light', 'dark'], // or list whatever DaisyUI themes you need
    },
};



