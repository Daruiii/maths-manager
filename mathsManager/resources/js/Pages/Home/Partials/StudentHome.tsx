import { useAuth } from '@/Hooks/Auth/useAuth';
import StudentSidebar from '@/Pages/Home/Partials/StudentSidebar';
import { formatDueDate } from '@/Pages/Home/Partials/AssignmentItem';
import StudentHero from '@/Pages/Home/Partials/Student/StudentHero';
import StudentWorkSection from '@/Pages/Home/Partials/Student/StudentWorkSection';
import {
  buildSortedItems,
  isActionableStatus,
  isOngoingStatus,
} from '@/Pages/Home/Partials/Student/studentHomeData';
import type { HomeActiveAssignment } from '@/types';

interface Props {
  activeAssignments?: {
    ds: HomeActiveAssignment[];
    dm: HomeActiveAssignment[];
    td: HomeActiveAssignment[];
  };
  averageGrade?: number | null;
  correctedCount?: number;
}

export default function StudentHome({
  activeAssignments,
  averageGrade,
  correctedCount = 0,
}: Props) {
  const { user } = useAuth();
  const firstName = user?.first_name ?? '';

  const ds = activeAssignments?.ds ?? [];
  const dm = activeAssignments?.dm ?? [];
  const td = activeAssignments?.td ?? [];
  const allItems = buildSortedItems(ds, dm, td);
  const total = allItems.length;
  const ongoingCount = allItems.filter((i) => isOngoingStatus(i.status)).length;
  const toDoCount = allItems.filter((i) => isActionableStatus(i.status)).length;

  const dropdownItems = allItems.filter((i) => isActionableStatus(i.status)).slice(0, 5);
  const firstAction = dropdownItems[0];
  const ctaHref = firstAction?.href ?? route('student.assignments.index');
  const ctaLabel = !firstAction
    ? 'Voir mes devoirs'
    : isOngoingStatus(firstAction.status)
      ? 'Reprendre'
      : 'Commencer';

  const displayItems = allItems.slice(0, 4);
  const remainingCount = Math.max(0, total - 4);

  const nextDueItem = allItems.find((i) => i.due_date && isActionableStatus(i.status));
  const dueFmt = nextDueItem ? formatDueDate(nextDueItem.due_date) : null;
  const nextDue =
    nextDueItem && dueFmt
      ? {
          label: dueFmt.label,
          title: nextDueItem.title,
          type: nextDueItem.type,
          href: nextDueItem.href,
          urgent: dueFmt.urgent,
        }
      : null;

  const heroMessage =
    total === 0
      ? 'Tout est à jour !'
      : ongoingCount > 0
        ? 'Continue sur ta lancée.'
        : "Du travail t'attend.";

  return (
    <div className="space-y-6">
      <StudentHero
        firstName={firstName}
        heroMessage={heroMessage}
        total={total}
        ctaHref={ctaHref}
        ctaLabel={ctaLabel}
        dropdownItems={dropdownItems}
        toDoCount={toDoCount}
        ongoingCount={ongoingCount}
        correctedCount={correctedCount}
      />

      <div className="grid grid-cols-1 lg:grid-cols-[1fr_260px] gap-6 items-start">
        <div className="space-y-4">
          <StudentWorkSection
            total={total}
            displayItems={displayItems}
            remainingCount={remainingCount}
          />
        </div>

        <StudentSidebar averageGrade={averageGrade} nextDue={nextDue} />
      </div>
    </div>
  );
}
