import { PageProps } from '@/types';
import { usePage } from '@inertiajs/react';

interface LogoProps {
  className?: string;
  size?: 'sm' | 'md' | 'lg' | 'xl';
  showBadge?: boolean;
}

/**
 * Application logo with environment badge (LOCAL/PREPROD)
 *
 * @component
 * @example
 * ```tsx
 * <Logo size="lg" showBadge={true} />
 * ```
 */
export default function Logo({ className = '', size = 'md', showBadge = true }: LogoProps) {
  const { appName, appEnv } = usePage<PageProps>().props;

  const sizeClasses = {
    sm: 'text-lg',
    md: 'text-2xl',
    lg: 'text-3xl',
    xl: 'text-4xl',
  };

  const badgeSizeClasses = {
    sm: 'text-[8px] px-1 py-0.5',
    md: 'text-[10px] sm:text-xs px-1.5 sm:px-2 py-0.5',
    lg: 'text-xs px-2 py-1',
    xl: 'text-sm px-2.5 py-1',
  };

  const isLocal = ['local', 'dev', 'develop', 'development'].includes(appEnv?.toLowerCase() || '');
  const isPreprod = appEnv?.toLowerCase() === 'preprod';
  const showEnvBadge = showBadge && (isLocal || isPreprod);

  return (
    <div className={`flex items-center gap-3 ${className}`}>
      <span
        className={`${sizeClasses[size]} font-comfortaa-bold text-text-color dark:text-gray-100 tracking-tight`}
      >
        {appName || 'Maths Manager'}
      </span>

      {showEnvBadge && (
        <span
          className={`hidden sm:inline-flex items-center justify-center font-bold rounded-full uppercase text-white shadow-sm ${badgeSizeClasses[size]} ${
            isLocal ? 'bg-success-color' : 'bg-orange-500'
          }`}
        >
          {isLocal ? 'LOCAL' : 'PREPROD'}
        </span>
      )}

      {/* Mobile: Simple colored dot */}
      {showEnvBadge && (
        <span
          className={`sm:hidden w-2 h-2 rounded-full ${
            isLocal ? 'bg-success-color' : 'bg-orange-500'
          }`}
          aria-label={isLocal ? 'Environnement local' : 'Environnement preprod'}
        />
      )}
    </div>
  );
}
