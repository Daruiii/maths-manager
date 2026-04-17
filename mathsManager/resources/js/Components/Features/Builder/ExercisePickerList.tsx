import { useCallback, useRef } from 'react';
import { Loader2, SearchX, Lock } from 'lucide-react';
import { PickableItem } from '@/types/models';
import PickerCard from '@/Components/Features/Builder/PickerCard';
import PickerPreviewPortal from '@/Components/Features/Builder/PickerPreviewPortal';
import EmptyState from '@/Components/Common/UI/EmptyState';
import { PickerTab } from '@/Constants/ds';
import { usePickerPreview } from '@/Hooks/Builder/usePickerPreview';

interface Props {
  tab: PickerTab;
  items: PickableItem[];
  selectedIds: Set<string>;
  loading: boolean;
  loadingMore: boolean;
  hasMore: boolean;
  error: string | null;
  onToggle: (item: PickableItem) => void;
  onLoadMore: () => void;
  onResetFilters: () => void;
  showResetFilters: boolean;
}

/**
 * ExercisePickerList — Composant interne du Picker gérant l'affichage de la liste
 * avec scroll infini, états de chargement/erreur, et preview au survol/tap.
 */
export default function ExercisePickerList({
  tab,
  items,
  selectedIds,
  loading,
  loadingMore,
  hasMore,
  error,
  onToggle,
  onLoadMore,
  onResetFilters,
  showResetFilters,
}: Props) {
  const {
    isTouch,
    canPreview,
    previewState,
    handlePreview,
    scheduleClose,
    handleClose,
    handleToggle,
    cancelCloseTimer,
  } = usePickerPreview({ onToggle });

  // ── Infinite scroll ──────────────────────────────────────────────────────────
  const observerRef = useRef<InstanceType<typeof window.IntersectionObserver> | null>(null);
  const sentinelRef = useCallback(
    (el: HTMLDivElement | null) => {
      if (observerRef.current) {
        observerRef.current.disconnect();
        observerRef.current = null;
      }
      if (!el || typeof window === 'undefined' || !window.IntersectionObserver) return;
      observerRef.current = new window.IntersectionObserver(
        ([entry]) => {
          if (entry.isIntersecting) onLoadMore();
        },
        { threshold: 0.1 }
      );
      observerRef.current.observe(el);
    },
    [onLoadMore]
  );

  // ── Early returns ────────────────────────────────────────────────────────────
  if (loading) {
    return (
      <div className="flex items-center justify-center py-12">
        <Loader2 size={24} className="animate-spin text-teacher-color" />
      </div>
    );
  }

  if (error) {
    return <EmptyState icon={SearchX} description={error} accentColor="default" />;
  }

  if (items.length === 0) {
    if (tab === 'private') {
      return (
        <EmptyState icon={Lock} description="Aucun exercice privé. Créez-en depuis Mon Bureau." />
      );
    }
    return (
      <EmptyState
        icon={SearchX}
        description="Aucun exercice trouvé"
        accentColor="default"
        action={
          showResetFilters
            ? {
                label: 'Effacer les filtres',
                onClick: onResetFilters,
              }
            : undefined
        }
      />
    );
  }

  // ── List ─────────────────────────────────────────────────────────────────────
  return (
    <>
      <div
        className="flex-1 overflow-y-auto custom-scrollbar p-3 space-y-2"
        onMouseLeave={scheduleClose}
      >
        {items.map((item) => {
          const isSelected = selectedIds.has(`${item.kind}-${item.id}`);

          return (
            <PickerCard
              key={`${item.kind}-${item.id}`}
              item={item}
              isSelected={isSelected}
              onToggle={handleToggle}
              onPreview={canPreview && !isSelected ? handlePreview : undefined}
            />
          );
        })}

        {hasMore && (
          <div ref={sentinelRef} className="flex items-center justify-center py-4">
            {loadingMore ? (
              <Loader2 size={18} className="animate-spin text-teacher-color" />
            ) : (
              <div className="h-4" />
            )}
          </div>
        )}
      </div>

      <PickerPreviewPortal
        isTouch={isTouch}
        previewState={canPreview ? previewState : null}
        onClose={handleClose}
        onMouseEnter={cancelCloseTimer}
        onMouseLeave={scheduleClose}
      />
    </>
  );
}
