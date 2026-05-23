import { Link } from '@inertiajs/react';
import { ChevronRight, LockOpen, Pencil, PencilLine } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import UserAvatar from '@/Components/Common/UI/UserAvatar';
import { BATCH_STATUS_META } from '@/Constants/statuses';
import { getTeacherStatusLabel } from '@/Constants/statuses';
import type { AssignmentItem } from '@/Pages/Teacher/Assignments/Partials/types';
import type { BatchType } from '@/types/api';

interface Props {
  item: AssignmentItem;
  type: BatchType;
  isLast: boolean;
  onUnlockStudent: (id: number) => void;
}

export default function AssignmentStudentRow({ item, type, isLast, onUnlockStudent }: Props) {
  // DM uses `finished` where DS uses `sent` — both mean "copy received, needs correction"
  const isReadyForCorrection =
    item.status === 'sent' || (type === 'dm' && item.status === 'finished');
  const meta = isReadyForCorrection
    ? { classes: 'bg-warning-color/10 text-warning-color' }
    : (BATCH_STATUS_META[item.status] ?? { classes: 'bg-surface-color text-text-gray' });
  const isUrgent = item.status === 'correction_requested' || isReadyForCorrection;
  const studentName = item.student
    ? `${item.student.first_name} ${item.student.last_name}`
    : 'Élève';

  const rowBase = `flex items-center gap-3 px-4 py-3 transition-colors ${
    !isLast ? 'border-b border-border-color' : ''
  } ${isUrgent ? 'bg-warning-color/[0.03]' : ''}`;

  const rowBody = (
    <>
      <UserAvatar
        src={item.student?.avatar ?? undefined}
        alt={studentName}
        size="sm"
        className="shrink-0"
      />
      <div className="flex-1 min-w-0">
        <p className="font-comfortaa-bold text-sm text-text-color truncate">{studentName}</p>
      </div>
      <span
        className={`text-[11px] px-2 py-0.5 rounded-full font-comfortaa-bold shrink-0 ${meta.classes}`}
      >
        {getTeacherStatusLabel(item.status, type)}
      </span>
    </>
  );

  if (type === 'td' && item.status === 'correction_requested') {
    return (
      <div className={rowBase}>
        {rowBody}
        <Button
          type="button"
          variant="teacher"
          size="sm"
          icon={LockOpen}
          onClick={() => onUnlockStudent(item.id)}
        >
          Débloquer
        </Button>
      </div>
    );
  }

  if (isReadyForCorrection && item.correction_request_id) {
    return (
      <Link
        href={route('teacher.corrections.show', item.correction_request_id)}
        className={`block group ${rowBase} hover:bg-primary-color/40`}
      >
        {rowBody}
        <span className="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[11px] font-comfortaa-bold bg-teacher-color/10 text-teacher-color group-hover:bg-teacher-color/20 transition-colors shrink-0">
          <Pencil size={10} />
          Corriger
        </span>
      </Link>
    );
  }

  if (item.status === 'corrected' && item.correction_request_id) {
    return (
      <Link
        href={route('teacher.corrections.show', item.correction_request_id)}
        className={`block group ${rowBase} hover:bg-primary-color/40`}
      >
        {rowBody}
        <span className="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[11px] font-comfortaa-bold bg-surface-color text-text-gray group-hover:bg-border-color/40 transition-colors shrink-0">
          <PencilLine size={10} />
          Modifier
        </span>
      </Link>
    );
  }

  if (item.correction_request_id) {
    return (
      <Link
        href={route('teacher.corrections.show', item.correction_request_id)}
        className={`block group ${rowBase} hover:bg-primary-color/40`}
      >
        {rowBody}
        <ChevronRight
          size={14}
          className="text-text-gray/30 group-hover:text-teacher-color transition-colors shrink-0"
        />
      </Link>
    );
  }

  return (
    <a
      href={item.show_url}
      target="_blank"
      rel="noreferrer"
      className={`block group ${rowBase} hover:bg-primary-color/40`}
    >
      {rowBody}
      <ChevronRight
        size={14}
        className="text-text-gray/30 group-hover:text-teacher-color transition-colors shrink-0"
      />
    </a>
  );
}
