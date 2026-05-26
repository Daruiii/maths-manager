import { Clock } from 'lucide-react';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import PictureGrid from '@/Components/Features/Corrections/PictureGrid';
import type { CorrectionRequest } from '@/types/models';

export default function AwaitingCorrectionCard({ cr }: { cr: CorrectionRequest }) {
  return (
    <div className="space-y-4">
      <TheoremCard accent="tertiary">
        <div className="flex items-start gap-2.5">
          <Clock size={15} className="text-info-color mt-0.5 shrink-0" />
          <div>
            <p className="text-sm font-comfortaa-bold text-text-color">Copie envoyée</p>
            <p className="text-xs text-text-gray mt-0.5 leading-relaxed">
              Tu recevras une notification dès que ton professeur aura corrigé ton travail.
            </p>
          </div>
        </div>
      </TheoremCard>

      {cr.pictures.length > 0 && (
        <TheoremCard accent="student">
          <SectionLabel>Ta copie envoyée</SectionLabel>
          <div className="mt-3">
            <PictureGrid paths={cr.pictures} label="Copie" />
          </div>
        </TheoremCard>
      )}

      {cr.message && (
        <TheoremCard accent="student" lined>
          <SectionLabel>Ton message</SectionLabel>
          <p className="mt-2 text-sm text-text-color leading-relaxed">{cr.message}</p>
        </TheoremCard>
      )}
    </div>
  );
}
