import { Link } from '@inertiajs/react';
import { BookOpen } from 'lucide-react';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
import AssignmentContentList from '@/Components/Features/Assignments/AssignmentContentList';
import {
  assignmentTitle,
  assignmentType,
} from '@/Components/Features/Corrections/correctionRequestLabels';
import type { CorrectionRequest } from '@/types/models';

export default function CorrectionSubjectPanel({
  correctionRequest,
}: {
  correctionRequest: CorrectionRequest;
}) {
  const type = assignmentType(correctionRequest).toLowerCase() as 'ds' | 'dm';
  const title = assignmentTitle(correctionRequest);
  const subject = correctionRequest.ds ?? correctionRequest.dm;
  const problems = subject?.problems ?? [];
  const exercises = subject?.exercises ?? [];
  const privateExercises = subject?.private_exercises ?? [];
  const hasContent = problems.length + exercises.length + privateExercises.length > 0;
  const subjectHref = subject ? route(type === 'ds' ? 'ds.show' : 'dm.show', subject.id) : null;

  return (
    <div className="mm-card mm-card-style-raised overflow-hidden">
      <div className="flex items-center justify-between gap-3 border-b border-border-color/60 px-5 py-3.5">
        <div className="flex items-center gap-2.5">
          <BookOpen size={14} className="text-text-gray shrink-0" />
          <span className="text-xs font-comfortaa-bold uppercase tracking-widest text-text-gray">
            Sujet du devoir
          </span>
        </div>
        {subjectHref && (
          <Link
            href={subjectHref}
            target="_blank"
            className="text-[11px] font-comfortaa-bold text-teacher-color hover:underline"
          >
            Ouvrir à part
          </Link>
        )}
      </div>

      <div className="px-5 py-5">
        <div className="mb-6 flex items-center gap-3">
          <TypeBadge type={type} size="md" />
          <div>
            <p className="font-comfortaa-bold text-text-color">{title}</p>
            <p className="mt-0.5 text-xs text-text-gray">
              Sujet de référence, corrigé visible côté professeur.
            </p>
          </div>
        </div>

        {hasContent ? (
          <AssignmentContentList
            problems={problems}
            exercises={exercises}
            privateExercises={privateExercises}
            accent="teacher"
            variant="academic"
            title={title}
            level={subject?.custom_level}
            instructions={subject?.custom_instructions}
            showSolutions
          />
        ) : (
          <p className="rounded-2xl border border-border-color bg-secondary-color px-4 py-3 text-sm text-text-gray shadow-warm-xs">
            Sujet indisponible dans cette correction. Ouvre le devoir dans un nouvel onglet.
          </p>
        )}
      </div>
    </div>
  );
}
