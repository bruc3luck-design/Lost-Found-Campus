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
                uin: {
                    50: '#E6F2EC',
                    500: '#0B5D3B',
                    700: '#08432C',
                },
                gold: {
                    100: '#F6EBD2',
                    500: '#B88830',
                },
                ink: '#12261C',
                paper: '#FAFAF6',
            },
        },
    },

    plugins: [forms],
};
