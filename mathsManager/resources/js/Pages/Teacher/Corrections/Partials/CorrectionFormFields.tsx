import SectionLabel from '@/Components/Common/UI/SectionLabel';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import UploadSessionWidget from '@/Components/Features/Uploads/UploadSessionWidget';

interface Props {
  title: string;
  message: string;
  grade: string;
  onMessageChange: (message: string) => void;
  onGradeChange: (grade: string) => void;
  onTokenChange: (token: string | null) => void;
  existingPictures?: string[];
  onRemoveExisting?: (path: string) => void;
}

export default function CorrectionFormFields({
  title,
  message,
  grade,
  onMessageChange,
  onGradeChange,
  onTokenChange,
  existingPictures,
  onRemoveExisting,
}: Props) {
  return (
    <>
      <TheoremCard accent="teacher" dotted>
        <SectionLabel>Fichiers de correction</SectionLabel>
        <div className="mt-3">
          <UploadSessionWidget
            purpose="teacher_correction"
            accentColor="teacher"
            onTokenChange={onTokenChange}
            existingPictures={existingPictures}
            onRemoveExisting={onRemoveExisting}
          />
        </div>
      </TheoremCard>

      <TheoremCard accent="teacher" lined>
        <SectionLabel>{title}</SectionLabel>
        <div className="mt-3 space-y-3">
          <textarea
            value={message}
            onChange={(e) => onMessageChange(e.target.value)}
            placeholder="Message pour l'élève (optionnel)"
            rows={4}
            className="w-full rounded-xl border border-border-color bg-secondary-color px-4 py-3 text-sm text-text-color placeholder:text-text-gray resize-none focus:outline-none focus:border-teacher-color/50"
          />
          <div className="flex items-center gap-3">
            <div className="flex items-baseline gap-1.5">
              <input
                type="number"
                min="0"
                max="20"
                step="0.25"
                value={grade}
                onChange={(e) => onGradeChange(e.target.value)}
                placeholder="—"
                className="w-20 rounded-xl border border-border-color bg-secondary-color px-3 py-2 text-2xl font-cmu-serif text-text-color placeholder:text-text-gray text-center focus:outline-none focus:border-teacher-color/50"
              />
              <span className="text-sm text-text-gray font-cmu-serif">/20</span>
            </div>
            <span className="text-xs text-text-gray">Laisser vide = non noté</span>
          </div>
        </div>
      </TheoremCard>
    </>
  );
}
