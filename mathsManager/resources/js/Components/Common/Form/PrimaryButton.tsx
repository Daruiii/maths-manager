import { ButtonHTMLAttributes } from 'react';

/**
 * Primary action button with consistent styling and disabled state support
 *
 * @component
 * @example
 * ```tsx
 * <PrimaryButton disabled={isLoading}>
 *   Submit Form
 * </PrimaryButton>
 * ```
 */
export default function PrimaryButton({
  className = '',
  disabled,
  children,
  ...props
}: ButtonHTMLAttributes<HTMLButtonElement>) {
  return (
    <button
      {...props}
      className={
        `inline-flex items-center px-6 py-3 bg-admin-color border border-transparent rounded-xl font-comfortaa-bold text-sm text-white uppercase tracking-widest hover:bg-admin-color/90 focus:bg-admin-color/90 active:bg-admin-color/90 focus:outline-none focus:ring-2 focus:ring-admin-color focus:ring-offset-2 transition ease-in-out duration-150 ${
          disabled && 'opacity-25'
        } ` + className
      }
      disabled={disabled}
    >
      {children}
    </button>
  );
}
