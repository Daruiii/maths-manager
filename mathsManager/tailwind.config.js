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
        // ── Backgrounds ──────────────────────────────────────────────────
        'primary-color': 'rgb(var(--primary-color) / <alpha-value>)',
        'secondary-color': 'rgb(var(--secondary-color) / <alpha-value>)',
        'surface-color': 'rgb(var(--surface-color) / <alpha-value>)',

        // ── Text ──────────────────────────────────────────────────────────
        'text-color': 'rgb(var(--text-color) / <alpha-value>)',
        'text-gray': 'rgb(var(--text-gray) / <alpha-value>)',

        // ── Borders ───────────────────────────────────────────────────────
        'border-color': 'rgb(var(--border-color) / <alpha-value>)',

        // ── Brand & Interactive ───────────────────────────────────────────
        'tertiary-color': 'rgb(var(--tertiary-color) / <alpha-value>)',

        // ── Status ────────────────────────────────────────────────────────
        'success-color': 'rgb(var(--success-color) / <alpha-value>)',
        'error-color': 'rgb(var(--error-color) / <alpha-value>)',
        'warning-color': 'rgb(var(--warning-color) / <alpha-value>)',
        'info-color': 'rgb(var(--info-color) / <alpha-value>)',

        // ── Roles ─────────────────────────────────────────────────────────
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
        'cmu-italic': ['var(--font-cmu-italic)'],
        'cmu-tt': ['var(--font-cmu-tt)'],
      },
      screens: {
        xs: '480px',
        sm: '640px',
        md: '768px',
        lg: '1024px',
        xl: '1280px',
        '2xl': '1536px',
      },
      fontSize: {
        xxxs: ['0.5rem', { lineHeight: '0.75rem' }], // 8px — très petits badges
        xxs: ['0.625rem', { lineHeight: '0.875rem' }], // 10px — badges, labels (text-xxs)
        xs: ['0.75rem', { lineHeight: '1rem' }], // 12px — texte secondaire, légendes
        sm: ['0.875rem', { lineHeight: '1.25rem' }], // 14px — texte de base (text-sm)
        base: ['1rem', { lineHeight: '1.5rem' }], // 16px — texte de base
        lg: ['1.125rem', { lineHeight: '1.75rem' }], // 18px — titres de section
        xl: ['1.25rem', { lineHeight: '1.75rem' }], // 20px — titres principaux
        '2xl': ['1.5rem', { lineHeight: '2rem' }], // 24px — titres principaux
      },
    },
  },

  plugins: [forms],
};
