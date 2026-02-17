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
  className?: string;
}

export default function PageHeader({
  title,
  subtitle,
  breadcrumbs = [],
  className = '',
}: PageHeaderProps) {
  return (
    <div className={`mb-8 ${className}`}>
      {/* Breadcrumbs */}
      <nav className="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2 font-comfortaa">
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
              <ChevronRight className="w-4 h-4 mx-1 text-gray-400" />
              {crumb.href ? (
                <Link href={crumb.href} className="hover:text-tertiary-color transition-colors">
                  {crumb.label}
                </Link>
              ) : (
                <span className="text-gray-900 dark:text-gray-200 font-comfortaa-bold">
                  {crumb.label}
                </span>
              )}
            </div>
          ))}
      </nav>

      {/* Title & Subtitle */}
      <div className="flex items-start">
        {/* Decorative bar - smaller */}
        <div className="hidden sm:block w-1 h-6 bg-tertiary-color rounded-full mr-3 mt-1.5 opacity-80 shadow-sm"></div>

        <div>
          <h1 className="text-xl font-comfortaa-bold text-gray-800 dark:text-gray-100 leading-tight">
            {title}
          </h1>
          {subtitle && (
            <p className="mt-0.5 text-text-gray dark:text-gray-400 font-comfortaa text-sm">
              {subtitle}
            </p>
          )}
        </div>
      </div>
    </div>
  );
}
