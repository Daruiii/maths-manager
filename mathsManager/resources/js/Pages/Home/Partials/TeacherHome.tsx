import { Link } from '@inertiajs/react';
import { ChevronRight, CheckCircle2, Bell } from 'lucide-react';
import { useAuth } from '@/Hooks/Auth/useAuth';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
import TeacherSidebar from '@/Pages/Home/Partials/TeacherSidebar';
import {
  BatchCorrectionItem,
  BatchUnlockItem,
  type BatchGroup,
} from '@/Pages/Home/Partials/PendingFeedItems';
import type { HomePendingCorrectionItem, HomeUnlockRequestItem } from '@/types';
import type { BatchType } from '@/types/api';

function groupByBatch(items: HomePendingCorrectionItem[]): BatchGroup[] {
  const map = new Map<string, BatchGroup>();
  for (const item of items) {
    const key = item.batch_id ? `${item.subject_type}::${item.batch_id}` : `correction::${item.id}`;
    if (!map.has(key)) {
      map.set(key, {
        key,
        title: item.subject_title,
        type: item.subject_type,
        href: item.batch_url ?? route('teacher.corrections.show', item.id),
        items: [],
      });
    }
    map.get(key)!.items.push(item);
  }
  return Array.from(map.values());
}

interface Props {
  pendingCorrections?: { count: number; items: HomePendingCorrectionItem[] };
  unlockRequests?: { count: number; items: HomeUnlockRequestItem[] };
  pendingTeachersCount?: number;
  activeStudentsCount?: number;
  assignedThisMonth?: number;
  activeBatches?: { ds: number; dm: number; td: number };
}

const BATCH_SHORTCUTS: { type: BatchType; label: string }[] = [
  { type: 'ds', label: 'DS' },
  { type: 'dm', label: 'DM' },
  { type: 'td', label: 'TD' },
];

export default function TeacherHome({
  pendingCorrections,
  unlockRequests,
  pendingTeachersCount,
  activeStudentsCount,
  assignedThisMonth,
  activeBatches,
}: Props) {
  const { user } = useAuth();
  const firstName = user?.first_name ?? '';
  const corrCount = pendingCorrections?.count ?? 0;
  const unlockCount = unlockRequests?.count ?? 0;
  const pendingTotal = corrCount + unlockCount;
  const allClear = pendingTotal === 0;
  const heroMessage = allClear
    ? 'Tout est à jour.'
    : corrCount === 1 && unlockCount === 0
      ? 'Une copie attend ta correction.'
      : corrCount > 1 && unlockCount === 0
        ? `${corrCount} copies attendent ta correction.`
        : unlockCount > 0 && corrCount === 0
          ? `${unlockCount} déblocage${unlockCount > 1 ? 's' : ''} en attente.`
          : `${corrCount} copies · ${unlockCount} déblocage${unlockCount > 1 ? 's' : ''}.`;

  const batches = groupByBatch(pendingCorrections?.items ?? []);
  const unlockBatchItems = unlockRequests?.items ?? [];
  const displayTotal = batches.length + unlockBatchItems.length;
  const heroStats = [
    {
      value: activeStudentsCount ?? 0,
      label: 'élèves',
    },
    {
      value: corrCount,
      label: 'corrections',
    },
    {
      value: assignedThisMonth ?? 0,
      label: 'ce mois',
    },
  ];

  return (
    <div className="space-y-6">
      {/* ── Admin banner ── */}
      {!!pendingTeachersCount && pendingTeachersCount > 0 && (
        <Link
          href={route('admin.applications.index')}
          className="flex items-center justify-between gap-4 px-4 py-3 bg-admin-color/10 border border-admin-color/20 rounded-2xl hover:bg-admin-color/15 transition-colors animate-fadeInUp"
        >
          <div className="flex items-center gap-3">
            <Bell size={16} className="text-admin-color shrink-0" />
            <p className="text-sm font-comfortaa-bold text-admin-color">
              {pendingTeachersCount} candidature{pendingTeachersCount > 1 ? 's' : ''} professeur
              {pendingTeachersCount > 1 ? 's' : ''} en attente
            </p>
          </div>
          <ChevronRight size={14} className="text-admin-color shrink-0" />
        </Link>
      )}

      {/* ── Hero ── */}
      <div className="relative mm-card mm-card-style-halo mm-card-accent-teacher rounded-3xl px-8 py-6 overflow-hidden animate-fadeIn">
        <div
          className="absolute inset-0 overflow-hidden rounded-3xl pointer-events-none select-none"
          aria-hidden
        >
          <div className="absolute inset-0 flex items-end justify-end pr-7">
            <span className="text-[140px] font-cmu-serif text-text-color opacity-[0.04] leading-none">
              π
            </span>
          </div>
        </div>
        <div className="relative flex items-center gap-8">
          <div className="flex-1 min-w-0 space-y-3">
            <p className="text-[11px] font-comfortaa-bold text-teacher-color uppercase tracking-widest">
              Bonjour {firstName} 👋
            </p>
            <h1 className="text-2xl sm:text-3xl font-comfortaa-bold text-text-color">
              {heroMessage}
            </h1>
            {!allClear && (
              <Link
                href={route('teacher.corrections.index')}
                className="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-teacher-color text-white text-sm font-comfortaa-bold shadow-warm-xs hover:opacity-90 transition-opacity"
              >
                Traiter les corrections <ChevronRight size={14} />
              </Link>
            )}
          </div>
          <div className="hidden sm:flex flex-col items-end gap-3 shrink-0 pr-5">
            {heroStats.map((stat) => (
              <div key={stat.label} className="text-right">
                <p className="text-2xl font-cmu-serif text-text-color leading-none">{stat.value}</p>
                <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest mt-0.5">
                  {stat.label}
                </p>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* ── 2-col grid ── */}
      <div className="grid grid-cols-1 lg:grid-cols-[1fr_260px] gap-6 items-start">
        <div className="space-y-4">
          {allClear ? (
            <div className="flex flex-col items-center gap-4 py-10 text-center bg-secondary-color border border-border-color rounded-2xl animate-fadeInUp">
              <div className="w-14 h-14 rounded-2xl bg-success-color/10 border border-success-color/20 flex items-center justify-center">
                <CheckCircle2 size={24} className="text-success-color" />
              </div>
              <div>
                <p className="text-sm font-comfortaa-bold text-text-color">Tout est traité</p>
                <p className="text-xs text-text-gray mt-1">Prochaine étape dans ton bureau.</p>
              </div>
              <Link
                href={route('teacher.bureau.index')}
                className="inline-flex items-center gap-1.5 text-xs font-comfortaa-bold text-teacher-color border border-teacher-color/30 bg-teacher-color/5 hover:bg-teacher-color/10 rounded-xl px-3 py-1.5 transition-colors"
              >
                Aller au bureau <ChevronRight size={12} />
              </Link>
            </div>
          ) : (
            <div className="bg-secondary-color border border-border-color rounded-2xl overflow-hidden">
              <div className="flex items-center justify-between px-4 py-3 border-b border-border-color">
                <span className="text-xs font-comfortaa-bold text-text-color uppercase tracking-wider">
                  À traiter
                </span>
                <span className="text-xs text-text-gray bg-surface-color border border-border-color px-2 py-0.5 rounded-full">
                  {displayTotal}
                </span>
              </div>
              <div className="p-2 space-y-0.5">
                {batches.map((batch, i) => (
                  <BatchCorrectionItem key={batch.key} batch={batch} index={i} />
                ))}
                {unlockBatchItems.map((item, i) => (
                  <BatchUnlockItem
                    key={item.batch_id ?? i}
                    item={item}
                    index={batches.length + i}
                  />
                ))}
              </div>
              {displayTotal > 5 && (
                <div className="px-4 py-2.5 border-t border-border-color">
                  <Link
                    href={route('teacher.corrections.index')}
                    className="text-xs font-comfortaa-bold text-teacher-color hover:underline flex items-center gap-1"
                  >
                    Voir tout ({displayTotal}) <ChevronRight size={12} />
                  </Link>
                </div>
              )}
            </div>
          )}

          {/* Raccourcis devoirs */}
          <Link
            href={route('teacher.bureau.devoirs')}
            className="block bg-secondary-color border border-border-color rounded-2xl overflow-hidden hover:shadow-warm-sm transition-all animate-fadeInUp group"
            style={{ animationDelay: '80ms' }}
          >
            <div className="flex items-center justify-between px-4 py-3 border-b border-border-color">
              <span className="text-xs font-comfortaa-bold text-text-color uppercase tracking-wider">
                Devoirs envoyés
              </span>
              <ChevronRight
                size={13}
                className="text-text-gray group-hover:text-teacher-color transition-colors"
              />
            </div>
            <div className="grid grid-cols-3 divide-x divide-border-color">
              {BATCH_SHORTCUTS.map(({ type }) => (
                <div key={type} className="flex flex-col items-center gap-1.5 py-3">
                  <TypeBadge type={type} size="sm" />
                  <span className="font-cmu-serif text-xl text-text-color leading-none">
                    {activeBatches?.[type] ?? 0}
                  </span>
                </div>
              ))}
            </div>
          </Link>
        </div>

        <TeacherSidebar assignedThisMonth={assignedThisMonth} />
      </div>
    </div>
  );
}
