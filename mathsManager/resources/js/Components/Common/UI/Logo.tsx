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
    sm: 'text-xxs px-1 py-0.5',
    md: 'text-xxs sm:text-xs px-1.5 sm:px-2 py-0.5',
    lg: 'text-xs px-2 py-1',
    xl: 'text-sm px-2.5 py-1',
  };

  const isLocal = ['local', 'dev', 'develop', 'development'].includes(appEnv?.toLowerCase() || '');
  const isPreprod = appEnv?.toLowerCase() === 'preprod';
  const showEnvBadge = showBadge && (isLocal || isPreprod);

  return (
    <div className={`inline-flex items-center gap-2 ${className}`}>
      <span
        className={`${sizeClasses[size]} font-comfortaa-bold text-text-color tracking-tight whitespace-nowrap`}
      >
        {appName || 'Maths Manager'}
      </span>
      {showEnvBadge && (
        <span
          className={[
            badgeSizeClasses[size],
            'rounded font-comfortaa-bold uppercase tracking-widest whitespace-nowrap',
            isLocal ? 'bg-success-color/15 text-success-color' : 'bg-orange-500/15 text-orange-500',
          ].join(' ')}
        >
          {isLocal ? 'local' : 'preprod'}
        </span>
      )}
    </div>
  );
}
