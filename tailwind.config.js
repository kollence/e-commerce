import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                'gradient-xy': {
                  '0%, 100%': {
                    backgroundSize: '200% 200%',
                    backgroundPosition: 'left top',
                  },
                  '50%': {
                    backgroundSize: '200% 200%',
                    backgroundPosition: 'right bottom',
                  },
                },
              },
              animation: {
                'gradient-xy': 'gradient-xy 5s ease infinite',
              },
        },
    },

    plugins: [forms, typography],
};
