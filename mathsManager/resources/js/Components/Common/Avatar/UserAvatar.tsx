import { useState } from 'react';
import MathAvatar from '@/Components/Common/Avatar/MathAvatar';

type AvatarUser = {
  first_name: string;
  last_name?: string;
  avatar?: string | null;
  role?: 'admin' | 'teacher' | 'student';
};

interface UserAvatarProps {
  user?: AvatarUser | null;
  src?: string;
  className?: string;
  alt?: string;
  size?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
}

const SIZE_CLASSES: Record<string, string> = {
  sm: 'w-8 h-8',
  md: 'w-10 h-10',
  lg: 'w-12 h-12',
  xl: 'w-20 h-20',
  '2xl': 'w-32 h-32',
};

export default function UserAvatar({
  user,
  src,
  className = '',
  alt,
  size = 'md',
}: UserAvatarProps) {
  const [imgError, setImgError] = useState(false);

  const resolveAvatar = (path: string) =>
    path.startsWith('http') || path.startsWith('/') ? path : `/storage/images/${path}`;

  const hasRealAvatar = (path?: string | null) => !!path && path !== 'default.jpg';
  const rawUrl = src
    ? resolveAvatar(src)
    : hasRealAvatar(user?.avatar)
      ? resolveAvatar(user!.avatar!)
      : null;

  const displayName = user?.first_name ?? alt ?? '?';

  if (!rawUrl || imgError) {
    return <MathAvatar name={displayName} role={user?.role} size={size} className={className} />;
  }

  const altText = alt ?? (user ? `${user.first_name} ${user.last_name ?? ''}`.trim() : 'Avatar');

  return (
    <img
      src={rawUrl}
      alt={altText}
      referrerPolicy="no-referrer"
      onError={() => setImgError(true)}
      className={`rounded-full object-cover border border-border-color ${SIZE_CLASSES[size] ?? ''} ${className}`}
    />
  );
}
