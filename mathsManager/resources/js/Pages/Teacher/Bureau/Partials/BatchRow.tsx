import { router } from '@inertiajs/react';
import { AlertCircle, Calendar, Users } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import { BATCH_STATUS_META, BATCH_PENDING_LABEL } from '@/Constants/statuses';
import type { BatchBrief, BatchType } from '@/types/api';

interface Props {
  batch: BatchBrief;
  type: BatchType;
}

export default function BatchRow({ batch, type }: Props) {
  const pluralize = (n: number, word: string) => `${n} ${word}${n > 1 ? 's' : ''}`;

  function unlockAll() {
    router.patch(route('teacher.td.batch.unlock', batch.id));
  }

  return (
    <article className="bg-surface-color border border-border-color rounded-2xl p-4 space-y-3">
      <div className="flex items-start justify-between gap-2">
        <div className="min-w-0">
          <p className="text-sm font-comfortaa-bold text-text-color truncate">{batch.title}</p>
          <div className="flex items-center gap-3 mt-1 text-xs text-text-gray">
            <span className="flex items-center gap-1">
              <Users size={12} />
              {pluralize(batch.total, 'élève')}
            </span>
            {batch.due_date && (
              <span className="flex items-center gap-1">
                <Calendar size={12} />
                {new Intl.DateTimeFormat('fr-FR', { dateStyle: 'short' }).format(
                  new Date(batch.due_date)
                )}
              </span>
            )}
          </div>
        </div>

        {batch.pending_actions > 0 && (
          <span className="shrink-0 inline-flex items-center gap-1 px-2 py-1 rounded-full text-[11px] font-comfortaa-bold bg-warning-color/10 text-warning-color">
            <AlertCircle size={11} />
            {pluralize(batch.pending_actions, BATCH_PENDING_LABEL[type])}
          </span>
        )}
      </div>

      {Object.keys(batch.statuses).length > 0 && (
        <div className="flex flex-wrap gap-1.5">
          {Object.entries(batch.statuses).map(([status, count]) => {
            const meta = BATCH_STATUS_META[status] ?? {
              label: status,
              classes: 'bg-surface-color text-text-gray',
            };
            return (
              <span
                key={status}
                className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] border border-border-color ${meta.classes}`}
              >
                <span className="font-comfortaa-bold">{count}</span>
                {meta.label}
              </span>
            );
          })}
        </div>
      )}

      {type === 'td' && batch.pending_actions > 0 && (
        <Button variant="teacher" size="sm" onClick={unlockAll}>
          Débloquer toutes les corrections ({batch.pending_actions})
        </Button>
      )}
    </article>
  );
}
