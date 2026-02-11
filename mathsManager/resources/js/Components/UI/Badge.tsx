import { ReactNode } from 'react';

interface BadgeProps {
  children: ReactNode;
  variant?: 'success' | 'error' | 'warning' | 'info' | 'student' | 'teacher' | 'admin';
  className?: string;
}

export default function Badge({ children, variant = 'info', className = '' }: BadgeProps) {
  const variantClasses = {
    success: 'bg-success-color text-white',
    error: 'bg-error-color text-white',
    warning: 'bg-orange-500 text-white',
    info: 'bg-blue-500 text-white',
    student: 'bg-student-color text-white',
    teacher: 'bg-teacher-color text-white',
    admin: 'bg-admin-color text-white',
  };

  return (
    <span className={`text-xs font-semibold px-2 py-1 rounded-full ${variantClasses[variant]} ${className}`}>
      {children}
    </span>
  );
}
