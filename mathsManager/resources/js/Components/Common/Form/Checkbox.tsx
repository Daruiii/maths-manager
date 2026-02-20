import { InputHTMLAttributes } from 'react';

/**
 * Styled checkbox input with consistent focus and hover states
 *
 * @component
 * @example
 * ```tsx
 * <Checkbox
 *   name="remember"
 *   checked={rememberMe}
 *   onChange={(e) => setRememberMe(e.target.checked)}
 * />
 * ```
 */
export default function Checkbox({
  className = '',
  ...props
}: InputHTMLAttributes<HTMLInputElement>) {
  return (
    <input
      {...props}
      type="checkbox"
      className={
        'rounded-md h-5 w-5 border-2 border-border-color bg-surface-color text-tertiary-color shadow-sm focus:ring-tertiary-color transition-all duration-200 cursor-pointer ' +
        className
      }
    />
  );
}
