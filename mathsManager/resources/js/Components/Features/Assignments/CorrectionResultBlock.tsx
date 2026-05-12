import TheoremCard from '@/Components/Common/UI/TheoremCard';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import PictureGrid from '@/Components/Features/Corrections/PictureGrid';
import type { CorrectionRequest } from '@/types/models';

export default function CorrectionResultBlock({ cr }: { cr: CorrectionRequest }) {
  return (
    <>
      {cr.grade !== null && (
        <div className="flex items-center justify-center py-6">
          <span
            className={`text-5xl font-comfortaa-bold ${cr.grade >= 10 ? 'text-success-color' : 'text-error-color'}`}
          >
            {cr.grade}
            <span className="text-2xl text-text-gray">/20</span>
          </span>
        </div>
      )}
      <div className="grid sm:grid-cols-2 gap-4">
        {cr.pictures.length > 0 && (
          <TheoremCard accent="student">
            <SectionLabel>Votre copie</SectionLabel>
            <div className="mt-3">
              <PictureGrid paths={cr.pictures} label="Copie" />
            </div>
          </TheoremCard>
        )}
        {cr.correction_pictures && cr.correction_pictures.length > 0 && (
          <TheoremCard accent="teacher">
            <SectionLabel>Correction</SectionLabel>
            <div className="mt-3">
              <PictureGrid paths={cr.correction_pictures} label="Correction" />
            </div>
          </TheoremCard>
        )}
      </div>
      {cr.correction_message && (
        <TheoremCard accent="teacher">
          <SectionLabel>Message du professeur</SectionLabel>
          <p className="mt-2 text-sm text-text-color leading-relaxed">{cr.correction_message}</p>
        </TheoremCard>
      )}
    </>
  );
}
