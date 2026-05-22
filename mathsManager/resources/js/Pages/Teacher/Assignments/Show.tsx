import { useState, useMemo } from 'react';
import { useConfetti } from '@/Hooks/UI/useConfetti';
import { Head, Link, router } from '@inertiajs/react';
import {
  Calendar,
  ChevronDown,
  ChevronRight,
  Eye,
  LockOpen,
  Users,
  UsersRound,
  Search,
  X,
} from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Button from '@/Components/Common/UI/Button';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
import UserAvatar from '@/Components/Common/UI/UserAvatar';
import { CONTENT_TYPE_META } from '@/Constants/contentTypes';
import { BATCH_STATUS_META } from '@/Constants/statuses';
import type { BatchType } from '@/types/api';

const TEACHER_LABEL: Record<string, string> = {
  sent: 'À corriger',
};

interface BatchGroup {
  id: number;
  name: string;
  count: number;
}

interface AssignmentBatch {
  id: number;
  title: string;
  due_date: string | null;
  created_at: string;
  total: number;
  statuses: Record<string, number>;
  groups: BatchGroup[];
}

interface AssignmentItem {
  id: number;
  title: string | null;
  status: string;
  student: {
    id: number;
    first_name: string;
    last_name: string;
    avatar: string | null;
    group: { id: number; name: string } | null;
  } | null;
  show_url: string;
  correction_request_id: number | null;
  correction_status: string | null;
}

interface Props {
  type: BatchType;
  batch: AssignmentBatch;
  items: AssignmentItem[];
}

const STATUS_PRIORITY: Record<string, number> = {
  correction_requested: 0,
  sent: 0,
  finished: 1,
  finished_late: 1,
  ongoing: 2,
  paused: 2,
  not_started: 3,
  corrected: 4,
  correction_unlocked: 4,
};

function formatDate(date: string | null): string {
  if (!date) return 'Aucune échéance';
  return new Intl.DateTimeFormat('fr-FR', { dateStyle: 'medium' }).format(new Date(date));
}

export default function AssignmentShow({ type, batch, items }: Props) {
  const [statusFilter, setStatusFilter] = useState<string | null>(null);
  const [studentSearch, setStudentSearch] = useState('');
  const [collapsedGroups, setCollapsedGroups] = useState<Set<string>>(() => {
    const keyNames: Array<{ key: string; name: string | null }> = [];
    for (const item of items) {
      const g = item.student?.group;
      const key = g ? `g${g.id}` : '__none__';
      if (!keyNames.find((k) => k.key === key)) keyNames.push({ key, name: g?.name ?? null });
    }
    keyNames.sort((a, b) => {
      if (a.name === null) return 1;
      if (b.name === null) return -1;
      return a.name.localeCompare(b.name);
    });
    const collapsed = new Set(keyNames.map((k) => k.key));
    // First named group and ungrouped students always start open
    if (keyNames[0]) collapsed.delete(keyNames[0].key);
    collapsed.delete('__none__');
    return collapsed;
  });
  const meta = CONTENT_TYPE_META[type];
  const title = batch.title || meta.label;

  const sortedItems = useMemo(
    () =>
      [...items].sort(
        (a, b) => (STATUS_PRIORITY[a.status] ?? 5) - (STATUS_PRIORITY[b.status] ?? 5)
      ),
    [items]
  );

  const displayedItems = useMemo(() => {
    return sortedItems.filter((item) => {
      if (statusFilter && item.status !== statusFilter) return false;
      if (studentSearch.trim()) {
        const name = item.student
          ? `${item.student.first_name} ${item.student.last_name}`.toLowerCase()
          : '';
        if (!name.includes(studentSearch.toLowerCase())) return false;
      }
      return true;
    });
  }, [sortedItems, statusFilter, studentSearch]);

  const groupedItems = useMemo(() => {
    const map = new Map<string, { key: string; name: string | null; items: AssignmentItem[] }>();
    for (const item of displayedItems) {
      const g = item.student?.group;
      const key = g ? `g${g.id}` : '__none__';
      if (!map.has(key)) map.set(key, { key, name: g?.name ?? null, items: [] });
      map.get(key)!.items.push(item);
    }
    return Array.from(map.values()).sort((a, b) => {
      if (a.name === null) return 1;
      if (b.name === null) return -1;
      return a.name.localeCompare(b.name);
    });
  }, [displayedItems]);

  const showGroupHeaders = batch.groups.length > 0;

  const pendingUnlocks =
    type === 'td' ? items.filter((i) => i.status === 'correction_requested') : [];
  const completedCount =
    type === 'td'
      ? (batch.statuses['correction_unlocked'] ?? 0)
      : (batch.statuses['corrected'] ?? 0);
  const progressPct = batch.total > 0 ? Math.round((completedCount / batch.total) * 100) : 0;

  useConfetti(completedCount, batch.total);

  // First item's show_url used as batch-level subject preview
  const previewUrl = items[0]?.show_url ?? null;

  function unlockStudent(id: number) {
    router.patch(route('teacher.td.unlock', id));
  }

  function unlockAll() {
    router.patch(route('teacher.td.batch.unlock', batch.id));
  }

  function toggleGroup(key: string) {
    setCollapsedGroups((prev) => {
      const next = new Set(prev);
      if (next.has(key)) next.delete(key);
      else next.add(key);
      return next;
    });
  }

  function renderRow(item: AssignmentItem, isLast: boolean) {
    const sm = BATCH_STATUS_META[item.status] ?? {
      label: item.status,
      classes: 'bg-surface-color text-text-gray',
    };
    const isUrgent = item.status === 'correction_requested' || item.status === 'sent';
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
          className={`text-[11px] px-2 py-0.5 rounded-full font-comfortaa-bold shrink-0 ${sm.classes}`}
        >
          {TEACHER_LABEL[item.status] ?? sm.label}
        </span>
      </>
    );

    // TD pending unlock — row not clickable, action button present
    if (type === 'td' && item.status === 'correction_requested') {
      return (
        <div key={item.id} className={rowBase}>
          {rowBody}
          <Button
            type="button"
            variant="teacher"
            size="sm"
            icon={LockOpen}
            onClick={() => unlockStudent(item.id)}
          >
            Débloquer
          </Button>
        </div>
      );
    }

    // DS/DM with submitted copy — link to correction view (Inertia nav)
    if (item.correction_request_id) {
      return (
        <Link
          key={item.id}
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

    // All others — teacher preview in new tab
    return (
      <a
        key={item.id}
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

        {/* ── Batch summary card ── */}
        <div className="bg-surface-color border border-border-color rounded-2xl overflow-hidden animate-fadeIn">
          {/* Progress bar — natural flow at the very top of the card */}
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
                  {formatDate(batch.due_date)}
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
            {type === 'td' && pendingUnlocks.length > 0 && (
              <div>
                <Button variant="teacher" size="sm" icon={LockOpen} onClick={unlockAll}>
                  Tout débloquer ({pendingUnlocks.length})
                </Button>
              </div>
            )}
          </div>
        </div>

        {/* ── Status filter cards ── */}
        {Object.keys(batch.statuses).length > 1 && (
          <div
            className="grid grid-cols-2 sm:grid-cols-4 gap-2 animate-fadeInUp"
            style={{ animationDelay: '40ms' }}
          >
            {Object.entries(batch.statuses).map(([status, count]) => {
              const sm = BATCH_STATUS_META[status] ?? {
                label: status,
                classes: 'bg-surface-color text-text-gray',
              };
              const isActive = statusFilter === status;
              return (
                <button
                  key={status}
                  onClick={() => setStatusFilter(isActive ? null : status)}
                  className={`text-left p-3 rounded-2xl border transition-all duration-150 hover:-translate-y-0.5 ${
                    isActive
                      ? 'border-teacher-color/40 bg-teacher-color/[0.05] shadow-warm-sm'
                      : 'border-border-color bg-secondary-color hover:bg-surface-color'
                  }`}
                >
                  <p className="text-xl font-cmu-serif text-text-color leading-none">{count}</p>
                  <p
                    className={`text-[11px] font-comfortaa-bold mt-1 ${
                      isActive ? 'text-teacher-color' : 'text-text-gray'
                    }`}
                  >
                    {TEACHER_LABEL[status] ?? sm.label}
                  </p>
                </button>
              );
            })}
          </div>
        )}

        {/* ── Student list ── */}
        <section className="space-y-3 animate-fadeInUp" style={{ animationDelay: '80ms' }}>
          <div className="flex items-center justify-between gap-3">
            <p className="mm-section-header">
              {statusFilter
                ? `${displayedItems.length} élève${displayedItems.length > 1 ? 's' : ''} — ${TEACHER_LABEL[statusFilter] ?? BATCH_STATUS_META[statusFilter]?.label ?? statusFilter}`
                : `${items.length} élève${items.length > 1 ? 's' : ''}`}
            </p>
            <div className="flex items-center gap-2">
              {statusFilter && (
                <button
                  onClick={() => setStatusFilter(null)}
                  className="text-[11px] font-comfortaa-bold text-teacher-color hover:underline flex items-center gap-1"
                >
                  <X size={11} /> Filtre
                </button>
              )}
              <div className="relative">
                <Search
                  size={12}
                  className="absolute left-2.5 top-1/2 -translate-y-1/2 text-text-gray pointer-events-none"
                />
                <input
                  type="text"
                  value={studentSearch}
                  onChange={(e) => setStudentSearch(e.target.value)}
                  placeholder="Chercher un élève..."
                  className="pl-7 pr-3 py-1.5 text-xs w-44 border border-border-color bg-secondary-color rounded-xl text-text-color placeholder:text-text-gray focus:outline-none focus:border-teacher-color/50 transition-colors"
                />
              </div>
            </div>
          </div>

          <div className="bg-secondary-color border border-border-color rounded-2xl overflow-hidden">
            {displayedItems.length === 0 ? (
              <div className="flex flex-col items-center py-10 gap-2 text-text-gray text-sm">
                <p>Aucun élève ne correspond.</p>
              </div>
            ) : showGroupHeaders ? (
              groupedItems.map((g) => {
                const isCollapsed = collapsedGroups.has(g.key);
                return (
                  <div key={g.key}>
                    <button
                      type="button"
                      onClick={() => toggleGroup(g.key)}
                      className="w-full flex items-center justify-between px-4 py-2 bg-primary-color/30 border-b border-border-color hover:bg-primary-color/50 transition-colors"
                    >
                      <span className="flex items-center gap-1.5 text-[11px] font-comfortaa-bold text-text-color uppercase tracking-wide">
                        {isCollapsed ? (
                          <ChevronRight size={12} className="text-teacher-color" />
                        ) : (
                          <ChevronDown size={12} className="text-teacher-color" />
                        )}
                        {g.name ?? 'Sans groupe'}
                      </span>
                      <span className="text-[11px] text-text-gray">{g.items.length}</span>
                    </button>
                    {!isCollapsed &&
                      g.items.map((item, i) => renderRow(item, i === g.items.length - 1))}
                  </div>
                );
              })
            ) : (
              displayedItems.map((item, i) => renderRow(item, i === displayedItems.length - 1))
            )}
          </div>
        </section>

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
