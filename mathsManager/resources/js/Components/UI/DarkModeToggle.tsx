import { useDarkMode } from '@/Hooks/useDarkMode';
import { Sun, Moon } from 'lucide-react';

/**
 * Dark mode toggle button with sun/moon icons
 *
 * @component
 * @example
 * ```tsx
 * <DarkModeToggle />
 * ```
 */
export default function DarkModeToggle() {
  const { isDark, toggle } = useDarkMode();

  return (
    <button
      onClick={toggle}
      className="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200"
      aria-label={isDark ? 'Activer le mode clair' : 'Activer le mode sombre'}
    >
      {isDark ? (
        <Sun className="w-5 h-5 text-yellow-500" />
      ) : (
        <Moon className="w-5 h-5 text-gray-700 dark:text-gray-300" />
      )}
    </button>
  );
}
