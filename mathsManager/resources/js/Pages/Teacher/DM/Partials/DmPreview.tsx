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
import { BookOpen, Users } from 'lucide-react';
import { DSPreviewItem as DSPreviewItemType } from '@/types/models';
import DmPreviewItem from '@/Pages/Teacher/DM/Partials/DmPreviewItem';
import Button from '@/Components/Common/UI/Button';
import EmptyState from '@/Components/Common/UI/EmptyState';

interface Props {
  items: DSPreviewItemType[];
  onReorder: (items: DSPreviewItemType[]) => void;
  onRemove: (uid: string) => void;
  onAssign: () => void;
}

export default function DmPreview({ items, onReorder, onRemove, onAssign }: Props) {
  const sensors = useSensors(
    useSensor(PointerSensor, { activationConstraint: { distance: 5 } }),
    useSensor(KeyboardSensor, { coordinateGetter: sortableKeyboardCoordinates })
  );

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
      <div className="px-2.5 py-2 border-b border-border-color flex-shrink-0">
        <h2 className="text-xs font-comfortaa-bold text-text-color">
          Sommaire
          {items.length > 0 && (
            <span className="ml-1 text-xxs font-normal text-text-gray">({items.length})</span>
          )}
        </h2>
      </div>

      <div className="flex-1 overflow-y-auto p-2 custom-scrollbar">
        {items.length === 0 ? (
          <EmptyState
            icon={BookOpen}
            description="Clique sur un exercice pour l'ajouter au DM"
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
                  <DmPreviewItem key={item.uid} item={item} index={index} onRemove={onRemove} />
                ))}
              </div>
            </SortableContext>
          </DndContext>
        )}
      </div>

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
