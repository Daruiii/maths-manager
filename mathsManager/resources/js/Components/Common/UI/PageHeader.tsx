import { Link } from '@inertiajs/react';
import { Home } from 'lucide-react';

interface Breadcrumb {
  label: string;
  href?: string;
}

interface PageHeaderProps {
  title: string;
  subtitle?: string;
  breadcrumbs?: Breadcrumb[];
  action?: React.ReactNode;
  className?: string;
}

export default function PageHeader({
  title,
  subtitle,
  breadcrumbs = [],
  action,
  className = '',
}: PageHeaderProps) {
  return (
    <div
      className={`mb-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full ${className}`}
    >
      <div className="flex flex-col">
        {/* Breadcrumbs */}
        <nav className="flex items-center gap-1 text-xs text-text-gray mb-2.5 font-comfortaa">
          <Link
            href={route('home')}
            className="hover:text-tertiary-color transition-colors flex items-center gap-1 mm-focus-ring rounded"
          >
            <Home className="w-3 h-3" />
            <span className="hidden sm:inline">Accueil</span>
          </Link>

          {breadcrumbs.length > 0 &&
            breadcrumbs.map((crumb, index) => (
              <div key={index} className="flex items-center gap-1">
                <span className="text-border-color/80 select-none">/</span>
                {crumb.href ? (
                  <Link
                    href={crumb.href}
                    className="hover:text-tertiary-color transition-colors mm-focus-ring rounded"
                  >
                    {crumb.label}
                  </Link>
                ) : (
                  <span className="text-text-color/70 font-comfortaa-bold">{crumb.label}</span>
                )}
              </div>
            ))}
        </nav>

        {/* Title & Subtitle */}
        <div className="flex items-start">
          {/* Gradient decorative bar */}
          <div className="hidden sm:block w-0.5 h-8 mr-3 mt-0.5 shrink-0 rounded-full bg-gradient-to-b from-tertiary-color via-tertiary-color/50 to-transparent" />

          <div>
            <h1 className="text-xl font-comfortaa-bold text-text-color leading-tight tracking-tight">
              {title}
            </h1>
            {subtitle && (
              <p className="mt-1 text-text-gray font-comfortaa text-sm leading-snug">{subtitle}</p>
            )}
          </div>
        </div>
      </div>

      {/* Action Element (Optional right-side button) */}
      {action && (
        <div className="flex-shrink-0 overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">{action}</div>
      )}
    </div>
  );
}
