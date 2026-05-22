import { Link, router } from '@inertiajs/react';
import { Archive, ArchiveRestore, Calendar, ChevronRight, LockOpen, Users } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
import { BATCH_STATUS_META } from '@/Constants/statuses';
import type { BatchBrief, BatchType } from '@/types/api';

interface Props {
  batch: BatchBrief;
  type: BatchType;
}

export default function BatchRow({ batch, type }: Props) {
  const hasPending = batch.pending_actions > 0;
  const completed =
    type === 'td'
      ? (batch.statuses['correction_unlocked'] ?? 0)
      : (batch.statuses['corrected'] ?? 0);
  const progressPct = batch.total > 0 ? Math.round((completed / batch.total) * 100) : 0;
  const isComplete = progressPct === 100;
  const isOverdue = !isComplete && batch.due_date !== null && new Date(batch.due_date) < new Date();

  function unlockAll() {
    router.patch(route('teacher.td.batch.unlock', batch.id));
  }

  function toggleArchive(e: React.MouseEvent) {
    e.preventDefault();
    e.stopPropagation();
    router.patch(route('teacher.bureau.batch.archive', { type, id: batch.id }));
  }

  return (
    <article
      className={`relative bg-surface-color border rounded-xl overflow-hidden transition-all hover:shadow-warm-sm group/card ${
        hasPending ? 'border-warning-color/30' : 'border-border-color'
      }`}
    >
      {/* Progress bar */}
      <div className="h-0.5 bg-border-color">
        <div
          className="h-full bg-teacher-color/60 transition-all duration-500"
          style={{ width: `${progressPct}%` }}
        />
      </div>

      <Link
        href={route('teacher.assignations.show', { type, batch: batch.id })}
        className="block px-3 py-3 hover:bg-primary-color/30 transition-colors"
      >
        <div className="flex items-start justify-between gap-2">
          <div className="flex items-center gap-1.5 flex-1 min-w-0">
            <TypeBadge type={type} />
            <p className="text-sm font-comfortaa-bold text-text-color truncate leading-tight">
              {batch.title}
            </p>
          </div>
          <div className="flex items-center gap-1.5 shrink-0">
            {hasPending && (
              <span className="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-comfortaa-bold bg-warning-color/15 text-warning-color">
                {batch.pending_actions}
              </span>
            )}
            {isOverdue && (
              <span className="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-comfortaa-bold bg-error-color/10 text-error-color">
                Dépassée
              </span>
            )}
            {!isComplete && (
              <button
                onClick={toggleArchive}
                className="opacity-0 group-hover/card:opacity-100 p-0.5 rounded text-text-gray/40 hover:text-text-gray transition-all"
                title={batch.is_archived ? 'Désarchiver' : 'Archiver'}
              >
                {batch.is_archived ? <ArchiveRestore size={12} /> : <Archive size={12} />}
              </button>
            )}
            <ChevronRight
              size={14}
              className="text-text-gray/30 group-hover/card:text-teacher-color group-hover/card:translate-x-0.5 transition-all"
            />
          </div>
        </div>

        <div className="flex items-center gap-2.5 mt-1 text-[11px] text-text-gray">
          <span className="flex items-center gap-0.5">
            <Users size={10} />
            {batch.total}
          </span>
          {batch.due_date && (
            <span className={`flex items-center gap-0.5 ${isOverdue ? 'text-error-color' : ''}`}>
              <Calendar size={10} />
              {new Intl.DateTimeFormat('fr-FR', { dateStyle: 'short' }).format(
                new Date(batch.due_date)
              )}
            </span>
          )}
          {batch.total > 0 && (
            <span className="ml-auto font-cmu-serif text-xs text-text-gray/60">
              {completed}/{batch.total}
            </span>
          )}
        </div>

        {Object.keys(batch.statuses).length > 0 && (
          <div className="relative mt-2 pt-2 border-t border-border-color/50">
            <div className="flex gap-1 overflow-x-hidden">
              {Object.entries(batch.statuses).map(([status, count]) => {
                const meta = BATCH_STATUS_META[status] ?? {
                  label: status,
                  classes: 'bg-surface-color text-text-gray',
                };
                return (
                  <span
                    key={status}
                    className={`inline-flex shrink-0 items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] ${meta.classes}`}
                  >
                    <span className="font-comfortaa-bold">{count}</span>
                    <span className="opacity-80">{meta.label}</span>
                  </span>
                );
              })}
            </div>
            <div className="absolute right-0 top-2 bottom-0 w-4 bg-gradient-to-l from-surface-color pointer-events-none" />
          </div>
        )}
      </Link>

      {type === 'td' && hasPending && (
        <div className="px-3 pb-3 pt-0">
          <Button variant="teacher" size="sm" icon={LockOpen} onClick={unlockAll}>
            Débloquer ({batch.pending_actions})
          </Button>
        </div>
      )}
    </article>
  );
}
