import type { BatchBrief, BatchType } from '@/types/api';

const TYPE_LABELS: Record<BatchType, string> = { ds: 'DS', dm: 'DM', td: 'TD' };

interface Props {
  types: BatchType[];
  active: BatchType;
  lists: Record<BatchType, BatchBrief[]>;
  onChange: (type: BatchType) => void;
}

export default function BatchTypeTabBar({ types, active, lists, onChange }: Props) {
  return (
    <div className="flex gap-1 shrink-0 bg-secondary-color border border-border-color rounded-2xl p-1 mb-3">
      {types.map((type) => {
        const list = lists[type];
        const pending = list.reduce((s, b) => s + b.pending_actions, 0);
        const isActive = active === type;
        return (
          <button
            key={type}
            onClick={() => onChange(type)}
            className={`flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-comfortaa-bold transition-all ${
              isActive ? 'bg-surface-color text-text-color shadow-sm' : 'text-text-gray'
            }`}
          >
            <span>{TYPE_LABELS[type]}</span>
            <span className="font-cmu-serif text-[11px]">{list.length}</span>
            {pending > 0 && <span className="w-1.5 h-1.5 rounded-full bg-warning-color shrink-0" />}
          </button>
        );
      })}
    </div>
  );
}
