import { LucideIcon } from 'lucide-react';
import { ReactNode } from 'react';

type StatusType = 'success' | 'error' | 'warning' | 'info' | 'neutral';

interface StatusCardProps {
  type?: StatusType;
  icon: LucideIcon;
  title: string;
  description?: ReactNode;
  children?: ReactNode;
  header?: ReactNode;
}

const typeStyles: Record<StatusType, string> = {
  success: 'bg-success-color/10 text-success-color',
  error: 'bg-error-color/10 text-error-color',
  warning: 'bg-warning-color/10 text-warning-color',
  info: 'bg-info-color/10 text-info-color',
  neutral: 'bg-surface-color text-text-gray',
};

export default function StatusCard({
  type = 'neutral',
  icon: Icon,
  title,
  description,
  children,
  header,
}: StatusCardProps) {
  return (
    <div className="space-y-4 text-center">
      {header}

      <div
        className={`w-16 h-16 rounded-2xl ${typeStyles[type]} flex items-center justify-center mx-auto`}
      >
        <Icon className="w-8 h-8" />
      </div>

      <h1 className="text-xl font-bold text-text-color">{title}</h1>

      {description && <p className="text-text-gray text-sm">{description}</p>}

      {children}
    </div>
  );
}
