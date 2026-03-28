import {
  DndContext,
  closestCenter,
  KeyboardSensor,
  PointerSensor,
  useSensor,
  useSensors,
  DragEndEvent,
} from '@dnd-kit/core';
import {
  SortableContext,
  sortableKeyboardCoordinates,
  verticalListSortingStrategy,
  arrayMove,
} from '@dnd-kit/sortable';
import { restrictToVerticalAxis, restrictToParentElement } from '@dnd-kit/modifiers';
import { Clock, BookOpen, Users } from 'lucide-react';
import { DSPreviewItem as DSPreviewItemType, DEFAULT_EXERCISE_MINUTES } from '@/types/models';
import DSPreviewItem from '@/Pages/Teacher/DS/Partials/DSPreviewItem';
import Button from '@/Components/Common/UI/Button';
import EmptyState from '@/Components/Common/UI/EmptyState';

interface Props {
  items: DSPreviewItemType[];
  onReorder: (items: DSPreviewItemType[]) => void;
  onRemove: (uid: string) => void;
  onAssign: () => void;
}

function formatTime(totalMinutes: number): string {
  if (totalMinutes === 0) return '0 min';
  const h = Math.floor(totalMinutes / 60);
  const m = totalMinutes % 60;
  if (h === 0) return `${m} min`;
  if (m === 0) return `${h}h`;
  return `${h}h${String(m).padStart(2, '0')}`;
}

export default function DSPreview({ items, onReorder, onRemove, onAssign }: Props) {
  const sensors = useSensors(
    useSensor(PointerSensor, { activationConstraint: { distance: 5 } }),
    useSensor(KeyboardSensor, { coordinateGetter: sortableKeyboardCoordinates })
  );

  const totalMinutes = items.reduce((sum, i) => {
    if (i.item.kind === 'problem') return sum + (i.item.time ?? 0);
    return sum + DEFAULT_EXERCISE_MINUTES;
  }, 0);

  function handleDragEnd(event: DragEndEvent) {
    const { active, over } = event;
    if (over && active.id !== over.id) {
      const oldIndex = items.findIndex((i) => i.uid === active.id);
      const newIndex = items.findIndex((i) => i.uid === over.id);
      onReorder(arrayMove(items, oldIndex, newIndex));
    }
  }

  return (
    <div className="flex flex-col h-full overflow-hidden">
      {/* ── Header ── */}
      <div className="px-2.5 py-2 border-b border-border-color flex-shrink-0">
        <div className="flex items-center justify-between">
          <h2 className="text-xs font-comfortaa-bold text-text-color">
            Sommaire
            {items.length > 0 && (
              <span className="ml-1 text-[10px] font-normal text-text-gray">({items.length})</span>
            )}
          </h2>

          {totalMinutes > 0 && (
            <span className="flex items-center gap-0.5 text-xs font-comfortaa-bold text-teacher-color">
              <Clock size={11} />
              {formatTime(totalMinutes)}
            </span>
          )}
        </div>
      </div>

      {/* ── Liste triable ── */}
      <div className="flex-1 overflow-y-auto p-2 custom-scrollbar">
        {items.length === 0 ? (
          <EmptyState
            icon={BookOpen}
            description="Clique sur un exercice pour l'ajouter au DS"
            accentColor="teacher"
          />
        ) : (
          <DndContext
            sensors={sensors}
            collisionDetection={closestCenter}
            modifiers={[restrictToVerticalAxis, restrictToParentElement]}
            onDragEnd={handleDragEnd}
          >
            <SortableContext items={items.map((i) => i.uid)} strategy={verticalListSortingStrategy}>
              <div className="space-y-1">
                {items.map((item, index) => (
                  <DSPreviewItem key={item.uid} item={item} index={index} onRemove={onRemove} />
                ))}
              </div>
            </SortableContext>
          </DndContext>
        )}
      </div>

      {/* ── Actions ── */}
      <div className="p-2.5 border-t border-border-color flex-shrink-0 space-y-1.5">
        <Button
          onClick={onAssign}
          disabled={items.length === 0}
          size="sm"
          className="w-full justify-center gap-1.5"
          variant="primary"
        >
          <Users size={13} />
          Assigner
        </Button>

        <button
          type="button"
          disabled
          title="Bientôt disponible"
          className="w-full py-1.5 px-3 rounded-lg border border-dashed border-border-color text-text-gray text-xs cursor-not-allowed opacity-50"
        >
          Sauvegarder
        </button>
      </div>
    </div>
  );
}
