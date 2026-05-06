import type { ReactNode } from 'react';

interface Props {
  children: ReactNode;
  accent?: 'student' | 'teacher' | 'admin' | 'tertiary';
  dotted?: boolean;
  lined?: boolean;
  styleVariant?: 'plain' | 'line' | 'halo' | 'corner' | 'topbar';
  className?: string;
}

const ACCENT_HALO: Record<NonNullable<Props['accent']>, string> = {
  student: 'mm-card-accent-student',
  teacher: 'mm-card-accent-teacher',
  admin: 'mm-card-accent-admin',
  tertiary: 'mm-card-accent-tertiary',
};

export default function TheoremCard({
  children,
  accent = 'student',
  dotted = false,
  lined = false,
  styleVariant = 'halo',
  className = '',
}: Props) {
  const pattern = dotted ? 'card-dot-grid' : lined ? 'card-lines' : '';

  return (
    <div
      className={`mm-card ${ACCENT_HALO[accent]} mm-card-style-${styleVariant} ${pattern} p-4 ${className}`}
    >
      {children}
    </div>
  );
}
