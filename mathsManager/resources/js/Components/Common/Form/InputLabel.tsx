import { LabelHTMLAttributes } from 'react';

/**
 * Form label with consistent styling and uppercase text
 *
 * @component
 * @example
 * ```tsx
 * <InputLabel htmlFor="email" value="Email Address" />
 * ```
 */
export default function InputLabel({
  value,
  className = '',
  children,
  ...props
}: LabelHTMLAttributes<HTMLLabelElement> & { value?: string }) {
  return (
    <label
      {...props}
      className={
        `block font-comfortaa-bold text-sm text-gray-700 dark:text-gray-200 mb-2 ` + className
      }
    >
      {value ? value : children}
    </label>
  );
}
