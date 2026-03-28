import { Check, Clock, ChevronRight } from 'lucide-react';
import { PickableProblem } from '@/types/models';

interface Props {
  problem: PickableProblem;
  isSelected: boolean;
  onAdd: (problem: PickableProblem) => void;
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

export default function ExercisePickerCard({ problem, isSelected, onAdd }: Props) {
  return (
    <button
      type="button"
      onClick={() => onAdd(problem)}
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
            {problem.multiple_chapter?.title ?? '—'}
          </p>

          <p className="text-sm font-comfortaa-bold text-text-color leading-snug line-clamp-2">
            {problem.name}
          </p>

          <div className="flex items-center gap-2 mt-1.5 flex-wrap">
            <DifficultyStars value={problem.difficulty} />

            {problem.time > 0 && (
              <span className="flex items-center gap-0.5 text-xs text-text-gray">
                <Clock size={10} />
                {problem.time} min
              </span>
            )}

            {problem.harder_exercise && (
              <span className="px-1.5 py-0.5 rounded-md bg-error-color/10 text-error-color text-[10px] font-medium">
                Difficile
              </span>
            )}

            {problem.year && <span className="text-[10px] text-text-gray">{problem.year}</span>}
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
