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
  return (
    <>
      <TheoremCard accent="student" dotted>
        <SectionLabel>Envoyer votre copie</SectionLabel>
        <div className="mt-3">
          <UploadSessionWidget
            purpose="correction_submission"
            accentColor="student"
            onTokenChange={onTokenChange}
          />
        </div>
      </TheoremCard>
      {uploadError && (
        <div className="rounded-xl border border-error-color/20 bg-error-color/10 px-4 py-3 text-sm text-error-color">
          {uploadError}
        </div>
      )}
      <textarea
        value={message}
        onChange={(e) => onMessageChange(e.target.value)}
        placeholder="Message pour le professeur (optionnel)"
        rows={3}
        className="w-full rounded-xl border border-border-color bg-secondary-color px-4 py-3 text-sm text-text-color placeholder:text-text-gray resize-none focus:outline-none focus:border-student-color/50"
      />
      <Button
        type="submit"
        variant="student"
        icon={Send}
        isLoading={submitting}
        disabled={!sessionToken}
      >
        Envoyer ma copie
      </Button>
    </>
  );
}
