import { useState } from 'react';
import { Head } from '@inertiajs/react';
import { Archive, BookOpen } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import BatchList from '@/Pages/Teacher/Bureau/Partials/BatchList';
import BatchTypeTabBar from '@/Pages/Teacher/Bureau/Partials/BatchTypeTabBar';
import BureauDevoirsFilters from '@/Pages/Teacher/Bureau/Partials/BureauDevoirsFilters';
import BureauDevoirsHero from '@/Pages/Teacher/Bureau/Partials/BureauDevoirsHero';
import {
  areBatchListsEmpty,
  buildBatchLists,
  countBatches,
  countPendingActions,
  filterBatchLists,
} from '@/Pages/Teacher/Bureau/Partials/bureauDevoirsUtils';
import {
  BATCH_COLUMNS,
  BATCH_TYPES,
  type TeacherGroupOption,
  type ViewMode,
} from '@/Pages/Teacher/Bureau/Partials/bureauDevoirsTypes';
import type { BatchBrief, BatchType } from '@/types/api';

interface Props {
  dsBatches: BatchBrief[];
  tdBatches: BatchBrief[];
  dmBatches: BatchBrief[];
  groups: TeacherGroupOption[];
}

export default function BureauDevoirs({ dsBatches, tdBatches, dmBatches, groups }: Props) {
  const [search, setSearch] = useState('');
  const [view, setView] = useState<ViewMode>('active');
  const [pendingOnly, setPendingOnly] = useState(false);
  const [groupId, setGroupId] = useState<number | null>(null);
  const [activeTab, setActiveTab] = useState<BatchType>('ds');

  const active = buildBatchLists(dsBatches, dmBatches, tdBatches, false);
  const archived = buildBatchLists(dsBatches, dmBatches, tdBatches, true);
  const totalActive = countBatches(active);
  const totalArchived = countBatches(archived);
  const totalPending = countPendingActions(active);
  const currentBaseLists = view === 'active' ? active : archived;
  const currentLists = filterBatchLists(
    currentBaseLists,
    search,
    groupId,
    view === 'active' && pendingOnly
  );
  const isEmpty = areBatchListsEmpty(currentLists);

  function resetFilters() {
    setView('active');
    setPendingOnly(false);
    setSearch('');
    setGroupId(null);
  }

  function changeView(nextView: ViewMode) {
    setView(nextView);
    setPendingOnly(false);
    setSearch('');
  }

  return (
    <AppLayout hideFooter>
      <Head title="Devoirs envoyés" />

      <div className="flex flex-col h-[calc(100vh-72px)] overflow-hidden">
        {/* Header */}
        <div className="flex-shrink-0 px-4 pt-5 pb-4 space-y-3 max-w-6xl mx-auto w-full">
          <BureauDevoirsHero
            totalActive={totalActive}
            totalPending={totalPending}
            totalArchived={totalArchived}
          />

          <BureauDevoirsFilters
            view={view}
            pendingOnly={pendingOnly}
            totalPending={totalPending}
            groups={groups}
            groupId={groupId}
            search={search}
            onViewChange={changeView}
            onPendingOnlyChange={setPendingOnly}
            onGroupChange={setGroupId}
            onSearchChange={setSearch}
          />
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
                onClick={resetFilters}
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
                  types={BATCH_TYPES}
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
                {BATCH_COLUMNS.map(({ type, label, short }, i) => (
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
