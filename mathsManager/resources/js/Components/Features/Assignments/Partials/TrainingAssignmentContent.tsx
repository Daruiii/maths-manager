import { BookOpen, ChevronDown } from 'lucide-react';
import LatexRenderer from '@/Components/Common/UI/LatexRenderer';
import AssignmentStatement from '@/Components/Features/Assignments/Partials/AssignmentStatement';
import type { AssignmentContentItem } from '@/Components/Features/Assignments/Partials/assignmentContentUtils';
import { itemLabel } from '@/Components/Features/Assignments/Partials/assignmentContentUtils';

interface Props {
  items: AssignmentContentItem[];
  accent: 'student' | 'teacher';
  showSolutions: boolean;
  openSolutions: Set<string>;
  onToggleSolution: (key: string) => void;
}

const ACCENT: Record<Props['accent'], { icon: string; border: string }> = {
  student: { icon: 'text-student-color', border: 'mm-card-accent-student' },
  teacher: { icon: 'text-teacher-color', border: 'mm-card-accent-teacher' },
};

export default function TrainingAssignmentContent({
  items,
  accent,
  showSolutions,
  openSolutions,
  onToggleSolution,
}: Props) {
  return (
    <div
      className={`mm-card mm-card-style-halo card-dot-grid ${ACCENT[accent].border} p-5 space-y-5`}
    >
      <div className="flex items-center justify-between gap-4">
        <div>
          <p className="text-xs font-comfortaa-bold text-text-gray uppercase tracking-wide">
            Sujet
          </p>
          <p className="text-sm text-text-gray mt-1">
            {items.length} exercice{items.length > 1 ? 's' : ''} à traiter.
          </p>
        </div>
      </div>

      <div className="space-y-5">
        {items.map((item, index) => {
          const solutionKey = `${item.kind}-${item.id}-${index}`;

          return (
            <TrainingExercise
              key={solutionKey}
              item={item}
              index={index}
              solutionKey={solutionKey}
              solutionOpen={openSolutions.has(solutionKey)}
              showSolutions={showSolutions}
              accent={accent}
              onToggleSolution={onToggleSolution}
            />
          );
        })}
      </div>
    </div>
  );
}

function TrainingExercise({
  item,
  index,
  solutionKey,
  solutionOpen,
  showSolutions,
  accent,
  onToggleSolution,
}: {
  item: AssignmentContentItem;
  index: number;
  solutionKey: string;
  solutionOpen: boolean;
  showSolutions: boolean;
  accent: 'student' | 'teacher';
  onToggleSolution: (key: string) => void;
}) {
  return (
    <article
      id={`ex-${index + 1}`}
      className="bg-secondary-color/90 border border-border-color rounded-2xl overflow-hidden shadow-sm scroll-mt-32"
    >
      <div className="flex items-start gap-3 px-4 py-3 border-b border-border-color bg-surface-color/70">
        <div className="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary-color border border-border-color text-xs font-comfortaa-bold text-text-gray">
          {index + 1}
        </div>
        <div className="min-w-0 flex-1">
          <div className="flex items-center gap-2 text-sm text-text-color font-comfortaa-bold">
            <BookOpen size={13} className={`${ACCENT[accent].icon} shrink-0`} />
            <span className="truncate">{itemLabel(item)}</span>
          </div>
          <p className="text-xs text-text-gray mt-0.5">{item.kind}</p>
        </div>
      </div>

      <div className="px-4 py-4 exercise-content">
        <AssignmentStatement item={item} />
      </div>

      {showSolutions && (
        <div className="border-t border-border-color bg-success-color/5">
          <button
            type="button"
            onClick={() => onToggleSolution(solutionKey)}
            className="w-full flex items-center justify-between gap-3 px-4 py-3 text-left text-sm font-comfortaa-bold text-success-color hover:bg-success-color/10 transition-colors"
          >
            <span>Solution</span>
            <ChevronDown
              size={16}
              className={`transition-transform ${solutionOpen ? 'rotate-180' : ''}`}
            />
          </button>

          {solutionOpen && (
            <div className="px-4 pb-4 solution-content">
              {item.latex_solution ? (
                <div className="rounded-xl border border-success-color/20 bg-secondary-color p-4">
                  <LatexRenderer latex={item.latex_solution} images={{}} />
                </div>
              ) : (
                <p className="rounded-xl border border-border-color bg-secondary-color px-4 py-3 text-sm text-text-gray italic">
                  Solution non disponible pour cet exercice.
                </p>
              )}
            </div>
          )}
        </div>
      )}
    </article>
  );
}
