import { Link } from '@inertiajs/react';
import { ArrowUpDown, ChevronRight } from 'lucide-react';
import { BureauActivity } from '@/types/api';
import {
  HISTORY_TYPE_ICONS,
  HISTORY_TYPE_LABELS,
} from '@/Pages/Teacher/Bureau/Partials/historyMeta';

interface Props {
  activity: BureauActivity;
}

export default function HistoryActivityRow({ activity }: Props) {
  const Icon = HISTORY_TYPE_ICONS[activity.type];

  return (
    <article className="bg-surface-color border border-border-color rounded-2xl p-4 flex items-start gap-3">
      <div className="shrink-0 w-10 h-10 rounded-xl bg-teacher-color/10 text-teacher-color flex items-center justify-center">
        <Icon size={18} />
      </div>

      <div className="min-w-0 flex-1 space-y-1">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
          <p className="text-sm font-comfortaa-bold text-text-color">{activity.title}</p>
          <span className="text-xs text-text-gray inline-flex items-center gap-1">
            <ArrowUpDown size={12} />
            {activity.occurred_at
              ? new Intl.DateTimeFormat('fr-FR', {
                  dateStyle: 'medium',
                  timeStyle: 'short',
                }).format(new Date(activity.occurred_at))
              : 'Date inconnue'}
          </span>
        </div>

        <p className="text-sm text-text-gray">{activity.description}</p>

        <span className="inline-flex px-2 py-1 rounded-full text-[11px] bg-secondary-color border border-border-color text-text-gray">
          {HISTORY_TYPE_LABELS[activity.type]}
        </span>
        {activity.href && (
          <Link
            href={activity.href}
            className="inline-flex items-center gap-1 text-xs font-comfortaa-bold text-teacher-color hover:underline"
          >
            Voir le récap <ChevronRight size={13} />
          </Link>
        )}
      </div>
    </article>
  );
}
