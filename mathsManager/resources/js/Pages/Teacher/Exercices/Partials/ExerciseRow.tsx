import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { Pencil, Trash2 } from 'lucide-react';
import DifficultyPicker from '@/Components/Common/Form/DifficultyPicker';
import { PrivateExercise } from '@/types/models';

interface Props {
  exercise: PrivateExercise;
  onDelete: () => void;
}

export default function ExerciseRow({ exercise, onDelete }: Props) {
  return (
    <div className="flex items-center gap-3 px-4 py-3 bg-surface-color border border-border-color rounded-xl hover:border-teacher-color/40 transition-colors group">
      <span
        className={`w-1.5 h-1.5 rounded-full flex-shrink-0 ${exercise.type === 'problem' ? 'bg-teacher-color' : 'bg-teacher-color/40'}`}
      />

      <Link href={route('teacher.exercices.edit', exercise.id)} className="min-w-0 flex-1">
        <p className="text-sm text-text-color truncate">{exercise.name}</p>
      </Link>

      {exercise.difficulty != null && (
        <div className="hidden sm:block">
          <DifficultyPicker value={String(exercise.difficulty)} onChange={() => {}} readOnly />
        </div>
      )}

      <span className="text-xxs text-text-gray/60 flex-shrink-0 hidden sm:block">
        {exercise.type === 'problem' ? 'Problème' : 'Exercice'}
      </span>

      <div className="flex shrink-0 items-center gap-1">
        <Link
          href={route('teacher.exercices.edit', exercise.id)}
          className="p-1.5 rounded-lg text-text-gray hover:text-teacher-color hover:bg-teacher-color/10 transition-colors"
          title="Éditer"
        >
          <Pencil size={14} />
        </Link>
        <button
          type="button"
          onClick={onDelete}
          className="p-1.5 rounded-lg text-text-gray hover:text-error-color hover:bg-error-color/10 transition-colors"
          title="Supprimer"
        >
          <Trash2 size={14} />
        </button>
      </div>
    </div>
  );
}
