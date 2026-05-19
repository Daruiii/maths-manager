import { ButtonHTMLAttributes, ReactNode } from 'react';
import { Loader2, LucideIcon } from 'lucide-react';
import { BUTTON_BASE_STYLES, BUTTON_VARIANTS, BUTTON_SIZES } from '@/Constants/ui';

type ButtonVariant =
  | 'primary'
  | 'secondary'
  | 'danger'
  | 'success'
  | 'ghost'
  | 'teacher'
  | 'student';
type ButtonSize = 'sm' | 'md' | 'lg';

interface ButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: ButtonVariant;
  size?: ButtonSize;
  isLoading?: boolean;
  icon?: LucideIcon;
  iconSize?: number;
  children?: ReactNode;
  className?: string;
}

export default function Button({
  variant = 'primary',
  size = 'md',
  isLoading = false,
  icon: Icon,
  iconSize = 16,
  className = '',
  disabled,
  children,
  ...props
}: ButtonProps) {
  return (
    <button
      {...props}
      disabled={disabled || isLoading}
      className={`${BUTTON_BASE_STYLES} ${BUTTON_VARIANTS[variant]} ${BUTTON_SIZES[size]} ${children ? 'gap-2' : ''} ${className}`}
    >
      {isLoading && <Loader2 className="animate-spin h-4 w-4 text-current" />}
      {!isLoading && Icon && <Icon size={iconSize} className="flex-shrink-0" />}
      {children}
    </button>
  );
}
