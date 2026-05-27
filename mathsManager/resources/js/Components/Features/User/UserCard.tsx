import { Link } from '@inertiajs/react';
import { User } from '@/types/models';
import UserAvatar from '@/Components/Common/Avatar/UserAvatar';
import { ReactNode } from 'react';

type AccentColor = 'student' | 'teacher' | 'admin';
type CardVariant = 'default' | 'dot-grid' | 'lines';

interface Props {
  user: User;
  href?: string;
  accentColor?: AccentColor;
  variant?: CardVariant;
  topLeftContent?: ReactNode;
  hoverAction?: ReactNode;
  children?: ReactNode;
}

const accentStyles: Record<AccentColor, { accent: string; hoverBg: string }> = {
  student: {
    accent: 'mm-card-accent-student',
    hoverBg: 'hover:bg-student-color/5',
  },
  teacher: {
    accent: 'mm-card-accent-teacher',
    hoverBg: 'hover:bg-teacher-color/5',
  },
  admin: {
    accent: 'mm-card-accent-admin',
    hoverBg: 'hover:bg-admin-color/5',
  },
};

const variantStyles: Record<CardVariant, string> = {
  default: '',
  'dot-grid': 'card-dot-grid',
  lines: 'card-lines',
};

export default function UserCard({
  user,
  href,
  accentColor = 'student',
  variant = 'default',
  topLeftContent,
  hoverAction,
  children,
}: Props) {
  const accent = accentStyles[accentColor];

  const content = (
    <>
      <UserAvatar user={user} size="lg" />
      <div className="text-center font-comfortaa">
        <p className="text-sm font-comfortaa-bold text-text-color leading-tight">
          {user.first_name}
        </p>
        <p className="text-sm font-comfortaa-bold text-text-color leading-tight">
          {user.last_name}
        </p>
      </div>
    </>
  );

  return (
    <div
      className={`mm-card ${accent.accent} mm-card-style-halo ${variantStyles[variant]} p-4 flex flex-col items-center gap-3 group ${accent.hoverBg}`}
    >
      {topLeftContent && <div className="absolute top-2 left-2">{topLeftContent}</div>}
      {hoverAction && <div className="absolute top-2 right-2 flex">{hoverAction}</div>}

      {href ? (
        <Link href={href} className="flex flex-col items-center gap-3 w-full">
          {content}
        </Link>
      ) : (
        <div className="flex flex-col items-center gap-3 w-full">{content}</div>
      )}

      {children}
    </div>
  );
}
