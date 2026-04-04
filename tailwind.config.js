import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                farm: {
                    50: '#f2f8f5',
                    100: '#e1efe7',
                    200: '#c3e0d0',
                    300: '#9ac9b2',
                    400: '#6bab8d',
                    500: '#4a8f6e',
                    600: '#387256',
                    700: '#2e5b47',
                    800: '#274a3b',
                    900: '#213d31',
                },
                earth: {
                    50: '#fcfaf8',
                    100: '#f8f4f0',
                    200: '#efe5da',
                    300: '#e1cfbf',
                    400: '#cbaea0',
                    500: '#b68e7d',
                    600: '#a37562',
                    700: '#875d4d',
                    800: '#714f44',
                    900: '#5c433b',
                }
            },
        },
    },

    plugins: [forms],
};
