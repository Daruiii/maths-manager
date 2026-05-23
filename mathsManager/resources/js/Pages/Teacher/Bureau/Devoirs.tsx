import { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import { Archive, BookOpen, ChevronLeft } from 'lucide-react';
import SearchInput from '@/Components/Common/Form/SearchInput';
import AppLayout from '@/Layouts/AppLayout';
import BatchRow from './Partials/BatchRow';
import BatchTypeTabBar from './Partials/BatchTypeTabBar';
import Select from '@/Components/Common/Form/Select';
import type { BatchBrief, BatchType } from '@/types/api';

interface Group {
  id: number;
  name: string;
}

interface Props {
  dsBatches: BatchBrief[];
  tdBatches: BatchBrief[];
  dmBatches: BatchBrief[];
  groups: Group[];
}

type ViewMode = 'active' | 'archived';

const COLUMNS: { type: BatchType; label: string; short: string }[] = [
  { type: 'ds', label: 'Devoirs Surveillés', short: 'DS' },
  { type: 'dm', label: 'Devoirs Maison', short: 'DM' },
  { type: 'td', label: 'Travaux Dirigés', short: 'TD' },
];

const TYPES = COLUMNS.map((c) => c.type);

function matchFilters(b: BatchBrief, q: string, groupId: number | null): boolean {
  if (q.trim() && !b.title.toLowerCase().includes(q.toLowerCase())) return false;
  if (groupId !== null && !b.group_ids.includes(groupId)) return false;
  return true;
}

function BatchList({
  list,
  type,
  emptyLabel,
}: {
  list: BatchBrief[];
  type: BatchType;
  emptyLabel: string;
}) {
  if (list.length === 0)
    return (
      <div className="flex items-center justify-center py-8 text-text-gray text-xs border border-dashed border-border-color rounded-xl">
        {emptyLabel}
      </div>
    );
  return (
    <div className="flex-1 min-h-0 overflow-y-auto space-y-2 pr-1 h-full">
      {list.map((batch) => (
        <BatchRow key={batch.id} batch={batch} type={type} />
      ))}
    </div>
  );
}

export default function BureauDevoirs({ dsBatches, tdBatches, dmBatches, groups }: Props) {
  const [search, setSearch] = useState('');
  const [view, setView] = useState<ViewMode>('active');
  const [pendingOnly, setPendingOnly] = useState(false);
  const [groupId, setGroupId] = useState<number | null>(null);
  const [activeTab, setActiveTab] = useState<BatchType>('ds');

  const active: Record<BatchType, BatchBrief[]> = {
    ds: dsBatches.filter((b) => !b.is_archived),
    dm: dmBatches.filter((b) => !b.is_archived),
    td: tdBatches.filter((b) => !b.is_archived),
  };

  const archived: Record<BatchType, BatchBrief[]> = {
    ds: dsBatches.filter((b) => b.is_archived),
    dm: dmBatches.filter((b) => b.is_archived),
    td: tdBatches.filter((b) => b.is_archived),
  };

  const totalActive = active.ds.length + active.dm.length + active.td.length;
  const totalArchived = archived.ds.length + archived.dm.length + archived.td.length;
  const totalPending = [...active.ds, ...active.dm, ...active.td].reduce(
    (s, b) => s + b.pending_actions,
    0
  );

  const byType: Record<BatchType, BatchBrief[]> = {
    ds: active.ds.filter(
      (b) => matchFilters(b, search, groupId) && (!pendingOnly || b.pending_actions > 0)
    ),
    dm: active.dm.filter(
      (b) => matchFilters(b, search, groupId) && (!pendingOnly || b.pending_actions > 0)
    ),
    td: active.td.filter(
      (b) => matchFilters(b, search, groupId) && (!pendingOnly || b.pending_actions > 0)
    ),
  };

  const archivedByType: Record<BatchType, BatchBrief[]> = {
    ds: archived.ds.filter((b) => matchFilters(b, search, groupId)),
    dm: archived.dm.filter((b) => matchFilters(b, search, groupId)),
    td: archived.td.filter((b) => matchFilters(b, search, groupId)),
  };

  const currentLists = view === 'active' ? byType : archivedByType;
  const isEmpty = TYPES.every((t) => currentLists[t].length === 0);

  return (
    <AppLayout hideFooter>
      <Head title="Devoirs envoyés" />

      <div className="flex flex-col h-[calc(100vh-72px)] overflow-hidden">
        {/* Header */}
        <div className="flex-shrink-0 px-4 pt-5 pb-4 space-y-3 max-w-6xl mx-auto w-full">
          {/* Hero */}
          <div className="relative mm-card mm-card-style-halo mm-card-accent-teacher rounded-3xl px-5 sm:px-8 py-4 overflow-hidden">
            <div
              className="absolute inset-0 overflow-hidden rounded-3xl pointer-events-none select-none"
              aria-hidden
            >
              <div className="absolute inset-0 flex items-center justify-end pr-8">
                <span className="text-[130px] font-cmu-serif text-text-color opacity-[0.04] leading-none">
                  ∑
                </span>
              </div>
            </div>
            <div className="relative flex items-center justify-between gap-4">
              <div className="flex-1 min-w-0 space-y-0.5">
                <Link
                  href={route('teacher.bureau.index')}
                  className="inline-flex items-center gap-1 text-[11px] font-comfortaa-bold text-teacher-color uppercase tracking-widest hover:opacity-70 transition-opacity"
                >
                  <ChevronLeft size={12} />
                  Mon Bureau
                </Link>
                <h1 className="text-lg sm:text-2xl font-comfortaa-bold text-text-color">
                  Devoirs envoyés
                </h1>
              </div>
              <div className="flex items-center gap-4 shrink-0">
                <div className="text-right">
                  <p className="text-xl sm:text-2xl font-cmu-serif text-text-color leading-none">
                    {totalActive}
                  </p>
                  <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest mt-0.5">
                    Actif{totalActive > 1 ? 's' : ''}
                  </p>
                </div>
                {totalPending > 0 && (
                  <div className="text-right">
                    <p className="text-xl sm:text-2xl font-cmu-serif text-warning-color leading-none">
                      {totalPending}
                    </p>
                    <p className="text-[10px] font-comfortaa-bold text-warning-color uppercase tracking-widest mt-0.5">
                      À traiter
                    </p>
                  </div>
                )}
                {totalArchived > 0 && (
                  <div className="text-right hidden sm:block">
                    <p className="text-xl sm:text-2xl font-cmu-serif text-text-gray/50 leading-none">
                      {totalArchived}
                    </p>
                    <p className="text-[10px] font-comfortaa-bold text-text-gray/50 uppercase tracking-widest mt-0.5">
                      Archivés
                    </p>
                  </div>
                )}
              </div>
            </div>
          </div>

          {/* Filter bar */}
          <div className="flex items-center gap-2 flex-wrap justify-between">
            <div className="flex items-center gap-2 flex-wrap">
              <div className="flex rounded-xl border border-border-color overflow-hidden text-[11px] font-comfortaa-bold">
                {(['active', 'archived'] as ViewMode[]).map((v) => (
                  <button
                    key={v}
                    onClick={() => {
                      setView(v);
                      setPendingOnly(false);
                      setSearch('');
                    }}
                    className={`px-3 py-1.5 transition-colors ${view === v ? 'bg-teacher-color/10 text-teacher-color' : 'text-text-gray hover:text-text-color'}`}
                  >
                    {v === 'active' ? 'Actifs' : 'Archivés'}
                  </button>
                ))}
              </div>
              {view === 'active' && totalPending > 0 && (
                <button
                  onClick={() => setPendingOnly((p) => !p)}
                  className={`flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-[11px] font-comfortaa-bold transition-colors ${pendingOnly ? 'bg-warning-color/15 text-warning-color border-warning-color/30' : 'border-border-color text-text-gray hover:text-text-color'}`}
                >
                  À traiter{pendingOnly && <span className="font-cmu-serif">{totalPending}</span>}
                </button>
              )}
              {groups.length > 0 && (
                <Select
                  size="sm"
                  searchable
                  value={groupId !== null ? String(groupId) : ''}
                  onChange={(v) => setGroupId(v ? Number(v) : null)}
                  placeholder="Toutes les classes"
                  searchPlaceholder="Chercher une classe…"
                  options={[
                    { value: '', label: 'Toutes les classes' },
                    ...groups.map((g) => ({ value: String(g.id), label: g.name })),
                  ]}
                  className="w-40"
                />
              )}
            </div>
            <SearchInput
              value={search}
              onChange={setSearch}
              placeholder="Rechercher un devoir…"
              className="w-40 sm:w-52"
            />
          </div>
        </div>

        {/* Content */}
        <div className="flex-1 min-h-0 px-4 pb-4 max-w-6xl mx-auto w-full">
          {totalActive === 0 && view === 'active' ? (
            <div className="flex flex-col items-center justify-center h-full gap-3 text-text-gray">
              <BookOpen size={32} className="opacity-30" />
              <p className="text-sm">Aucun devoir envoyé pour l'instant.</p>
            </div>
          ) : totalArchived === 0 && view === 'archived' ? (
            <div className="flex flex-col items-center justify-center h-full gap-3 text-text-gray">
              <Archive size={32} className="opacity-30" />
              <p className="text-sm">Aucun devoir archivé.</p>
            </div>
          ) : isEmpty ? (
            <div className="flex flex-col items-center justify-center h-full gap-3 text-text-gray">
              <p className="text-sm">Aucun résultat pour ces filtres.</p>
              <button
                onClick={() => {
                  setView('active');
                  setPendingOnly(false);
                  setSearch('');
                  setGroupId(null);
                }}
                className="text-xs text-teacher-color hover:underline font-comfortaa-bold"
              >
                Réinitialiser
              </button>
            </div>
          ) : (
            <>
              {/* Mobile: tab bar + single column */}
              <div className="md:hidden flex flex-col h-full">
                <BatchTypeTabBar
                  types={TYPES}
                  active={activeTab}
                  lists={currentLists}
                  onChange={setActiveTab}
                />
                <BatchList
                  list={currentLists[activeTab]}
                  type={activeTab}
                  emptyLabel={`Aucun ${activeTab.toUpperCase()}${pendingOnly ? ' à traiter' : ''}`}
                />
              </div>

              {/* Desktop: 3-column grid */}
              <div className="hidden md:grid md:grid-cols-3 gap-4 h-full">
                {COLUMNS.map(({ type, label, short }, i) => (
                  <div
                    key={type}
                    className="flex flex-col min-h-0 animate-fadeInUp"
                    style={{ animationDelay: `${i * 40}ms` }}
                  >
                    <div className="flex items-center justify-between mb-3 shrink-0">
                      <p className="mm-section-header">{label}</p>
                      <span className="font-cmu-serif text-sm text-text-gray">
                        {currentLists[type].length}
                      </span>
                    </div>
                    <BatchList
                      list={currentLists[type]}
                      type={type}
                      emptyLabel={`Aucun ${short}${pendingOnly ? ' à traiter' : ''}`}
                    />
                  </div>
                ))}
              </div>
            </>
          )}
        </div>
      </div>
    </AppLayout>
  );
}
