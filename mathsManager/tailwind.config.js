import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.tsx',
  ],

  darkMode: 'class',

  theme: {
    extend: {
      colors: {
        'primary-color': 'var(--primary-color)',
        'secondary-color': 'var(--secondary-color)',
        'tertiary-color': 'rgb(var(--tertiary-color) / <alpha-value>)',

        'text-color': 'var(--text-color)',
        'text-gray': 'var(--text-gray)',
        'success-color': 'rgb(var(--success-color) / <alpha-value>)',
        'error-color': 'rgb(var(--error-color) / <alpha-value>)',
        'warning-color': 'rgb(var(--warning-color) / <alpha-value>)',
        'info-color': 'rgb(var(--info-color) / <alpha-value>)',
        'admin-color': 'rgb(var(--admin-color) / <alpha-value>)',
        'teacher-color': 'rgb(var(--teacher-color) / <alpha-value>)',
        'student-color': 'rgb(var(--student-color) / <alpha-value>)',
      },
      fontFamily: {
        sans: ['Comfortaa', ...defaultTheme.fontFamily.sans],
        comfortaa: ['var(--font-comfortaa)'],
        'comfortaa-bold': ['var(--font-comfortaa-bold)'],
        'comfortaa-light': ['var(--font-comfortaa-light)'],
        'cmu-serif': ['var(--font-cmu-serif)'],
        'cmu-bold': ['var(--font-cmu-bold)'],
      },
      screens: {
        xs: '480px',
      },
    },
  },

  plugins: [forms],
};
