import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { GripVertical, X, Clock } from 'lucide-react';
import IconButton from '@/Components/Common/UI/IconButton';
import { DSPreviewItem as DSPreviewItemType } from '@/types/models';

interface Props {
  item: DSPreviewItemType;
  index: number;
  onRemove: (uid: string) => void;
}

export default function DSPreviewItem({ item, index, onRemove }: Props) {
  const { attributes, listeners, setNodeRef, transform, transition, isDragging } = useSortable({
    id: item.uid,
  });

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
  };

  const current = item.item;
  const chapterName =
    current.kind === 'problem'
      ? current.multiple_chapter?.title
      : current.kind === 'exercise'
        ? current.subchapter?.title
        : 'Privé';
  const time =
    current.kind === 'problem' ? current.time : current.kind === 'private' ? current.time : null;

  return (
    <div
      ref={setNodeRef}
      style={style}
      className={`flex items-center gap-1.5 px-1.5 py-1 rounded-lg border bg-secondary-color transition-shadow ${
        isDragging
          ? 'border-teacher-color shadow-md shadow-teacher-color/10 opacity-90 z-50'
          : 'border-border-color'
      }`}
    >
      {/* Drag handle */}
      <button
        type="button"
        {...attributes}
        {...listeners}
        className="flex-shrink-0 text-text-gray hover:text-teacher-color cursor-grab active:cursor-grabbing touch-none"
        aria-label="Réordonner"
      >
        <GripVertical size={12} />
      </button>

      {/* Number */}
      <span className="flex-shrink-0 w-4 h-4 rounded-full bg-teacher-color/15 text-teacher-color text-xxs font-comfortaa-bold flex items-center justify-center">
        {index + 1}
      </span>

      {/* Content */}
      <div className="flex-1 min-w-0">
        <p className="text-xs font-comfortaa-bold text-text-color leading-tight truncate">
          {item.item.name}
        </p>
        {chapterName && <p className="text-xxs text-text-gray truncate">{chapterName}</p>}
      </div>

      {/* Time */}
      {time != null && time > 0 && (
        <span className="flex-shrink-0 flex items-center gap-0.5 text-xxs text-text-gray">
          <Clock size={9} />
          {time}
        </span>
      )}

      {/* Remove */}
      <IconButton
        icon={X}
        iconSize={11}
        accentColor="error"
        onClick={() => onRemove(item.uid)}
        aria-label="Retirer"
      />
    </div>
  );
}
