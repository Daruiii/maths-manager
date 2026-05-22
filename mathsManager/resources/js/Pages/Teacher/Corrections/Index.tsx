import { Head, Link, router } from '@inertiajs/react';
import { ClipboardList, ChevronRight, LockOpen, Clock } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
import Button from '@/Components/Common/UI/Button';
import type { PaginatedResponse } from '@/types/api';
import type { CorrectionRequestStatus, TdStatus } from '@/types/models';

interface CorrectionBrief {
  id: number;
  status: CorrectionRequestStatus;
  grade: number | null;
  created_at: string;
  user: { first_name: string; last_name: string } | null;
  ds: { id: number; custom_title: string | null } | null;
  dm: { id: number; custom_title: string | null } | null;
}

interface TdUnlockBrief {
  id: number;
  status: TdStatus;
  custom_title: string | null;
  custom_level: string | null;
  batch_id: number | null;
  updated_at: string;
  student: { first_name: string; last_name: string } | null;
}

interface TdBatchGroup {
  batchId: number | null;
  title: string;
  items: TdUnlockBrief[];
}

interface Props {
  correctionRequests: PaginatedResponse<CorrectionBrief>;
  tdUnlockRequests: TdUnlockBrief[];
  filters: { status: string };
}

const STATUS_TABS = [
  { value: 'pending', label: 'À corriger' },
  { value: 'corrected', label: 'Corrigés' },
  { value: 'all', label: 'Tous' },
] as const;

function formatDate(iso: string) {
  return new Intl.DateTimeFormat('fr-FR', { day: 'numeric', month: 'short' }).format(new Date(iso));
}

function groupTdByBatch(items: TdUnlockBrief[]): TdBatchGroup[] {
  const map = new Map<string, TdBatchGroup>();
  for (const item of items) {
    const key = item.batch_id != null ? `batch::${item.batch_id}` : `solo::${item.id}`;
    if (!map.has(key)) {
      map.set(key, { batchId: item.batch_id ?? null, title: item.custom_title ?? 'TD', items: [] });
    }
    map.get(key)!.items.push(item);
  }
  return Array.from(map.values());
}

export default function CorrectionsIndex({ correctionRequests, tdUnlockRequests, filters }: Props) {
  const items = correctionRequests.data;
  const showTdUnlocks = filters.status === 'pending' || filters.status === 'all';
  const tdBatchGroups = groupTdByBatch(tdUnlockRequests);

  function setStatus(status: string) {
    router.get(
      route('teacher.corrections.index'),
      { status },
      { preserveState: true, replace: true }
    );
  }

  return (
    <AppLayout>
      <Head title="Corrections" />
      <div className="max-w-3xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title="Corrections"
          breadcrumbs={[
            { label: 'Mon Bureau', href: route('teacher.bureau.index') },
            { label: 'Corrections' },
          ]}
        />

        <div className="flex gap-2">
          {STATUS_TABS.map((tab) => (
            <button
              key={tab.value}
              onClick={() => setStatus(tab.value)}
              className={`text-xs px-3 py-1.5 rounded-full font-comfortaa-bold transition-colors ${
                filters.status === tab.value
                  ? 'bg-teacher-color text-white'
                  : 'bg-secondary-color border border-border-color text-text-gray hover:text-text-color'
              }`}
            >
              {tab.label}
            </button>
          ))}
        </div>

        {/* ── TD unlock requests grouped by batch ── */}
        {showTdUnlocks && tdBatchGroups.length > 0 && (
          <div className="space-y-4">
            <SectionLabel>TD à débloquer ({tdUnlockRequests.length})</SectionLabel>
            {tdBatchGroups.map((group) => (
              <div key={group.batchId ?? `solo-${group.items[0].id}`} className="space-y-1.5">
                {/* Batch header */}
                <div className="flex items-center justify-between px-1">
                  <span className="text-xs font-comfortaa-bold text-text-color">{group.title}</span>
                  {group.batchId && (
                    <Link
                      href={route('teacher.assignations.show', {
                        type: 'td',
                        batch: group.batchId,
                      })}
                      className="flex items-center gap-1 text-[11px] font-comfortaa-bold text-teacher-color hover:underline"
                    >
                      Voir le batch <ChevronRight size={11} />
                    </Link>
                  )}
                </div>
                {/* Per-student rows */}
                <ul className="space-y-1.5">
                  {group.items.map((td) => (
                    <li
                      key={td.id}
                      className="flex items-center gap-3 px-4 py-3 rounded-2xl bg-secondary-color border border-warning-color/20"
                    >
                      <TypeBadge type="td" size="md" />
                      <div className="flex-1 min-w-0">
                        {td.student && (
                          <p className="font-comfortaa-bold text-text-color text-sm truncate">
                            {td.student.first_name} {td.student.last_name}
                          </p>
                        )}
                        {td.custom_level && (
                          <p className="text-xs text-student-color mt-0.5">{td.custom_level}</p>
                        )}
                      </div>
                      <Button
                        type="button"
                        variant="teacher"
                        size="sm"
                        icon={LockOpen}
                        onClick={() => router.patch(route('teacher.td.unlock', td.id))}
                      >
                        Débloquer
                      </Button>
                    </li>
                  ))}
                </ul>
              </div>
            ))}
          </div>
        )}

        {/* ── DS/DM correction requests ── */}
        {items.length === 0 && (!showTdUnlocks || tdBatchGroups.length === 0) ? (
          <div className="flex flex-col items-center justify-center py-16 gap-3 text-text-gray">
            <ClipboardList size={32} className="opacity-40" />
            <p className="text-sm">Aucune correction à traiter.</p>
          </div>
        ) : items.length > 0 ? (
          <div className="mm-card mm-card-style-plain overflow-hidden">
            <div className="flex items-center justify-between px-5 py-4 border-b border-border-color">
              <SectionLabel>
                {items.length} correction{items.length > 1 ? 's' : ''}
              </SectionLabel>
            </div>
            <ul className="divide-y divide-border-color">
              {items.map((cr) => {
                const subject = cr.ds ?? cr.dm;
                const type = cr.ds ? 'ds' : 'dm';
                return (
                  <li key={cr.id}>
                    <Link
                      href={route('teacher.corrections.show', cr.id)}
                      className="flex items-center gap-3 px-4 py-3 hover:bg-primary-color/40 transition-colors group"
                    >
                      <TypeBadge type={type} size="md" />
                      <div className="flex-1 min-w-0">
                        <p className="font-comfortaa-bold text-text-color truncate">
                          {subject?.custom_title ?? type.toUpperCase()}
                        </p>
                        {cr.user && (
                          <p className="text-xs text-text-gray mt-0.5">
                            {cr.user.first_name} {cr.user.last_name}
                          </p>
                        )}
                      </div>
                      <div className="flex items-center gap-3 shrink-0">
                        <span className="flex items-center gap-1 text-[11px] text-text-gray">
                          <Clock size={11} />
                          {formatDate(cr.created_at)}
                        </span>
                        <span
                          className={`text-[10px] px-2 py-0.5 rounded-full font-comfortaa-bold ${
                            cr.status === 'pending'
                              ? 'bg-warning-color/10 text-warning-color'
                              : 'bg-success-color/10 text-success-color'
                          }`}
                        >
                          {cr.status === 'pending' ? 'À corriger' : 'Corrigé'}
                        </span>
                        <ChevronRight
                          size={14}
                          className="text-text-gray group-hover:text-teacher-color transition-colors"
                        />
                      </div>
                    </Link>
                  </li>
                );
              })}
            </ul>
          </div>
        ) : null}
      </div>
    </AppLayout>
  );
}
