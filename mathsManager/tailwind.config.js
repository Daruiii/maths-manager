import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.tsx',
    './resources/js/**/*.ts',
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

        // ── Content types (DS / DM / TD) — not role colors ────────────────
        'ds-color': 'rgb(var(--ds-color) / <alpha-value>)',
        'dm-color': 'rgb(var(--dm-color) / <alpha-value>)',
        'td-color': 'rgb(var(--td-color) / <alpha-value>)',
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
        xxs: ['0.625rem', { lineHeight: '0.875rem' }], // 10px — badges, labels
        xs: ['0.75rem', { lineHeight: '1rem' }], // 12px — texte secondaire
        sm: ['0.875rem', { lineHeight: '1.25rem' }], // 14px — texte de base
        base: ['1rem', { lineHeight: '1.5rem' }], // 16px
        lg: ['1.125rem', { lineHeight: '1.75rem' }], // 18px — titres de section
        xl: ['1.25rem', { lineHeight: '1.75rem' }], // 20px — titres principaux
        '2xl': ['1.5rem', { lineHeight: '2rem' }], // 24px
        '3xl': ['1.875rem', { lineHeight: '2.25rem' }], // 30px — grands titres
        '4xl': ['2.25rem', { lineHeight: '2.5rem' }], // 36px — hero titres
        '5xl': ['3rem', { lineHeight: '1.1' }], // 48px — display
      },
      boxShadow: {
        'warm-xs':
          '0 1px 3px rgb(var(--text-color) / 0.05), 0 1px 2px rgb(var(--text-color) / 0.04)',
        'warm-sm':
          '0 2px 8px rgb(var(--text-color) / 0.07), 0 1px 3px rgb(var(--text-color) / 0.04)',
        warm: '0 4px 16px rgb(var(--text-color) / 0.08), 0 2px 6px rgb(var(--text-color) / 0.05)',
        'warm-md':
          '0 8px 24px rgb(var(--text-color) / 0.10), 0 4px 8px rgb(var(--text-color) / 0.06)',
        'warm-lg':
          '0 16px 48px rgb(var(--text-color) / 0.12), 0 8px 16px rgb(var(--text-color) / 0.06)',
        focus: '0 0 0 3px rgb(var(--tertiary-color) / 0.28)',
      },
      transitionDuration: {
        50: '50ms',
        75: '75ms',
        400: '400ms',
        600: '600ms',
      },
      keyframes: {
        slideInRight: {
          '0%': { opacity: '0', transform: 'translateX(12px) scale(0.95)' },
          '100%': { opacity: '1', transform: 'translateX(0) scale(1)' },
        },
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(8px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        scaleIn: {
          '0%': { opacity: '0', transform: 'scale(0.95)' },
          '100%': { opacity: '1', transform: 'scale(1)' },
        },
        shimmer: {
          '0%': { backgroundPosition: '-200% 0' },
          '100%': { backgroundPosition: '200% 0' },
        },
      },
      animation: {
        slideInRight: 'slideInRight 0.18s ease both',
        fadeIn: 'fadeIn 0.2s ease-out both',
        fadeInUp: 'fadeInUp 0.25s ease-out both',
        scaleIn: 'scaleIn 0.15s ease-out both',
        shimmer: 'shimmer 1.5s linear infinite',
      },
    },
  },

  plugins: [forms],
};
