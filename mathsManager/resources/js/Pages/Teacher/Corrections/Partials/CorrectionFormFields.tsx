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
    <TheoremCard accent="teacher" dotted>
      <div className="flex flex-col gap-4">
        <div className="flex items-start justify-between gap-3">
          <div>
            <SectionLabel>{title}</SectionLabel>
            <p className="mt-1 text-xs text-text-gray">
              Note, message et fichiers seront visibles par l'élève.
            </p>
          </div>
          <div className="flex shrink-0 items-baseline gap-1.5 rounded-2xl border border-teacher-color/20 bg-teacher-color/10 px-3 py-2">
            <input
              type="number"
              min="0"
              max="20"
              step="0.25"
              value={grade}
              onChange={(e) => onGradeChange(e.target.value)}
              placeholder="—"
              className="w-16 bg-transparent text-center font-cmu-serif text-3xl leading-none text-text-color placeholder:text-text-gray focus:outline-none"
            />
            <span className="font-cmu-serif text-sm text-text-gray">/20</span>
          </div>
        </div>

        <div className="grid gap-4">
          <textarea
            value={message}
            onChange={(e) => onMessageChange(e.target.value)}
            placeholder="Message pour l'élève (optionnel)"
            rows={5}
            className="w-full resize-none rounded-2xl border border-border-color bg-secondary-color px-4 py-3 text-sm leading-relaxed text-text-color placeholder:text-text-gray focus:border-teacher-color/50 focus:outline-none"
          />

          <div className="rounded-2xl border border-border-color bg-surface-color/60 p-3">
            <div className="mb-3 flex items-center justify-between gap-3">
              <p className="text-xs font-comfortaa-bold uppercase tracking-wider text-text-color">
                Photos de correction
              </p>
              <span className="text-[11px] text-text-gray">Optionnel</span>
            </div>
            <UploadSessionWidget
              purpose="teacher_correction"
              accentColor="teacher"
              onTokenChange={onTokenChange}
              existingPictures={existingPictures}
              onRemoveExisting={onRemoveExisting}
            />
          </div>
        </div>
      </div>
    </TheoremCard>
  );
}
