import { useAuth } from '@/Hooks/Auth/useAuth';
import TeacherAdminBanner from '@/Pages/Home/Partials/Teacher/TeacherAdminBanner';
import TeacherHero from '@/Pages/Home/Partials/Teacher/TeacherHero';
import TeacherPendingPanel from '@/Pages/Home/Partials/Teacher/TeacherPendingPanel';
import TeacherWorkbench from '@/Pages/Home/Partials/Teacher/TeacherWorkbench';
import { groupByBatch, teacherHeroMessage } from '@/Pages/Home/Partials/Teacher/teacherHomeData';
import type { HomePendingCorrectionItem, HomeUnlockRequestItem } from '@/types';

interface Props {
  pendingCorrections?: { count: number; items: HomePendingCorrectionItem[] };
  unlockRequests?: { count: number; items: HomeUnlockRequestItem[] };
  pendingTeachersCount?: number;
  activeStudentsCount?: number;
  assignedThisMonth?: number;
  activeBatches?: { ds: number; dm: number; td: number };
}

export default function TeacherHome({
  pendingCorrections,
  unlockRequests,
  pendingTeachersCount,
  activeStudentsCount,
  assignedThisMonth,
  activeBatches,
}: Props) {
  const { user } = useAuth();
  const correctionsCount = pendingCorrections?.count ?? 0;
  const unlockCount = unlockRequests?.count ?? 0;
  const allClear = correctionsCount + unlockCount === 0;
  const batches = groupByBatch(pendingCorrections?.items ?? []);
  const unlockBatchItems = unlockRequests?.items ?? [];

  return (
    <div className="space-y-6">
      <TeacherAdminBanner count={pendingTeachersCount} />

      <TeacherHero
        firstName={user?.first_name ?? ''}
        message={teacherHeroMessage(correctionsCount, unlockCount)}
        allClear={allClear}
        stats={[
          { value: activeStudentsCount ?? 0, label: 'élèves' },
          { value: correctionsCount, label: 'corrections' },
          { value: assignedThisMonth ?? 0, label: 'assignations ce mois' },
        ]}
      />

      <div className="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-6 items-start">
        <TeacherWorkbench activeBatches={activeBatches} />
        <div className="space-y-4">
          <TeacherPendingPanel
            allClear={allClear}
            batches={batches}
            unlockRequests={unlockBatchItems}
          />
        </div>
      </div>
    </div>
  );
}
