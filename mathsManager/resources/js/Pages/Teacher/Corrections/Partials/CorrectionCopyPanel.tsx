import { Images, MessageSquareText } from 'lucide-react';
import PictureGrid from '@/Components/Features/Corrections/PictureGrid';
import { studentName } from '@/Components/Features/Corrections/correctionRequestLabels';
import type { CorrectionRequest } from '@/types/models';

export default function CorrectionCopyPanel({
  correctionRequest,
}: {
  correctionRequest: CorrectionRequest;
}) {
  const name = studentName(correctionRequest);
  const photoCount = correctionRequest.pictures.length;

  return (
    <div className="mm-card mm-card-style-raised overflow-hidden">
      <div className="flex items-center justify-between gap-3 border-b border-border-color/60 px-5 py-3.5">
        <div className="flex items-center gap-2">
          <Images size={14} className="text-text-gray shrink-0" />
          <span className="text-xs font-comfortaa-bold uppercase tracking-widest text-text-gray">
            Copie de {name}
          </span>
        </div>
        <span className="mm-badge mm-badge-student">
          {photoCount} photo{photoCount > 1 ? 's' : ''}
        </span>
      </div>

      <div className="px-4 py-4">
        <PictureGrid paths={correctionRequest.pictures} label={`Copie de ${name}`} />
      </div>

      <div className="mx-4 mb-4 rounded-2xl border border-border-color bg-secondary-color px-3.5 py-3 shadow-warm-xs">
        <div className="mb-2 flex items-center gap-1.5">
          <MessageSquareText size={13} className="text-text-gray shrink-0" />
          <span className="text-[11px] font-comfortaa-bold uppercase tracking-wider text-text-gray">
            Message de l&apos;élève
          </span>
        </div>
        {correctionRequest.message ? (
          <p className="text-sm font-cmu-italic leading-relaxed text-text-color">
            {correctionRequest.message}
          </p>
        ) : (
          <p className="text-sm italic text-text-gray">Aucun message ajouté.</p>
        )}
      </div>
    </div>
  );
}
