import { ReactNode } from 'react';

interface CardProps {
  children: ReactNode;
  className?: string;
  title?: string;
  icon?: ReactNode;
  headerAction?: ReactNode;
  variant?: 'default' | 'danger' | 'teacher' | 'student' | 'admin';
}

export default function Card({
  children,
  className = '',
  title,
  icon,
  headerAction,
  variant = 'default',
}: CardProps) {
  const getVariantClasses = () => {
    switch (variant) {
      case 'teacher':
        return { bg: 'bg-teacher-color' };
      case 'student':
        return { bg: 'bg-student-color' };
      case 'admin':
        return { bg: 'bg-admin-color' };
      case 'danger':
        return { bg: 'bg-error-color' };
      default:
        return { bg: 'bg-tertiary-color' };
    }
  };

  const { bg } = getVariantClasses();

  return (
    <div className={`relative bg-secondary-color rounded-2xl shadow-sm ${className} mt-4 pt-6`}>
      {(title || headerAction) && (
        <div className="absolute -top-5 left-4 flex items-center z-10">
          {/* Icon Circle */}
          {icon && (
            <div
              className={`mr-[-12px] flex items-center justify-center w-10 h-10 rounded-full shadow-md border-4 border-secondary-color text-white z-20 ${bg}`}
            >
              {icon}
            </div>
          )}

          {/* Title Tab */}
          {title && (
            <div
              className={`px-6 py-2 rounded-2xl shadow-md font-comfortaa-bold text-white text-sm tracking-wide z-10 pl-5 ${bg}`}
            >
              {title}
            </div>
          )}

          {/* Header Action */}
          {headerAction && (
            <div className="ml-auto bg-secondary-color rounded-full shadow-sm px-3 py-1">
              {headerAction}
            </div>
          )}
        </div>
      )}

      <div className={`p-6 ${variant === 'danger' ? 'bg-error-color/5 rounded-2xl' : ''}`}>
        {children}
      </div>
    </div>
  );
}
