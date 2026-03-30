import { Plus, Minus, Clock } from 'lucide-react';
import {
  PickableItem,
  PickableProblem,
  PickableExercise,
  PickablePrivateExercise,
} from '@/types/models';

interface Props {
  item: PickableItem;
  isSelected: boolean;
  onToggle: (item: PickableItem) => void;
}

function DifficultyDots({ value }: { value: number | null }) {
  if (!value) return null;
  return (
    <span className="flex items-center gap-0.5">
      {Array.from({ length: 5 }).map((_, i) => (
        <span
          key={i}
          className={`w-1.5 h-1.5 rounded-full ${i < value ? 'bg-teacher-color' : 'bg-border-color'}`}
        />
      ))}
    </span>
  );
}

function ProblemMeta({ problem }: { problem: PickableProblem }) {
  return (
    <>
      <DifficultyDots value={problem.difficulty} />
      {problem.time > 0 && (
        <span className="flex items-center gap-0.5 text-xxs text-text-gray">
          <Clock size={9} />
          {problem.time}min
        </span>
      )}
      {problem.harder_exercise && (
        <span className="px-1 py-0.5 rounded bg-error-color/10 text-error-color text-xxs font-medium leading-none">
          ★
        </span>
      )}
      {problem.year && <span className="text-xxs text-text-gray">{problem.year}</span>}
    </>
  );
}

function ExerciseMeta({ exercise }: { exercise: PickableExercise }) {
  return (
    <>
      <DifficultyDots value={exercise.difficulty} />
      {exercise.order != null && (
        <span className="text-xxs text-text-gray font-mono">#{exercise.order}</span>
      )}
    </>
  );
}

function PrivateMeta({ exercise }: { exercise: PickablePrivateExercise }) {
  return (
    <>
      <DifficultyDots value={exercise.difficulty} />
      {exercise.time != null && exercise.time > 0 && (
        <span className="flex items-center gap-0.5 text-xxs text-text-gray">
          <Clock size={9} />
          {exercise.time}min
        </span>
      )}
      <span className="px-1 py-0.5 rounded bg-teacher-color/10 text-teacher-color text-xxs font-medium leading-none">
        {exercise.type === 'problem' ? 'Pb' : 'Ex'}
      </span>
    </>
  );
}

export default function PickerCard({ item, isSelected, onToggle }: Props) {
  const breadcrumb =
    item.kind === 'problem'
      ? ((item as PickableProblem).multiple_chapter?.title ?? '—')
      : item.kind === 'exercise'
        ? (() => {
            const ex = item as PickableExercise;
            const chapter = ex.subchapter?.chapter?.title;
            const sub = ex.subchapter?.title;
            return chapter && sub ? `${chapter} · ${sub}` : (sub ?? chapter ?? '—');
          })()
        : 'Privé';

  return (
    <button
      type="button"
      onClick={() => onToggle(item)}
      className={`w-full text-left flex items-center gap-0 rounded-xl border-2 transition-all group overflow-hidden ${
        isSelected
          ? 'border-teacher-color bg-teacher-color/5'
          : 'border-border-color bg-secondary-color hover:border-teacher-color/50 hover:bg-teacher-color/5'
      }`}
    >
      {/* Left accent bar */}
      <div
        className={`self-stretch w-1 shrink-0 transition-colors ${
          isSelected ? 'bg-teacher-color' : 'bg-border-color group-hover:bg-teacher-color/40'
        }`}
      />

      {/* Content */}
      <div className="flex-1 min-w-0 px-2.5 py-2">
        <p className="text-xxs text-text-gray truncate leading-tight mb-0.5">{breadcrumb}</p>
        <p className="text-sm font-comfortaa-bold text-text-color leading-tight truncate">
          {item.name}
        </p>
      </div>

      {/* Metadata */}
      <div className="flex items-center gap-1.5 px-2 shrink-0">
        {item.kind === 'problem' ? (
          <ProblemMeta problem={item as PickableProblem} />
        ) : item.kind === 'exercise' ? (
          <ExerciseMeta exercise={item as PickableExercise} />
        ) : (
          <PrivateMeta exercise={item as PickablePrivateExercise} />
        )}
      </div>

      {/* Action icon */}
      <div
        className={`flex items-center justify-center w-7 h-7 rounded-lg mr-2 shrink-0 transition-colors ${
          isSelected
            ? 'bg-teacher-color/15 text-teacher-color'
            : 'text-text-gray group-hover:text-teacher-color group-hover:bg-teacher-color/10'
        }`}
      >
        {isSelected ? <Minus size={13} /> : <Plus size={13} />}
      </div>
    </button>
  );
}
