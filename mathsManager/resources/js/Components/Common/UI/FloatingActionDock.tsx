import type { ReactNode } from 'react';
import type { LucideIcon } from 'lucide-react';

type Props =
  | {
      description: string;
      label: string;
      mobileLabel?: string;
      icon: LucideIcon;
      onClick: () => void;
      accent?: 'student' | 'teacher';
      leading?: never;
      actions?: never;
    }
  | {
      leading: ReactNode;
      actions: ReactNode;
      accent?: 'student' | 'teacher';
      description?: never;
      label?: never;
      mobileLabel?: never;
      icon?: never;
      onClick?: never;
    };

export default function FloatingActionDock({
  label,
  mobileLabel = label,
  description,
  icon: Icon,
  onClick,
  leading,
  actions,
  accent = 'student',
}: Props) {
  const dockStyle =
    accent === 'teacher'
      ? 'border-teacher-color/20 shadow-[0_16px_44px_rgb(var(--teacher-color)_/_0.14)]'
      : 'border-student-color/20 shadow-[0_16px_44px_rgb(var(--student-color)_/_0.14)]';
  const buttonStyle = accent === 'teacher' ? 'bg-teacher-color' : 'bg-student-color';

  return (
    <div className="sticky bottom-6 z-30 mx-auto flex justify-center pointer-events-none animate-fadeInUp">
      <div
        className={`pointer-events-auto flex w-full max-w-md flex-col gap-2 rounded-2xl border bg-secondary-color/95 px-3 py-2.5 backdrop-blur-md sm:flex-row sm:items-center sm:justify-between sm:gap-3 ${dockStyle}`}
      >
        <div className="min-w-0 flex-1">
          {leading ?? (
            <p className="truncate text-xs font-comfortaa text-text-gray">{description}</p>
          )}
        </div>

        {actions ??
          (Icon ? (
            <button
              type="button"
              onClick={onClick}
              className={`shrink-0 inline-flex items-center justify-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-comfortaa-bold text-white shadow-warm-xs hover:opacity-90 active:opacity-80 transition-opacity ${buttonStyle}`}
            >
              <Icon size={13} className="shrink-0" />
              <span className="hidden sm:inline">{label}</span>
              <span className="sm:hidden">{mobileLabel}</span>
            </button>
          ) : null)}
      </div>
    </div>
  );
}
