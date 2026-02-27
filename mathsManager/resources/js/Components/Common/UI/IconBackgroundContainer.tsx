import { ReactNode } from 'react';
import { LucideIcon } from 'lucide-react';

interface Props {
  icon: LucideIcon;
  iconClassName?: string;
  className?: string;
  children: ReactNode;
}

export default function IconBackgroundContainer({
  icon: Icon,
  iconClassName = 'text-teacher-color/5',
  className = '',
  children,
}: Props) {
  return (
    <div
      className={`relative bg-surface-color/50 p-6 sm:p-8 rounded-[2rem] border border-border-color shadow-sm overflow-hidden ${className}`}
    >
      {/* Background Icon Decoration */}
      <Icon
        size={120}
        className={`absolute top-0 right-0 -translate-y-4 translate-x-4 -rotate-12 z-0 pointer-events-none ${iconClassName}`}
      />

      {/* Content */}
      <div className="relative z-10 w-full">{children}</div>
    </div>
  );
}
