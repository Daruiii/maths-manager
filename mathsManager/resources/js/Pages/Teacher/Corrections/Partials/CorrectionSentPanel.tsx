import { CheckCircle, Pencil } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import PictureGrid from '@/Components/Features/Corrections/PictureGrid';
import type { CorrectionRequest } from '@/types/models';

interface Props {
  correctionRequest: CorrectionRequest;
  onEdit: () => void;
}

export default function CorrectionSentPanel({ correctionRequest, onEdit }: Props) {
  return (
    <>
      <TheoremCard accent="teacher" dotted>
        <div className="flex items-center justify-between gap-3">
          <div className="flex items-center gap-2">
            <CheckCircle size={17} className="text-success-color" />
            <p className="font-comfortaa-bold text-text-color">Correction envoyée</p>
          </div>
          <Button variant="ghost" size="sm" icon={Pencil} onClick={onEdit}>
            Modifier
          </Button>
        </div>

        <div className="mt-3 flex items-baseline gap-1.5">
          <span className="text-[11px] text-text-gray uppercase tracking-wider font-comfortaa-bold">
            Note
          </span>
          <span className="text-3xl font-cmu-serif text-text-color leading-none">
            {correctionRequest.grade === null ? '—' : correctionRequest.grade}
          </span>
          {correctionRequest.grade !== null && (
            <span className="text-sm text-text-gray font-cmu-serif">/20</span>
          )}
        </div>
      </TheoremCard>

      {correctionRequest.correction_pictures && (
        <TheoremCard accent="teacher">
          <SectionLabel>Correction</SectionLabel>
          <div className="mt-3">
            <PictureGrid paths={correctionRequest.correction_pictures} label="Correction" />
          </div>
        </TheoremCard>
      )}

      {correctionRequest.correction_message && (
        <TheoremCard accent="teacher" lined>
          <SectionLabel>Message professeur</SectionLabel>
          <p className="mt-2 text-sm text-text-color leading-relaxed">
            {correctionRequest.correction_message}
          </p>
        </TheoremCard>
      )}
    </>
  );
}
