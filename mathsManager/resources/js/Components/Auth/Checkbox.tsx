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
        'rounded border-gray-300 text-admin-color shadow-sm focus:ring-admin-color transition-all duration-200 ' +
        className
      }
    />
  );
}
