import { ChevronDown } from 'lucide-react';
import PictureGrid from '@/Components/Features/Corrections/PictureGrid';
import type { CorrectionRequest } from '@/types/models';

interface Props {
  cr: CorrectionRequest;
  solutionsAnchor?: string;
}

function formatCorrectedDate(iso: string): string {
  return new Date(iso).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  });
}

export default function CorrectionHero({ cr, solutionsAnchor }: Props) {
  const hasStudentPhotos = cr.pictures.length > 0;
  const hasCorrectionPhotos = !!cr.correction_pictures?.length;

  return (
    <div className="mm-card mm-card-style-raised">
      {/* Grade + meta */}
      <div className="px-6 pt-6 pb-5 flex items-start justify-between gap-6">
        <div>
          {cr.grade !== null ? (
            <>
              <div className="flex items-baseline gap-1 leading-none">
                <span
                  className={`text-6xl font-cmu-serif ${cr.grade >= 10 ? 'text-success-color' : 'text-error-color'}`}
                >
                  {cr.grade}
                </span>
                <span className="text-2xl font-cmu-serif text-text-gray/60">/20</span>
              </div>
              <p className="mt-1.5 text-[10px] font-comfortaa-bold uppercase tracking-widest text-text-gray">
                Note finale
              </p>
            </>
          ) : (
            <p className="text-sm font-comfortaa-bold text-success-color">Corrigé</p>
          )}
        </div>

        <div className="text-right shrink-0">
          <p className="text-xs font-comfortaa text-text-gray">
            Corrigé le {formatCorrectedDate(cr.updated_at)}
          </p>
          {solutionsAnchor && (
            <a
              href={`#${solutionsAnchor}`}
              className="mt-1.5 inline-flex items-center gap-0.5 text-xs font-comfortaa text-student-color hover:underline transition-colors"
            >
              Solutions <ChevronDown size={11} />
            </a>
          )}
        </div>
      </div>

      {/* Message du professeur */}
      {cr.correction_message && (
        <div className="mx-6 mb-5 border-l-2 border-text-color/20 pl-4">
          <p className="text-sm font-cmu-italic text-text-color leading-relaxed">
            {cr.correction_message}
          </p>
          <p className="mt-1.5 text-[10px] font-comfortaa-bold uppercase tracking-widest text-text-gray">
            Ton professeur
          </p>
        </div>
      )}

      {/* Photos copie + correction */}
      {(hasStudentPhotos || hasCorrectionPhotos) && (
        <div className="border-t border-border-color/60 px-6 py-4">
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {hasStudentPhotos && (
              <div>
                <p className="mb-2 text-[10px] font-comfortaa-bold uppercase tracking-widest text-text-gray">
                  Ta copie
                </p>
                <PictureGrid paths={cr.pictures} label="Copie" />
              </div>
            )}
            {hasCorrectionPhotos && (
              <div>
                <p className="mb-2 text-[10px] font-comfortaa-bold uppercase tracking-widest text-text-gray">
                  Correction du prof
                </p>
                <PictureGrid paths={cr.correction_pictures!} label="Correction du prof" />
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
}
