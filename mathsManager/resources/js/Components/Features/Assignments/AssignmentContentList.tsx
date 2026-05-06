import { useState } from 'react';
import { BookOpen, ChevronDown } from 'lucide-react';
import LatexRenderer from '@/Components/Common/UI/LatexRenderer';
import LegacyKatexHtmlBlock from '@/Components/Common/UI/LegacyKatexHtmlBlock';
import type { AssignmentListItem } from '@/types/models';
import { normalizeStoragePath } from '@/Utils/pickableItemContent';

interface Props {
  problems: AssignmentListItem[];
  exercises: AssignmentListItem[];
  privateExercises: AssignmentListItem[];
  accent?: 'student' | 'teacher';
  showSolutions?: boolean;
  variant?: 'academic' | 'training';
  title?: string | null;
  level?: string | null;
  instructions?: string | null;
}

const ACCENT: Record<NonNullable<Props['accent']>, { icon: string; border: string }> = {
  student: { icon: 'text-student-color', border: 'mm-card-accent-student' },
  teacher: { icon: 'text-teacher-color', border: 'mm-card-accent-teacher' },
};

function itemLabel(item: AssignmentListItem): string {
  return item.title ?? item.name ?? `#${item.id}`;
}

function imageMap(item: AssignmentListItem): Record<string, string> {
  if (!item.image_paths) return {};

  let raw: Record<string, string>;

  if (typeof item.image_paths === 'string') {
    try {
      raw = JSON.parse(item.image_paths) as Record<string, string>;
    } catch {
      return {};
    }
  } else {
    raw = item.image_paths;
  }

  return Object.fromEntries(
    Object.entries(raw).map(([key, value]) => [key, normalizeStoragePath(value)])
  );
}

function AssignmentStatement({ item }: { item: AssignmentListItem }) {
  if (item.statement?.trim()) {
    return <LegacyKatexHtmlBlock html={item.statement} />;
  }

  if (item.latex_statement?.trim()) {
    return <LatexRenderer latex={item.latex_statement} images={imageMap(item)} />;
  }

  return <p className="text-xs text-text-gray italic">Énoncé non disponible.</p>;
}

export default function AssignmentContentList({
  problems,
  exercises,
  privateExercises,
  accent = 'student',
  showSolutions = false,
  variant = 'training',
  title,
  level,
  instructions,
}: Props) {
  const items = [
    ...problems.map((item) => ({ ...item, kind: 'Problème' })),
    ...exercises.map((item) => ({ ...item, kind: 'Exercice' })),
    ...privateExercises.map((item) => ({ ...item, kind: 'Exercice privé' })),
  ];
  const [openSolutions, setOpenSolutions] = useState<Set<string>>(new Set());

  if (items.length === 0) return null;

  function toggleSolution(key: string) {
    setOpenSolutions((prev) => {
      const next = new Set(prev);
      if (next.has(key)) {
        next.delete(key);
      } else {
        next.add(key);
      }
      return next;
    });
  }

  if (variant === 'academic') {
    return (
      <div className="academic-paper">
        <div className="font-cmu-serif text-text-color px-5 py-8 sm:px-10 sm:py-12">
          <div className="text-center space-y-2 pb-8 border-b border-border-color">
            <h2 className="text-lg font-bold uppercase tracking-wide">
              {(title ?? 'Devoir Maison').charAt(0)}
              <span className="text-sm">{(title ?? 'Devoir Maison').slice(1)}</span>
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

          <div className="space-y-12 pt-10">
            {items.map((item, index) => {
              const solutionKey = `${item.kind}-${item.id}-${index}`;
              const solutionOpen = openSolutions.has(solutionKey);

              return (
                <section key={solutionKey} className="space-y-4">
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
                    <div className="border-t border-border-color pt-3">
                      <button
                        type="button"
                        onClick={() => toggleSolution(solutionKey)}
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
            })}
          </div>
        </div>
      </div>
    );
  }

  return (
    <div
      className={`mm-card mm-card-style-halo card-dot-grid ${ACCENT[accent].border} p-5 space-y-5`}
    >
      <div>
        <p className="text-xs font-comfortaa-bold text-text-gray uppercase tracking-wide">Sujet</p>
        <p className="text-sm text-text-gray mt-1">
          {items.length} exercice{items.length > 1 ? 's' : ''} à traiter.
        </p>
      </div>

      <div className="space-y-5">
        {items.map((item, index) => {
          const solutionKey = `${item.kind}-${item.id}-${index}`;
          const solutionOpen = openSolutions.has(solutionKey);

          return (
            <article
              key={`${item.kind}-${item.id}-${index}`}
              className="bg-secondary-color/90 border border-border-color rounded-2xl overflow-hidden shadow-sm"
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
                    onClick={() => toggleSolution(solutionKey)}
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
        })}
      </div>
    </div>
  );
}
