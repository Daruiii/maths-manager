import { LucideIcon } from 'lucide-react';

interface Props {
  icon: LucideIcon;
  label: string;
  value?: string | null;
  className?: string;
}

export default function Bubble({ icon: Icon, label, value, className = '' }: Props) {
  if (!value) return null;

  return (
    <div
      className={`flex items-center gap-3 bg-surface-color px-4 py-2.5 rounded-xl border border-border-color ${className}`}
      title={value}
    >
      <div className="flex items-center justify-center text-tertiary-color flex-shrink-0">
        <Icon size={16} strokeWidth={2.5} />
      </div>
      <div className="min-w-0">
        <span className="text-[10px] font-bold uppercase tracking-wider text-text-gray block leading-tight">
          {label}
        </span>
        <span className="font-bold text-text-color text-xs truncate block capitalize">{value}</span>
      </div>
    </div>
  );
}
