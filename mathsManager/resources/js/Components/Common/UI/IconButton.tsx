import { ButtonHTMLAttributes } from 'react';
import { LucideIcon } from 'lucide-react';

type AccentColor = 'teacher' | 'student' | 'admin' | 'error' | 'default';
type Variant = 'ghost' | 'bordered';
type Size = 'sm' | 'md';

interface Props extends ButtonHTMLAttributes<HTMLButtonElement> {
  icon: LucideIcon;
  iconSize?: number;
  isActive?: boolean;
  accentColor?: AccentColor;
  variant?: Variant;
  size?: Size;
}

const accentMap: Record<AccentColor, { hover: string; active: string }> = {
  teacher: {
    hover: 'hover:text-teacher-color hover:bg-teacher-color/10',
    active: 'text-teacher-color bg-teacher-color/10 border-teacher-color',
  },
  student: {
    hover: 'hover:text-student-color hover:bg-student-color/10',
    active: 'text-student-color bg-student-color/10 border-student-color',
  },
  admin: {
    hover: 'hover:text-admin-color hover:bg-admin-color/10',
    active: 'text-admin-color bg-admin-color/10 border-admin-color',
  },
  error: {
    hover: 'hover:text-error-color hover:bg-error-color/10',
    active: 'text-error-color bg-error-color/10 border-error-color',
  },
  default: {
    hover: 'hover:text-text-color hover:bg-surface-color',
    active: 'text-text-color bg-surface-color',
  },
};

export default function IconButton({
  icon: Icon,
  iconSize = 14,
  isActive = false,
  accentColor = 'default',
  variant = 'ghost',
  size = 'sm',
  className = '',
  ...props
}: Props) {
  const accent = accentMap[accentColor];
  const sizeClass = size === 'md' ? 'h-10 w-10' : 'h-8 w-8';

  const base =
    variant === 'bordered'
      ? `inline-flex items-center justify-center ${sizeClass} rounded-lg border transition-colors shrink-0 ${
          isActive ? accent.active : `border-border-color text-text-gray ${accent.hover}`
        }`
      : `p-1 rounded-lg transition-colors ${
          isActive ? accent.active : `text-text-gray ${accent.hover}`
        }`;

  return (
    <button type="button" className={`${base} ${className}`} {...props}>
      <Icon size={iconSize} />
    </button>
  );
}
