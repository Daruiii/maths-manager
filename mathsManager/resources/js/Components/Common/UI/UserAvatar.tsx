import { User } from '@/types';

interface UserAvatarProps {
  user?: User | null;
  src?: string; // Direct source override
  className?: string;
  alt?: string;
  size?: 'sm' | 'md' | 'lg' | 'xl' | '2xl'; // Extended sizes
}

export default function UserAvatar({
  user,
  src,
  className = '',
  alt,
  size = 'md',
}: UserAvatarProps) {
  // Determine avatar URL source
  const avatarUrl =
    src ||
    (user?.avatar
      ? user.avatar.startsWith('http')
        ? user.avatar
        : `/storage/images/${user.avatar}`
      : '/storage/images/default.jpg');

  // Determine alt text
  const altText = alt || (user ? `${user.first_name} ${user.last_name}` : 'Avatar');

  // Base size classes (can be overridden by className)
  const sizeClasses = {
    sm: 'w-8 h-8',
    md: 'w-10 h-10',
    lg: 'w-12 h-12',
    xl: 'w-20 h-20',
    '2xl': 'w-32 h-32',
  };

  return (
    <img
      src={avatarUrl}
      alt={altText}
      referrerPolicy="no-referrer"
      onError={(e) => {
        const target = e.currentTarget;
        if (!target.src.endsWith('/storage/images/default.jpg')) {
          target.src = '/storage/images/default.jpg';
        }
      }}
      className={`rounded-full object-cover border border-border-color ${sizeClasses[size] || ''} ${className}`}
    />
  );
}
