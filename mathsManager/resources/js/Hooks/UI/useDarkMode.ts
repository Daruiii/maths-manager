import { useEffect, useState } from 'react';

/**
 * Custom hook to manage dark mode with localStorage persistence
 *
 * @returns {Object} Dark mode state and toggle function
 * @returns {boolean} isDark - Current dark mode state
 * @returns {function} toggle - Function to toggle dark mode
 *
 * @example
 * ```tsx
 * const { isDark, toggle } = useDarkMode();
 *
 * return (
 *   <button onClick={toggle}>
 *     {isDark ? '🌙' : '☀️'}
 *   </button>
 * );
 * ```
 */
export const useDarkMode = () => {
  const [isDark, setIsDark] = useState<boolean>(() => {
    if (typeof window === 'undefined') return false;

    const stored = localStorage.getItem('theme');
    if (stored) {
      return stored === 'dark';
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches;
  });

  useEffect(() => {
    if (typeof window === 'undefined') return;

    const root = document.documentElement;

    if (isDark) {
      root.classList.add('dark');
      localStorage.setItem('theme', 'dark');
    } else {
      root.classList.remove('dark');
      localStorage.setItem('theme', 'light');
    }
  }, [isDark]);

  const toggle = () => setIsDark(!isDark);

  return { isDark, toggle };
};
