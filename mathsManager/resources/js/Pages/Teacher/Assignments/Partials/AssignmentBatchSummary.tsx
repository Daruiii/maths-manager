import { Calendar, Eye, LockOpen, Users, UsersRound } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
import { formatBatchDate } from '@/Pages/Teacher/Assignments/Partials/assignmentShowUtils';
import type { AssignmentBatch } from '@/Pages/Teacher/Assignments/Partials/types';
import type { BatchType } from '@/types/api';

interface Props {
  type: BatchType;
  batch: AssignmentBatch;
  completedCount: number;
  pendingUnlockCount: number;
  previewUrl: string | null;
  onUnlockAll: () => void;
}

export default function AssignmentBatchSummary({
  type,
  batch,
  completedCount,
  pendingUnlockCount,
  previewUrl,
  onUnlockAll,
}: Props) {
  const progressPct = batch.total > 0 ? Math.round((completedCount / batch.total) * 100) : 0;

  return (
    <div className="bg-surface-color border border-border-color rounded-2xl overflow-hidden animate-fadeIn">
      <div className="h-1 bg-border-color">
        <div
          className="h-full bg-teacher-color transition-all duration-700 ease-out"
          style={{ width: `${progressPct}%` }}
        />
      </div>

      <div className="p-4 space-y-3">
        <div className="flex items-center gap-3">
          <TypeBadge type={type} size="sm" />
          <div className="flex items-center gap-2 text-xs text-text-gray flex-wrap flex-1 min-w-0">
            <span className="flex items-center gap-1">
              <Users size={11} />
              {batch.total} élève{batch.total > 1 ? 's' : ''}
            </span>
            {batch.groups.length > 0 && (
              <span className="flex items-center gap-1 text-teacher-color font-comfortaa-bold">
                <UsersRound size={11} />
                {batch.groups.length === 1
                  ? batch.groups[0].name
                  : `${batch.groups.length} groupes`}
              </span>
            )}
            <span className="flex items-center gap-1">
              <Calendar size={11} />
              {formatBatchDate(batch.due_date)}
            </span>
          </div>
          {previewUrl && (
            <a href={previewUrl} target="_blank" rel="noreferrer">
              <Button variant="ghost" size="sm" icon={Eye}>
                Sujet
              </Button>
            </a>
          )}
          <div className="shrink-0 border-l border-border-color pl-3">
            <p className="font-cmu-serif text-xl leading-none text-text-color">
              {completedCount}
              <span className="text-sm text-text-gray">/{batch.total}</span>
            </p>
            <p className="text-[10px] text-text-gray mt-0.5">
              terminé{completedCount > 1 ? 's' : ''}
            </p>
          </div>
        </div>

        {type === 'td' && completedCount < batch.total && (
          <Button variant="teacher" size="sm" icon={LockOpen} onClick={onUnlockAll}>
            {pendingUnlockCount > 0
              ? `Débloquer demandes (${pendingUnlockCount})`
              : 'Tout débloquer'}
          </Button>
        )}
      </div>
    </div>
  );
}
