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
  const isProblem = current.kind === 'problem';
  const chapterName = isProblem ? current.multiple_chapter?.title : current.subchapter?.title;
  const time = isProblem ? current.time : null;

  return (
    <div
      ref={setNodeRef}
      style={style}
      className={`flex items-center gap-2 p-2.5 rounded-xl border-2 bg-secondary-color transition-shadow ${
        isDragging
          ? 'border-teacher-color shadow-lg shadow-teacher-color/10 opacity-90 z-50'
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
        <GripVertical size={16} />
      </button>

      {/* Number */}
      <span className="flex-shrink-0 w-5 h-5 rounded-full bg-teacher-color/15 text-teacher-color text-[11px] font-comfortaa-bold flex items-center justify-center">
        {index + 1}
      </span>

      {/* Content */}
      <div className="flex-1 min-w-0">
        <p className="text-sm font-comfortaa-bold text-text-color leading-tight truncate">
          {item.item.name}
        </p>
        {chapterName && <p className="text-[10px] text-text-gray truncate">{chapterName}</p>}
      </div>

      {/* Time */}
      {time != null && time > 0 && (
        <span className="flex-shrink-0 flex items-center gap-0.5 text-xs text-text-gray">
          <Clock size={10} />
          {time}
        </span>
      )}

      {/* Remove */}
      <IconButton
        icon={X}
        accentColor="error"
        onClick={() => onRemove(item.uid)}
        aria-label="Retirer"
      />
    </div>
  );
}
