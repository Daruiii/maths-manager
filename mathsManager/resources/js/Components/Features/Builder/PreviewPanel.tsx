import { useState } from 'react';
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
import { Calendar, ChevronDown, Clock, BookOpen, Users, Save, X } from 'lucide-react';
import { DSPreviewItem as DSPreviewItemType, DEFAULT_EXERCISE_MINUTES } from '@/types/models';
import PreviewItem from '@/Components/Features/Builder/PreviewItem';
import Button from '@/Components/Common/UI/Button';
import EmptyState from '@/Components/Common/UI/EmptyState';

interface Props {
  items: DSPreviewItemType[];
  onReorder: (items: DSPreviewItemType[]) => void;
  onRemove: (uid: string) => void;
  onAssign: () => void;
  onSave?: () => void;
  entityLabel: string;
  showTime?: boolean;
  dueDate?: string;
  onDueDateChange?: (date: string) => void;
}

function formatTime(totalMinutes: number): string {
  if (totalMinutes === 0) return '0 min';
  const h = Math.floor(totalMinutes / 60);
  const m = totalMinutes % 60;
  if (h === 0) return `${m} min`;
  if (m === 0) return `${h}h`;
  return `${h}h${String(m).padStart(2, '0')}`;
}

export default function PreviewPanel({
  items,
  onReorder,
  onRemove,
  onAssign,
  onSave,
  entityLabel,
  showTime = false,
  dueDate = '',
  onDueDateChange,
}: Props) {
  const [showPicker, setShowPicker] = useState(false);

  const formattedDueDate = dueDate
    ? new Date(dueDate + 'T00:00:00').toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
      })
    : null;

  const sensors = useSensors(
    useSensor(PointerSensor, { activationConstraint: { distance: 5 } }),
    useSensor(KeyboardSensor, { coordinateGetter: sortableKeyboardCoordinates })
  );

  const totalMinutes = showTime
    ? items.reduce((sum, i) => {
        if (i.item.kind === 'problem') return sum + (i.item.time ?? 0);
        return sum + DEFAULT_EXERCISE_MINUTES;
      }, 0)
    : 0;

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
        <div className="flex items-center justify-between">
          <h2 className="text-xs font-comfortaa-bold text-text-color">
            Sommaire
            {items.length > 0 && (
              <span className="ml-1 text-xxs font-normal text-text-gray">({items.length})</span>
            )}
          </h2>

          {showTime && totalMinutes > 0 && (
            <span className="flex items-center gap-0.5 text-xs font-comfortaa-bold text-teacher-color">
              <Clock size={11} />
              {formatTime(totalMinutes)}
            </span>
          )}
        </div>
      </div>

      <div className="flex-1 overflow-y-auto p-2 custom-scrollbar">
        {items.length === 0 ? (
          <EmptyState
            icon={BookOpen}
            description={`Clique sur un exercice pour l'ajouter au ${entityLabel}`}
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
                  <PreviewItem
                    key={item.uid}
                    item={item}
                    index={index}
                    onRemove={onRemove}
                    showTime={showTime}
                  />
                ))}
              </div>
            </SortableContext>
          </DndContext>
        )}
      </div>

      <div className="p-2.5 border-t border-border-color flex-shrink-0 space-y-1.5">
        {onDueDateChange && (
          <div className="pb-0.5">
            {dueDate && !showPicker ? (
              <div className="flex items-center justify-between rounded-xl border border-teacher-color/30 bg-teacher-color/5 px-3 py-1.5">
                <button
                  type="button"
                  onClick={() => setShowPicker(true)}
                  className="flex items-center gap-1.5 text-xs text-teacher-color font-comfortaa-bold"
                >
                  <Calendar size={11} />
                  Échéance : {formattedDueDate}
                </button>
                <button
                  type="button"
                  onClick={() => onDueDateChange('')}
                  className="text-text-gray hover:text-text-color transition-colors"
                  aria-label="Supprimer la date"
                >
                  <X size={11} />
                </button>
              </div>
            ) : showPicker ? (
              <input
                type="date"
                autoFocus
                min={new Date().toISOString().slice(0, 10)}
                value={dueDate}
                onChange={(e) => {
                  onDueDateChange(e.target.value);
                  setShowPicker(false);
                }}
                onBlur={() => setShowPicker(false)}
                className="w-full rounded-xl border border-teacher-color/50 bg-surface-color px-3 py-1.5 text-sm text-text-color focus:outline-none focus:border-teacher-color"
              />
            ) : (
              <button
                type="button"
                onClick={() => setShowPicker(true)}
                className="flex w-full items-center gap-1.5 rounded-xl border border-dashed border-border-color px-3 py-1.5 text-xs text-text-gray hover:border-teacher-color/40 hover:text-teacher-color transition-colors"
              >
                <Calendar size={11} />
                <span className="flex-1 text-left">Ajouter une date limite</span>
                <ChevronDown size={11} />
              </button>
            )}
          </div>
        )}
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

        <Button
          onClick={onSave}
          disabled={!onSave || items.length === 0}
          size="sm"
          className="w-full justify-center gap-1.5 border border-teacher-color text-teacher-color hover:bg-teacher-color/10"
          variant="ghost"
        >
          <Save size={13} />
          Sauvegarder
        </Button>
      </div>
    </div>
  );
}
