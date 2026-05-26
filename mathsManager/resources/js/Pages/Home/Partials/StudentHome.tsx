import { useMemo } from 'react';
import { useAuth } from '@/Hooks/Auth/useAuth';
import StudentSidebar from '@/Pages/Home/Partials/StudentSidebar';
import { formatDueDate } from '@/Pages/Home/Partials/AssignmentItem';
import StudentHero from '@/Pages/Home/Partials/Student/StudentHero';
import StudentWorkSection from '@/Pages/Home/Partials/Student/StudentWorkSection';
import {
  buildSortedItems,
  isActionableStatus,
  isOngoingStatus,
  studentAssignmentCtaLabel,
} from '@/Pages/Home/Partials/Student/studentHomeData';
import type { HomeActiveAssignment, HomeFeedbackItem, HomeFeedbackSummary } from '@/types';

interface Props {
  activeAssignments?: {
    ds: HomeActiveAssignment[];
    dm: HomeActiveAssignment[];
    td: HomeActiveAssignment[];
  };
  averageGrade?: number | null;
  correctedCount?: number;
  feedbackSummary?: HomeFeedbackSummary;
  recentFeedbackItems?: HomeFeedbackItem[];
}

export default function StudentHome({
  activeAssignments,
  averageGrade,
  correctedCount = 0,
  feedbackSummary,
  recentFeedbackItems,
}: Props) {
  const { user } = useAuth();
  const firstName = user?.first_name ?? '';

  const ds = activeAssignments?.ds ?? [];
  const dm = activeAssignments?.dm ?? [];
  const td = activeAssignments?.td ?? [];

  const allItems = useMemo(() => buildSortedItems(ds, dm, td), [ds, dm, td]);
  const actionableItems = useMemo(
    () => allItems.filter((i) => isActionableStatus(i.status)),
    [allItems]
  );

  const total = allItems.length;
  const ongoingCount = useMemo(
    () => allItems.filter((i) => isOngoingStatus(i.status)).length,
    [allItems]
  );
  const toDoCount = actionableItems.length;
  const dropdownItems = useMemo(() => actionableItems.slice(0, 5), [actionableItems]);
  const displayItems = useMemo(() => allItems.slice(0, 4), [allItems]);
  const remainingCount = Math.max(0, total - 4);

  const firstAction = dropdownItems[0];
  const ctaHref = firstAction?.href ?? route('student.assignments.index');
  const ctaLabel = studentAssignmentCtaLabel(firstAction?.status);

  const nextDue = useMemo(() => {
    const item = actionableItems.find((i) => i.due_date);
    if (!item) return null;
    const fmt = formatDueDate(item.due_date);
    if (!fmt) return null;
    return {
      label: fmt.label,
      title: item.title,
      type: item.type,
      href: item.href,
      urgent: fmt.urgent,
    };
  }, [actionableItems]);

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

        <StudentSidebar
          averageGrade={averageGrade}
          nextDue={nextDue}
          feedbackSummary={feedbackSummary}
          recentFeedbackItems={recentFeedbackItems}
        />
      </div>
    </div>
  );
}
