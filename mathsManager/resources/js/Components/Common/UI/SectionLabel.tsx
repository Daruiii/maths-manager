import type { ReactNode } from 'react';
import type { LucideIcon } from 'lucide-react';

interface Props {
  children: ReactNode;
  icon?: LucideIcon;
  accent?: boolean;
}

export default function SectionLabel({ children, icon: Icon, accent = false }: Props) {
  const textClass = accent ? 'text-tertiary-color' : 'text-text-gray';

  return (
    <div className="flex items-center gap-1.5">
      {Icon && <Icon size={11} className={textClass} strokeWidth={2.5} />}
      <p className={`text-xs font-comfortaa-bold uppercase tracking-wide ${textClass}`}>
        {children}
      </p>
    </div>
  );
}
