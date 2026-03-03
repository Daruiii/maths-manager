import { Link } from '@inertiajs/react';
import { User } from '@/types/models';
import UserAvatar from '@/Components/Common/UI/UserAvatar';
import { ReactNode } from 'react';

type AccentColor = 'student' | 'teacher' | 'admin';
type CardVariant = 'default' | 'dot-grid' | 'lines';

interface Props {
  user: User;
  href?: string;
  accentColor?: AccentColor;
  variant?: CardVariant;
  hoverAction?: ReactNode;
  children?: ReactNode;
}

const accentStyles: Record<AccentColor, { borderLeft: string; hoverBg: string }> = {
  student: {
    borderLeft: 'border-l-student-color',
    hoverBg: 'hover:bg-student-color/5',
  },
  teacher: {
    borderLeft: 'border-l-teacher-color',
    hoverBg: 'hover:bg-teacher-color/5',
  },
  admin: {
    borderLeft: 'border-l-admin-color',
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
      className={`card-theorem relative ${accent.borderLeft} ${variantStyles[variant]} p-4 flex flex-col items-center gap-3 group ${accent.hoverBg}`}
    >
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
