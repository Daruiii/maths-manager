import { useEffect, useMemo, useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Button from '@/Components/Common/UI/Button';
import { CONTENT_TYPE_META } from '@/Constants/contentTypes';
import { useConfetti } from '@/Hooks/UI/useConfetti';
import AssignmentBatchSummary from '@/Pages/Teacher/Assignments/Partials/AssignmentBatchSummary';
import AssignmentStudentList from '@/Pages/Teacher/Assignments/Partials/AssignmentStudentList';
import {
  filterAssignmentItems,
  getInitialCollapsedGroups,
  groupAssignmentItems,
  sortAssignmentItems,
} from '@/Pages/Teacher/Assignments/Partials/assignmentShowUtils';
import StatusFilterGrid from '@/Pages/Teacher/Assignments/Partials/StatusFilterGrid';
import type { AssignmentShowProps } from '@/Pages/Teacher/Assignments/Partials/types';

export default function AssignmentShow({ type, batch, items }: AssignmentShowProps) {
  const [statusFilter, setStatusFilter] = useState<string | null>(null);
  const [studentSearch, setStudentSearch] = useState('');
  const [collapsedGroups, setCollapsedGroups] = useState<Set<string>>(() =>
    getInitialCollapsedGroups()
  );

  useEffect(() => {
    if (statusFilter !== null) setCollapsedGroups(new Set());
  }, [statusFilter]);

  const meta = CONTENT_TYPE_META[type];
  const title = batch.title || meta.label;
  const sortedItems = useMemo(() => sortAssignmentItems(items), [items]);
  const displayedItems = useMemo(
    () => filterAssignmentItems(sortedItems, statusFilter, studentSearch),
    [sortedItems, statusFilter, studentSearch]
  );
  const groupedItems = useMemo(() => groupAssignmentItems(displayedItems), [displayedItems]);
  const showGroupHeaders = batch.groups.length > 0;
  const pendingUnlockCount = useMemo(
    () =>
      type === 'td' ? items.filter((item) => item.status === 'correction_requested').length : 0,
    [items, type]
  );
  const completedCount = useMemo(
    () =>
      type === 'td' ? (batch.statuses.correction_unlocked ?? 0) : (batch.statuses.corrected ?? 0),
    [type, batch.statuses]
  );
  const previewUrl = useMemo(() => items[0]?.show_url ?? null, [items]);

  useConfetti(completedCount, batch.total);

  function unlockStudent(id: number) {
    router.patch(route('teacher.td.unlock', id), {}, { preserveState: true, preserveScroll: true });
  }

  function unlockAll() {
    router.patch(
      route('teacher.td.batch.unlock', batch.id),
      {},
      { preserveState: true, preserveScroll: true }
    );
  }

  function toggleGroup(key: string) {
    setCollapsedGroups((prev) => {
      const next = new Set(prev);
      if (next.has(key)) next.delete(key);
      else next.add(key);
      return next;
    });
  }

  return (
    <AppLayout>
      <Head title={title} />

      <div className="max-w-3xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title={title}
          breadcrumbs={[
            { label: 'Mon Bureau', href: route('teacher.bureau.index') },
            { label: 'Devoirs envoyés', href: route('teacher.bureau.devoirs') },
            { label: title },
          ]}
        />

        <AssignmentBatchSummary
          type={type}
          batch={batch}
          completedCount={completedCount}
          pendingUnlockCount={pendingUnlockCount}
          previewUrl={previewUrl}
          onUnlockAll={unlockAll}
        />

        <StatusFilterGrid
          statuses={batch.statuses}
          activeStatus={statusFilter}
          type={type}
          onChange={setStatusFilter}
        />

        <AssignmentStudentList
          type={type}
          items={items}
          displayedItems={displayedItems}
          groupedItems={groupedItems}
          showGroupHeaders={showGroupHeaders}
          statusFilter={statusFilter}
          studentSearch={studentSearch}
          collapsedGroups={collapsedGroups}
          onStatusFilterChange={setStatusFilter}
          onStudentSearchChange={setStudentSearch}
          onToggleGroup={toggleGroup}
          onUnlockStudent={unlockStudent}
        />

        <div className="pt-1 animate-fadeInUp" style={{ animationDelay: '120ms' }}>
          <Link href={route(`teacher.${type}.create`)}>
            <Button variant="ghost" size="sm">
              Créer un autre {meta.label}
            </Button>
          </Link>
        </div>
      </div>
    </AppLayout>
  );
}
