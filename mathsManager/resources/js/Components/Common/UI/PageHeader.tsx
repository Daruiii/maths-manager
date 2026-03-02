import { Link } from '@inertiajs/react';
import { Home, ChevronRight } from 'lucide-react';

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
        <nav className="flex items-center text-sm text-text-gray mb-2 font-comfortaa">
          <Link
            href={route('home')}
            className="hover:text-tertiary-color transition-colors flex items-center gap-1"
          >
            <Home className="w-3.5 h-3.5" />
            <span className="hidden sm:inline">Accueil</span>
          </Link>

          {breadcrumbs.length > 0 &&
            breadcrumbs.map((crumb, index) => (
              <div key={index} className="flex items-center">
                <ChevronRight className="w-4 h-4 mx-1 text-text-gray" />
                {crumb.href ? (
                  <Link href={crumb.href} className="hover:text-tertiary-color transition-colors">
                    {crumb.label}
                  </Link>
                ) : (
                  <span className="text-text-color font-comfortaa-bold">{crumb.label}</span>
                )}
              </div>
            ))}
        </nav>

        {/* Title & Subtitle */}
        <div className="flex items-start">
          {/* Decorative bar - smaller */}
          <div className="hidden sm:block w-1 h-6 bg-tertiary-color rounded-full mr-3 mt-1.5 opacity-80 shadow-sm"></div>

          <div>
            <h1 className="text-xl font-comfortaa-bold text-text-color leading-tight">{title}</h1>
            {subtitle && <p className="mt-0.5 text-text-gray font-comfortaa text-sm">{subtitle}</p>}
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
