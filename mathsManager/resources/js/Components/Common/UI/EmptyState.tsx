import { LucideIcon } from 'lucide-react';

type AccentColor = 'teacher' | 'student' | 'admin' | 'default';

interface Props {
  icon: LucideIcon;
  description: string;
  action?: { label: string; onClick: () => void };
  accentColor?: AccentColor;
}

const colorMap: Record<AccentColor, { bg: string; icon: string; action: string }> = {
  teacher: {
    bg: 'bg-teacher-color/10',
    icon: 'text-teacher-color/60',
    action: 'text-teacher-color hover:text-teacher-color/80',
  },
  student: {
    bg: 'bg-student-color/10',
    icon: 'text-student-color/60',
    action: 'text-student-color hover:text-student-color/80',
  },
  admin: {
    bg: 'bg-admin-color/10',
    icon: 'text-admin-color/60',
    action: 'text-admin-color hover:text-admin-color/80',
  },
  default: {
    bg: 'bg-surface-color',
    icon: 'text-text-gray/60',
    action: 'text-tertiary-color hover:text-tertiary-color/80',
  },
};

export default function EmptyState({
  icon: Icon,
  description,
  action,
  accentColor = 'default',
}: Props) {
  const colors = colorMap[accentColor];

  return (
    <div className="flex flex-col items-center justify-center h-full py-12 text-center gap-3 animate-fadeInUp">
      <div
        className={`w-11 h-11 rounded-2xl ${colors.bg} flex items-center justify-center shadow-warm-xs`}
      >
        <Icon size={20} className={colors.icon} />
      </div>
      <p className="text-sm text-text-gray leading-relaxed max-w-[220px]">{description}</p>
      {action && (
        <button
          type="button"
          onClick={action.onClick}
          className={`text-xs font-comfortaa-bold hover:underline underline-offset-2 transition-colors ${colors.action}`}
        >
          {action.label}
        </button>
      )}
    </div>
  );
}
