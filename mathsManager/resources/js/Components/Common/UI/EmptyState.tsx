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
    action: 'text-teacher-color',
  },
  student: {
    bg: 'bg-student-color/10',
    icon: 'text-student-color/60',
    action: 'text-student-color',
  },
  admin: { bg: 'bg-admin-color/10', icon: 'text-admin-color/60', action: 'text-admin-color' },
  default: { bg: 'bg-surface-color', icon: 'text-text-gray', action: 'text-tertiary-color' },
};

export default function EmptyState({
  icon: Icon,
  description,
  action,
  accentColor = 'default',
}: Props) {
  const colors = colorMap[accentColor];

  return (
    <div className="flex flex-col items-center justify-center h-full py-12 text-center gap-3">
      <div className={`w-12 h-12 rounded-2xl ${colors.bg} flex items-center justify-center`}>
        <Icon size={22} className={colors.icon} />
      </div>
      <p className="text-sm text-text-gray leading-relaxed">{description}</p>
      {action && (
        <button
          type="button"
          onClick={action.onClick}
          className={`text-xs hover:underline ${colors.action}`}
        >
          {action.label}
        </button>
      )}
    </div>
  );
}
