import { CheckCircle, Pencil } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import PictureGrid from '@/Components/Features/Corrections/PictureGrid';
import type { CorrectionRequest } from '@/types/models';

interface Props {
  correctionRequest: CorrectionRequest;
  onEdit: () => void;
}

export default function CorrectionSentPanel({ correctionRequest, onEdit }: Props) {
  const hasCorrectionPhotos =
    correctionRequest.correction_pictures && correctionRequest.correction_pictures.length > 0;

  return (
    <div className="mm-card mm-card-style-raised overflow-hidden">
      {/* Status + grade */}
      <div className="px-5 py-5">
        <div className="flex items-start justify-between gap-3">
          <div className="flex items-center gap-2">
            <CheckCircle size={15} className="text-success-color shrink-0" />
            <div>
              <p className="font-comfortaa-bold text-text-color">Correction envoyée</p>
              <p className="text-xs text-text-gray">L&apos;élève a été notifié.</p>
            </div>
          </div>
          <Button variant="ghost" size="sm" icon={Pencil} onClick={onEdit}>
            Modifier
          </Button>
        </div>

        <div className="mt-5 flex items-baseline gap-1.5">
          <span className="font-cmu-serif text-5xl leading-none text-text-color">
            {correctionRequest.grade === null ? '—' : correctionRequest.grade}
          </span>
          {correctionRequest.grade !== null && (
            <span className="font-cmu-serif text-lg text-text-gray">/20</span>
          )}
        </div>
      </div>

      {/* Message prof */}
      {correctionRequest.correction_message && (
        <div className="border-t border-border-color/60 px-5 py-4">
          <p className="mb-2 text-[11px] font-comfortaa-bold uppercase tracking-wider text-text-gray">
            Message
          </p>
          <p className="text-sm font-cmu-italic leading-relaxed text-text-color">
            {correctionRequest.correction_message}
          </p>
        </div>
      )}

      {/* Photos de correction */}
      {hasCorrectionPhotos && (
        <div className="border-t border-border-color/60 px-5 py-4">
          <p className="mb-3 text-[11px] font-comfortaa-bold uppercase tracking-wider text-text-gray">
            Photos de correction
          </p>
          <PictureGrid paths={correctionRequest.correction_pictures!} label="Correction" />
        </div>
      )}
    </div>
  );
}
