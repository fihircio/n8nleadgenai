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
    './resources/**/*.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './resources/**/*.jsx',
    './resources/**/*.tsx',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  plugins: [
    forms,
    typography,
    function ({ addUtilities }) {
      addUtilities({
        '.scroll-smooth': {
          scrollBehavior: 'smooth',
        },
      });
    },
  ],

  safelist: [
    { pattern: /^[\w:-]+$/ },
    // Add more patterns as needed
  ],

  // Remove the safelist or adjust it to include necessary patterns
  // safelist: [],
};
