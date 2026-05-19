import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
import { BATCH_STATUS_META } from '@/Constants/statuses';
import type { HomeActiveAssignment } from '@/types';

type ItemType = 'ds' | 'dm' | 'td';

export interface FlatItem extends HomeActiveAssignment {
  type: ItemType;
  href: string;
}

export function formatDueDate(date?: string | null) {
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

  return (
    <Link
      href={item.href}
      style={{ animationDelay: `${index * 30}ms` }}
      className="flex items-center gap-3 px-4 py-3 hover:bg-surface-color transition-colors group border-b border-border-color last:border-b-0 animate-fadeInUp"
    >
      <TypeBadge type={item.type} size="sm" />
      <span className="flex-1 text-sm font-comfortaa-bold text-text-color truncate min-w-0">
        {item.title}
      </span>
      <span
        className={`text-[10px] font-comfortaa-bold px-2 py-0.5 rounded-full shrink-0 ${meta.classes}`}
      >
        {meta.label}
      </span>
      <ChevronRight
        size={13}
        className="text-text-gray group-hover:text-text-color transition-colors shrink-0"
      />
    </Link>
  );
}
