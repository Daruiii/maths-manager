import { getTeacherStatusLabel, STATUS_DISPLAY_PRIORITY } from '@/Constants/statuses';
import type { BatchType } from '@/types/api';

const MAX_FILTER_CARDS = 4;
const BASE_URGENT = new Set(['sent', 'correction_requested']);

interface Props {
  statuses: Record<string, number>;
  activeStatus: string | null;
  type: BatchType;
  onChange: (status: string | null) => void;
}

export default function StatusFilterGrid({ statuses, activeStatus, type, onChange }: Props) {
  const priority =
    type === 'dm'
      ? [
          'finished',
          'finished_late',
          ...STATUS_DISPLAY_PRIORITY.filter((s) => s !== 'finished' && s !== 'finished_late'),
        ]
      : STATUS_DISPLAY_PRIORITY;
  const visibleStatuses = priority.filter((s) => s in statuses).slice(0, MAX_FILTER_CARDS);

  if (visibleStatuses.length <= 1) return null;

  return (
    <div
      className="grid grid-cols-2 sm:grid-cols-4 gap-2 animate-fadeInUp"
      style={{ animationDelay: '40ms' }}
    >
      {visibleStatuses.map((status) => {
        const count = statuses[status];
        const isActive = activeStatus === status;
        const isUrgent =
          (BASE_URGENT.has(status) || (type === 'dm' && status === 'finished')) && count > 0;

        return (
          <button
            key={status}
            onClick={() => onChange(isActive ? null : status)}
            className={`relative text-left p-3 rounded-2xl border transition-all duration-150 hover:-translate-y-0.5 ${
              isActive
                ? 'border-teacher-color/40 bg-teacher-color/[0.05] shadow-warm-sm'
                : isUrgent
                  ? 'border-warning-color/40 bg-warning-color/[0.04] hover:bg-warning-color/[0.08]'
                  : 'border-border-color bg-secondary-color hover:bg-surface-color'
            }`}
          >
            {isUrgent && !isActive && (
              <span className="absolute top-2 right-2 w-1.5 h-1.5 rounded-full bg-warning-color" />
            )}
            <p
              className={`text-xl font-cmu-serif leading-none ${isUrgent && !isActive ? 'text-warning-color' : 'text-text-color'}`}
            >
              {count}
            </p>
            <p
              className={`text-[11px] font-comfortaa-bold mt-1 ${
                isActive ? 'text-teacher-color' : isUrgent ? 'text-warning-color' : 'text-text-gray'
              }`}
            >
              {getTeacherStatusLabel(status, type)}
            </p>
          </button>
        );
      })}
    </div>
  );
}
