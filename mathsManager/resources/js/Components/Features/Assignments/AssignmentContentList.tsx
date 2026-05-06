import { BookOpen } from 'lucide-react';
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
}

const ACCENT: Record<NonNullable<Props['accent']>, { icon: string; border: string }> = {
  student: { icon: 'text-student-color', border: 'border-l-student-color' },
  teacher: { icon: 'text-teacher-color', border: 'border-l-teacher-color' },
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
}: Props) {
  const items = [
    ...problems.map((item) => ({ ...item, kind: 'Problème' })),
    ...exercises.map((item) => ({ ...item, kind: 'Exercice' })),
    ...privateExercises.map((item) => ({ ...item, kind: 'Exercice privé' })),
  ];

  if (items.length === 0) return null;

  return (
    <div className={`card-theorem card-dot-grid ${ACCENT[accent].border} p-4 space-y-4`}>
      <p className="text-xs font-comfortaa-bold text-text-gray uppercase tracking-wide">Contenu</p>
      <div className="space-y-4">
        {items.map((item, index) => (
          <article
            key={`${item.kind}-${item.id}-${index}`}
            className="bg-secondary-color/80 border border-border-color rounded-xl p-4 space-y-3"
          >
            <div className="flex items-center gap-2 text-sm text-text-color font-comfortaa-bold">
              <BookOpen size={13} className={`${ACCENT[accent].icon} shrink-0`} />
              <span>
                {item.kind} — {itemLabel(item)}
              </span>
            </div>
            <div className="border-t border-border-color pt-3">
              <AssignmentStatement item={item} />
            </div>
            {showSolutions && item.latex_solution && (
              <div className="border-t border-success-color/20 pt-3">
                <p className="text-xs font-comfortaa-bold text-success-color uppercase tracking-wide mb-2">
                  Solution
                </p>
                <LatexRenderer latex={item.latex_solution} images={{}} />
              </div>
            )}
          </article>
        ))}
      </div>
    </div>
  );
}
