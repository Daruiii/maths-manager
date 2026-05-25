import TypeBadge from '@/Components/Common/UI/TypeBadge';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import PictureGrid from '@/Components/Features/Corrections/PictureGrid';
import {
  assignmentTitle,
  assignmentType,
  studentName,
} from '@/Components/Features/Corrections/correctionRequestLabels';
import type { CorrectionRequest } from '@/types/models';

interface Props {
  correctionRequest: CorrectionRequest;
}

export default function CorrectionRequestSummary({ correctionRequest }: Props) {
  const type = assignmentType(correctionRequest).toLowerCase() as 'ds' | 'dm';

  return (
    <div className="space-y-4">
      <TheoremCard accent="student" dotted>
        <SectionLabel>Demande élève</SectionLabel>
        <div className="mt-3 flex items-start gap-3">
          <TypeBadge type={type} size="md" />
          <div className="space-y-1">
            <p className="font-comfortaa-bold text-text-color">{studentName(correctionRequest)}</p>
            <p className="text-sm text-text-gray">
              {assignmentType(correctionRequest)} — {assignmentTitle(correctionRequest)}
            </p>
            <span
              className={`inline-flex text-[10px] px-2 py-0.5 rounded-full font-comfortaa-bold ${
                correctionRequest.status === 'pending'
                  ? 'bg-warning-color/10 text-warning-color'
                  : 'bg-success-color/10 text-success-color'
              }`}
            >
              {correctionRequest.status === 'pending' ? 'À corriger' : 'Corrigé'}
            </span>
          </div>
        </div>
      </TheoremCard>

      <TheoremCard accent="student">
        <SectionLabel>Copie envoyée</SectionLabel>
        <div className="mt-3">
          <PictureGrid paths={correctionRequest.pictures} label="Copie élève" />
        </div>
      </TheoremCard>

      {correctionRequest.message && (
        <TheoremCard accent="student" lined>
          <SectionLabel>Message élève</SectionLabel>
          <p className="mt-2 text-sm text-text-color leading-relaxed">
            {correctionRequest.message}
          </p>
        </TheoremCard>
      )}
    </div>
  );
}
