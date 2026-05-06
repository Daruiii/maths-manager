import type { ReactNode } from 'react';

interface Props {
  children: ReactNode;
  accent?: 'student' | 'teacher' | 'admin' | 'tertiary';
  dotted?: boolean;
  lined?: boolean;
  className?: string;
}

const ACCENT_BORDER: Record<NonNullable<Props['accent']>, string> = {
  student: 'border-l-student-color',
  teacher: 'border-l-teacher-color',
  admin: 'border-l-admin-color',
  tertiary: 'border-l-tertiary-color',
};

export default function TheoremCard({
  children,
  accent = 'student',
  dotted = false,
  lined = false,
  className = '',
}: Props) {
  const pattern = dotted ? 'card-dot-grid' : lined ? 'card-lines' : '';

  return (
    <div className={`card-theorem ${ACCENT_BORDER[accent]} ${pattern} p-4 ${className}`}>
      {children}
    </div>
  );
}
