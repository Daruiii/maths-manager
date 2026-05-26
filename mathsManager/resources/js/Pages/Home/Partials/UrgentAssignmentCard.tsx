import { Link } from '@inertiajs/react';
import { ChevronRight, CalendarDays } from 'lucide-react';
import { BATCH_STATUS_META } from '@/Constants/statuses';
import type { FlatItem } from '@/Pages/Home/Partials/AssignmentItem';
import { formatDueDate } from '@/Pages/Home/Partials/AssignmentItem';

const CTA_LABEL: Record<string, string> = {
  ongoing: 'Reprendre',
  paused: 'Reprendre',
  not_started: 'Commencer',
  finished: 'Envoyer',
  finished_late: 'Envoyer',
};

const TYPE_STYLE: Record<string, { strip: string; label: string; name: string; border: string }> = {
  ds: {
    strip: 'bg-ds-color',
    label: 'text-ds-color',
    name: 'DS',
    border: 'border-ds-color/50',
  },
  dm: {
    strip: 'bg-dm-color',
    label: 'text-dm-color',
    name: 'DM',
    border: 'border-dm-color/50',
  },
  td: {
    strip: 'bg-td-color',
    label: 'text-td-color',
    name: 'TD',
    border: 'border-td-color/50',
  },
};

const STATUS_COLOR: Record<string, string> = {
  ongoing: 'text-student-color',
  paused: 'text-text-gray',
  not_started: 'text-text-gray',
  finished: 'text-success-color',
  finished_late: 'text-error-color',
};

interface Props {
  item: FlatItem;
  index?: number;
  tile?: boolean;
}

export default function UrgentAssignmentCard({ item, index = 0, tile = false }: Props) {
  const meta = BATCH_STATUS_META[item.status] ?? BATCH_STATUS_META.not_started;
  const dueDate = formatDueDate(item.due_date);
  const ctaLabel = CTA_LABEL[item.status] ?? 'Ouvrir';
  const isActive = item.status === 'ongoing' || item.status === 'paused';
  const typeStyle = TYPE_STYLE[item.type] ?? TYPE_STYLE.ds;
  const statusColor = STATUS_COLOR[item.status] ?? 'text-text-gray';

  const typeHeader = (
    <div className="flex items-center gap-1.5">
      <span
        className={`text-[10px] font-comfortaa-bold uppercase tracking-widest ${typeStyle.label}`}
      >
        {typeStyle.name}
      </span>
      <span className="text-[10px] text-text-gray opacity-40">·</span>
      <span className={`text-[10px] font-comfortaa-bold uppercase tracking-widest ${statusColor}`}>
        {meta.label}
      </span>
    </div>
  );

  const cta = (
    <span className="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-student-color text-white text-xs font-comfortaa-bold shrink-0 group-hover:opacity-90 transition-opacity">
      {ctaLabel} <ChevronRight size={11} />
    </span>
  );

  if (tile) {
    return (
      <Link
        href={item.href}
        style={{ animationDelay: `${index * 50}ms` }}
        className={`group flex flex-col gap-2 rounded-2xl pt-4 px-4 pb-4 h-full animate-fadeInUp hover:shadow-warm-sm transition-shadow bg-secondary-color border-x-2 ${typeStyle.border}`}
      >
        {typeHeader}
        <p className="text-sm font-comfortaa-bold text-text-color line-clamp-2 leading-snug flex-1">
          {item.title}
        </p>
        {dueDate ? (
          <p
            className={`text-[10px] flex items-center gap-1 ${dueDate.urgent ? 'text-error-color font-comfortaa-bold' : 'text-text-gray'}`}
          >
            <CalendarDays size={10} className="shrink-0" />
            {dueDate.label}
          </p>
        ) : (
          <p className="text-[10px] text-text-gray/50">Assigné récemment</p>
        )}
        <div className="flex justify-end">{cta}</div>
      </Link>
    );
  }

  return (
    <Link
      href={item.href}
      style={{ animationDelay: `${index * 50}ms` }}
      className={`group relative flex items-center gap-4 rounded-2xl pl-5 pr-4 py-3.5 overflow-hidden animate-fadeInUp hover:shadow-warm-sm transition-shadow ${isActive ? 'mm-card mm-card-style-halo mm-card-accent-student' : 'bg-secondary-color border border-border-color'}`}
    >
      <div className={`absolute left-0 top-0 bottom-0 w-[3px] ${typeStyle.strip}`} />
      <div className="flex-1 min-w-0 space-y-0.5">
        {typeHeader}
        <p className="text-sm font-comfortaa-bold text-text-color truncate">{item.title}</p>
      </div>
      <div className="flex flex-col items-end gap-1 shrink-0">
        {cta}
        {dueDate && (
          <span
            className={`text-[10px] flex items-center gap-1 ${dueDate.urgent ? 'text-error-color font-comfortaa-bold' : 'text-text-gray'}`}
          >
            <CalendarDays size={10} className="shrink-0" />
            {dueDate.label}
          </span>
        )}
      </div>
    </Link>
  );
}
