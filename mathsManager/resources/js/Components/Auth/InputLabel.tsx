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
        `block font-comfortaa-bold text-sm text-text-gray dark:text-gray-300 uppercase tracking-wider ` + className
      }
    >
      {value ? value : children}
    </label>
  );
}
