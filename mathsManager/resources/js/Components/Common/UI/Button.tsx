import { ButtonHTMLAttributes, ReactNode } from 'react';
import { Loader2 } from 'lucide-react';

type ButtonVariant = 'primary' | 'secondary' | 'danger' | 'ghost';
type ButtonSize = 'sm' | 'md' | 'lg';

interface ButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: ButtonVariant;
  size?: ButtonSize;
  isLoading?: boolean;
  children: ReactNode;
  className?: string;
}

export default function Button({
  variant = 'primary',
  size = 'md',
  isLoading = false,
  className = '',
  disabled,
  children,
  ...props
}: ButtonProps) {
  const baseStyles =
    'inline-flex items-center justify-center rounded-xl font-comfortaa-bold uppercase tracking-widest transition-all duration-75 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed transform active:translate-y-1 active:shadow-none focus:outline-none';

  const variants = {
    primary:
      'bg-tertiary-color text-white shadow-[0_4px_0_0_rgba(0,0,0,0.2)] hover:brightness-110 active:brightness-100',
    secondary:
      'bg-white dark:bg-gray-800 text-text-gray dark:text-gray-300 border-2 border-gray-200 dark:border-gray-600 shadow-[0_4px_0_0_rgba(0,0,0,0.1)] hover:bg-gray-50 dark:hover:bg-gray-700',
    danger:
      'bg-error-color text-white shadow-[0_4px_0_0_rgba(0,0,0,0.2)] hover:brightness-110 active:brightness-100',
    ghost:
      'bg-transparent text-text-gray hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 shadow-none active:translate-y-0',
  };

  const sizes = {
    sm: 'px-4 py-2 text-xs',
    md: 'px-6 py-3 text-sm',
    lg: 'px-8 py-4 text-base',
  };

  return (
    <button
      {...props}
      disabled={disabled || isLoading}
      className={`${baseStyles} ${variants[variant]} ${sizes[size]} ${className}`}
    >
      {isLoading && <Loader2 className="animate-spin -ml-1 mr-3 h-4 w-4 text-current" />}
      {children}
    </button>
  );
}
