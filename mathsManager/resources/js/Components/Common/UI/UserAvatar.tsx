import { User } from '@/types';

interface UserAvatarProps {
  user?: User | null;
  className?: string;
  alt?: string;
  size?: 'sm' | 'md' | 'lg' | 'xl' | '2xl'; // Extended sizes
}

export default function UserAvatar({ user, className = '', alt, size = 'md' }: UserAvatarProps) {
  // Determine avatar URL source
  const avatarUrl = user?.avatar
    ? user.avatar.startsWith('http')
      ? user.avatar
      : `/storage/images/${user.avatar}`
    : '/storage/images/default.jpg';

  // Determine alt text
  const altText = alt || user?.name || 'Avatar';

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
      className={`rounded-full object-cover border border-gray-200 dark:border-gray-700 ${sizeClasses[size] || ''} ${className}`}
    />
  );
}
