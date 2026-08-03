import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                grape: {
                    50:  '#f2f7ed',
                    100: '#e2efd8',
                    200: '#c4dfb1',
                    300: '#9dc87f',
                    400: '#74ad50',
                    500: '#528f32',  // main grape-leaf green
                    600: '#407226',
                    700: '#30531c',
                    800: '#213913',
                    900: '#13210b',
                    950: '#091205',
                },
            },
        },
    },

    plugins: [forms],
};
