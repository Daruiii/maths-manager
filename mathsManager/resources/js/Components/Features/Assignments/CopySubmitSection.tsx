import { useState } from 'react';
import { Send } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import UploadSessionWidget from '@/Components/Features/Uploads/UploadSessionWidget';

interface Props {
  sessionToken: string | null;
  onTokenChange: (token: string | null) => void;
  message: string;
  onMessageChange: (msg: string) => void;
  submitting: boolean;
  uploadError?: string | null;
}

export default function CopySubmitSection({
  sessionToken,
  onTokenChange,
  message,
  onMessageChange,
  submitting,
  uploadError,
}: Props) {
  const [fileCount, setFileCount] = useState(0);
  const canSubmit = !!sessionToken && fileCount > 0;

  return (
    <>
      <TheoremCard accent="student" dotted>
        <SectionLabel>
          Envoyer ta copie <span className="text-error-color">*</span>
        </SectionLabel>
        <p className="mt-1 text-xs text-text-gray">
          Ajoute au moins une photo lisible de ta copie avant l'envoi.
        </p>
        <div className="mt-3">
          <UploadSessionWidget
            purpose="correction_submission"
            accentColor="student"
            onTokenChange={onTokenChange}
            onFileCountChange={setFileCount}
          />
        </div>
        {!canSubmit && fileCount === 0 && sessionToken && (
          <p className="mt-3 rounded-xl border border-error-color/20 bg-error-color/10 px-3 py-2 text-xs text-error-color">
            Ajoutez au moins une photo avant d'envoyer.
          </p>
        )}
      </TheoremCard>

      {uploadError && (
        <div className="rounded-xl border border-error-color/20 bg-error-color/10 px-4 py-3 text-sm text-error-color">
          {uploadError}
        </div>
      )}

      <div className="rounded-2xl border border-border-color/70 bg-surface-color/70 px-4 py-3 focus-within:border-student-color/35 focus-within:bg-surface-color transition-colors">
        <label className="block text-[10px] font-comfortaa-bold uppercase tracking-widest text-text-gray">
          Message au professeur <span className="normal-case tracking-normal">(optionnel)</span>
        </label>
        <textarea
          value={message}
          onChange={(e) => onMessageChange(e.target.value)}
          placeholder="Ajouter une précision sur votre copie..."
          rows={3}
          className="mt-2 w-full bg-transparent p-0 text-sm text-text-color placeholder:text-text-gray resize-none border-0 focus:outline-none focus:ring-0"
        />
      </div>

      <Button
        type="submit"
        variant="student"
        icon={Send}
        isLoading={submitting}
        disabled={!canSubmit}
      >
        Envoyer ma copie
      </Button>
    </>
  );
}
