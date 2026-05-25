import { router } from '@inertiajs/react';
import { Send } from 'lucide-react';
import { useState } from 'react';
import Button from '@/Components/Common/UI/Button';
import CorrectionFormFields from '@/Pages/Teacher/Corrections/Partials/CorrectionFormFields';
import type { CorrectionRequest } from '@/types/models';

interface Props {
  correctionRequest: CorrectionRequest;
}

export default function SendCorrectionForm({ correctionRequest }: Props) {
  const [sessionToken, setSessionToken] = useState<string | null>(null);
  const [correctionMessage, setCorrectionMessage] = useState('');
  const [grade, setGrade] = useState('');
  const [submitting, setSubmitting] = useState(false);

  function submitCorrection(e: React.SyntheticEvent) {
    e.preventDefault();
    if (submitting) return;
    setSubmitting(true);
    router.patch(
      route('teacher.corrections.send', correctionRequest.id),
      {
        ...(sessionToken ? { upload_session_token: sessionToken } : {}),
        correction_message: correctionMessage,
        grade: grade === '' ? null : Number(grade),
      },
      { onFinish: () => setSubmitting(false) }
    );
  }

  return (
    <form onSubmit={submitCorrection} className="space-y-4">
      <CorrectionFormFields
        title="Feedback"
        message={correctionMessage}
        grade={grade}
        onMessageChange={setCorrectionMessage}
        onGradeChange={setGrade}
        onTokenChange={setSessionToken}
      />
      <Button
        type="submit"
        variant="teacher"
        icon={Send}
        isLoading={submitting}
        disabled={submitting}
      >
        Envoyer la correction
      </Button>
    </form>
  );
}
