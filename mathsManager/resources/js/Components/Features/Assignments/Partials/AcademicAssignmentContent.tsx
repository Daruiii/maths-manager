import { ChevronDown } from 'lucide-react';
import LatexRenderer from '@/Components/Common/UI/LatexRenderer';
import AssignmentStatement from '@/Components/Features/Assignments/Partials/AssignmentStatement';
import ExerciseNav from '@/Components/Features/Assignments/Partials/ExerciseNav';
import type { AssignmentContentItem } from '@/Components/Features/Assignments/Partials/assignmentContentUtils';
import { itemLabel } from '@/Components/Features/Assignments/Partials/assignmentContentUtils';

interface Props {
  items: AssignmentContentItem[];
  showSolutions: boolean;
  openSolutions: Set<string>;
  onToggleSolution: (key: string) => void;
  onOpenSolutionExclusive?: (key: string) => void;
  title?: string | null;
  level?: string | null;
  instructions?: string | null;
}

export default function AcademicAssignmentContent({
  items,
  showSolutions,
  openSolutions,
  onToggleSolution,
  onOpenSolutionExclusive,
  title,
  level,
  instructions,
}: Props) {
  function handleOpenSolution(index: number) {
    const item = items[index];
    if (!item) return;
    const key = `${item.kind}-${item.id}-${index}`;
    if (onOpenSolutionExclusive) {
      onOpenSolutionExclusive(key);
    } else if (!openSolutions.has(key)) {
      onToggleSolution(key);
    }
  }

  return (
    <div className="relative">
      <ExerciseNav
        count={items.length}
        className="mb-3 -mx-1 flex items-center gap-2 overflow-x-auto px-1 pb-1 lg:hidden"
        showSolutions={showSolutions}
        onOpenSolution={showSolutions ? handleOpenSolution : undefined}
      />
      <ExerciseNav
        count={items.length}
        className="hidden lg:sticky lg:left-full lg:top-24 lg:float-right lg:-mr-28 lg:ml-6 lg:z-10 lg:block lg:w-24 rounded-xl border border-student-color/15 bg-secondary-color/80 px-3 py-2.5 shadow-[0_12px_32px_rgb(var(--student-color)_/_0.10)] backdrop-blur-sm"
        variant="toc"
        showSolutions={showSolutions}
        onOpenSolution={showSolutions ? handleOpenSolution : undefined}
      />

      <div className="academic-paper">
        <div className="font-cmu-serif text-text-color px-5 py-8 sm:px-10 sm:py-12">
          <AcademicHeader title={title} level={level} instructions={instructions} />

          <div className="space-y-12 pt-10">
            {items.map((item, index) => {
              const solutionKey = `${item.kind}-${item.id}-${index}`;

              return (
                <AcademicExercise
                  key={solutionKey}
                  item={item}
                  index={index}
                  solutionKey={solutionKey}
                  solutionOpen={openSolutions.has(solutionKey)}
                  showSolutions={showSolutions}
                  onToggleSolution={onToggleSolution}
                />
              );
            })}
          </div>
        </div>
      </div>
    </div>
  );
}

function AcademicHeader({
  title,
  level,
  instructions,
}: {
  title?: string | null;
  level?: string | null;
  instructions?: string | null;
}) {
  const displayTitle = title ?? 'Devoir Maison';

  return (
    <div className="text-center space-y-2 pb-8 border-b border-border-color">
      <h2 className="text-lg font-bold uppercase tracking-wide">
        {displayTitle.charAt(0)}
        <span className="text-sm">{displayTitle.slice(1)}</span>
      </h2>
      {level && (
        <p className="text-base font-bold uppercase tracking-wide">
          {level.charAt(0)}
          <span className="text-xs">{level.slice(1)}</span>
        </p>
      )}
      {instructions && (
        <p className="text-sm font-cmu-italic leading-relaxed pt-2 whitespace-pre-line">
          {instructions}
        </p>
      )}
    </div>
  );
}

function AcademicExercise({
  item,
  index,
  solutionKey,
  solutionOpen,
  showSolutions,
  onToggleSolution,
}: {
  item: AssignmentContentItem;
  index: number;
  solutionKey: string;
  solutionOpen: boolean;
  showSolutions: boolean;
  onToggleSolution: (key: string) => void;
}) {
  return (
    <section id={`ex-${index + 1}`} className="space-y-4 scroll-mt-32">
      <div className="space-y-3">
        <h3 className="text-sm font-bold">
          Exercice {index + 1}.
          <span className="ml-2 font-normal text-text-gray">{itemLabel(item)}</span>
        </h3>
        <div className="text-sm leading-relaxed exercise-content">
          <AssignmentStatement item={item} />
        </div>
      </div>

      {showSolutions && (
        <div id={`sol-${index + 1}`} className="border-t border-border-color pt-3 scroll-mt-28">
          <button
            type="button"
            onClick={() => onToggleSolution(solutionKey)}
            className="flex items-center gap-1.5 text-xs font-bold text-success-color uppercase tracking-wide"
          >
            Solution
            <ChevronDown
              size={14}
              className={`transition-transform ${solutionOpen ? 'rotate-180' : ''}`}
            />
          </button>

          {solutionOpen && (
            <div className="mt-3 rounded border border-success-color/20 bg-success-color/5 p-4 solution-content">
              {item.latex_solution ? (
                <LatexRenderer latex={item.latex_solution} images={{}} />
              ) : (
                <p className="text-sm text-text-gray italic">
                  Solution non disponible pour cet exercice.
                </p>
              )}
            </div>
          )}
        </div>
      )}
    </section>
  );
}
