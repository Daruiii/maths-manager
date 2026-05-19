import { Link } from '@inertiajs/react';
import { CalendarDays, ChevronRight } from 'lucide-react';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
import { BATCH_STATUS_META } from '@/Constants/statuses';
import type { HomeActiveAssignment } from '@/types';

type ItemType = 'ds' | 'dm' | 'td';

export interface FlatItem extends HomeActiveAssignment {
  type: ItemType;
  href: string;
}

function formatDueDate(date?: string | null) {
  if (!date) return null;

  const today = new Date();
  today.setHours(0, 0, 0, 0);

  const due = new Date(`${date}T00:00:00`);
  const diffDays = Math.round((due.getTime() - today.getTime()) / 86_400_000);

  if (diffDays < 0) return { label: 'Échéance dépassée', urgent: true };
  if (diffDays === 0) return { label: "À rendre aujourd'hui", urgent: true };
  if (diffDays === 1) return { label: 'À rendre demain', urgent: false };

  return {
    label: `Échéance ${new Intl.DateTimeFormat('fr-FR', { day: 'numeric', month: 'short' }).format(due)}`,
    urgent: false,
  };
}

interface Props {
  item: FlatItem;
  index?: number;
}

export default function AssignmentItem({ item, index = 0 }: Props) {
  const meta = BATCH_STATUS_META[item.status] ?? BATCH_STATUS_META.not_started;
  const dueDate = formatDueDate(item.due_date);

  return (
    <Link
      href={item.href}
      style={{ animationDelay: `${index * 40}ms` }}
      className="flex items-center gap-3 px-4 py-3 rounded-2xl border border-border-color bg-primary-color/55 hover:bg-primary-color hover:shadow-warm-xs transition-all group animate-fadeInUp"
    >
      <TypeBadge type={item.type} size="md" />
      <div className="flex-1 min-w-0">
        <p className="text-sm font-comfortaa-bold text-text-color truncate">{item.title}</p>
        {dueDate && (
          <span
            className={`mt-1 inline-flex items-center gap-1 text-[11px] ${
              dueDate.urgent ? 'text-error-color' : 'text-text-gray'
            }`}
          >
            <CalendarDays size={12} />
            {dueDate.label}
          </span>
        )}
      </div>
      <div className="flex items-center gap-2 shrink-0">
        <span
          className={`text-[10px] font-comfortaa-bold px-2 py-0.5 rounded-full ${meta.classes}`}
        >
          {meta.label}
        </span>
        <ChevronRight
          size={14}
          className="text-text-gray group-hover:text-text-color transition-colors"
        />
      </div>
    </Link>
  );
}
