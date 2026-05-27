const SYMBOLS = ['√', 'Δ', 'π', 'Σ', '∫', '∞', '∂', 'ℝ'];

const ROLE_PALETTE: Record<string, string> = {
  student: 'bg-student-color/15 text-student-color',
  teacher: 'bg-teacher-color/15 text-teacher-color',
  admin: 'bg-admin-color/15 text-admin-color',
};

const FALLBACK_PALETTE = [
  'bg-tertiary-color/15 text-tertiary-color',
  'bg-ds-color/15 text-ds-color',
  'bg-dm-color/15 text-dm-color',
];

const SIZE_CLASSES: Record<string, string> = {
  sm: 'h-7 w-7 text-sm',
  md: 'h-9 w-9 text-base',
  lg: 'h-12 w-12 text-lg',
  xl: 'h-20 w-20 text-3xl',
  '2xl': 'h-32 w-32 text-5xl',
};

interface Props {
  name: string;
  role?: 'student' | 'teacher' | 'admin';
  size?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
  className?: string;
}

export default function MathAvatar({ name, role, size = 'sm', className = '' }: Props) {
  const idx = name.charCodeAt(0) % SYMBOLS.length;
  const palette = role
    ? (ROLE_PALETTE[role] ?? FALLBACK_PALETTE[idx % FALLBACK_PALETTE.length])
    : FALLBACK_PALETTE[idx % FALLBACK_PALETTE.length];
  const symbol = SYMBOLS[idx];

  return (
    <span
      className={`${SIZE_CLASSES[size] ?? SIZE_CLASSES.sm} ${palette} flex shrink-0 items-center justify-center rounded-full font-cmu-serif ${className}`}
      aria-hidden
    >
      {symbol}
    </span>
  );
}
