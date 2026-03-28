import { Check, ChevronRight } from 'lucide-react';
import { PickableExercise } from '@/types/models';

interface Props {
  exercise: PickableExercise;
  isSelected: boolean;
  onAdd: (exercise: PickableExercise) => void;
}

function DifficultyStars({ value }: { value: number | null }) {
  if (!value) return <span className="text-text-gray text-xs">—</span>;
  return (
    <span className="text-xs text-teacher-color">
      {'★'.repeat(value)}
      <span className="opacity-30">{'★'.repeat(5 - value)}</span>
    </span>
  );
}

export default function ExercisePickerExerciseCard({ exercise, isSelected, onAdd }: Props) {
  return (
    <button
      type="button"
      onClick={() => onAdd(exercise)}
      disabled={isSelected}
      className={`w-full text-left p-3 rounded-xl border-2 transition-all group ${
        isSelected
          ? 'border-teacher-color/40 bg-teacher-color/5 opacity-60 cursor-default'
          : 'border-border-color bg-secondary-color hover:border-teacher-color hover:bg-teacher-color/5 cursor-pointer'
      }`}
    >
      <div className="flex items-start justify-between gap-2">
        <div className="flex-1 min-w-0">
          <p className="text-[10px] font-medium text-teacher-color/70 truncate mb-0.5">
            {exercise.subchapter?.chapter?.title ?? '—'} · {exercise.subchapter?.title ?? '—'}
          </p>
          <p className="text-sm font-comfortaa-bold text-text-color leading-snug line-clamp-2">
            {exercise.name}
          </p>

          <div className="flex items-center gap-2 mt-1.5 flex-wrap">
            <DifficultyStars value={exercise.difficulty} />
          </div>
        </div>

        <div className="flex-shrink-0 mt-0.5">
          {isSelected ? (
            <Check size={16} className="text-teacher-color" />
          ) : (
            <ChevronRight
              size={16}
              className="text-text-gray group-hover:text-teacher-color transition-colors"
            />
          )}
        </div>
      </div>
    </button>
  );
}
