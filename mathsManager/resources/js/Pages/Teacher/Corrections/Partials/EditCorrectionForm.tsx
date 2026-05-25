import { router } from '@inertiajs/react';
import { Save, X } from 'lucide-react';
import { useState } from 'react';
import Button from '@/Components/Common/UI/Button';
import CorrectionFormFields from '@/Pages/Teacher/Corrections/Partials/CorrectionFormFields';
import type { CorrectionRequest } from '@/types/models';

interface Props {
  correctionRequest: CorrectionRequest;
  onCancel: () => void;
  onSaved: () => void;
}

export default function EditCorrectionForm({ correctionRequest, onCancel, onSaved }: Props) {
  const [editGrade, setEditGrade] = useState(
    correctionRequest.grade !== null && correctionRequest.grade !== undefined
      ? String(correctionRequest.grade)
      : ''
  );
  const [editMessage, setEditMessage] = useState(correctionRequest.correction_message ?? '');
  const [editSessionToken, setEditSessionToken] = useState<string | null>(null);
  const [editPictures, setEditPictures] = useState<string[]>(
    correctionRequest.correction_pictures ?? []
  );
  const [updating, setUpdating] = useState(false);

  function updateCorrection(e: React.SyntheticEvent) {
    e.preventDefault();
    if (updating) return;
    setUpdating(true);
    router.patch(
      route('teacher.corrections.update', correctionRequest.id),
      {
        ...(editSessionToken ? { upload_session_token: editSessionToken } : {}),
        existing_pictures: editPictures,
        correction_message: editMessage || null,
        grade: editGrade === '' ? null : Number(editGrade),
      },
      {
        onFinish: () => setUpdating(false),
        onSuccess: onSaved,
      }
    );
  }

  return (
    <form onSubmit={updateCorrection} className="space-y-4">
      <CorrectionFormFields
        title="Modifier la correction"
        message={editMessage}
        grade={editGrade}
        onMessageChange={setEditMessage}
        onGradeChange={setEditGrade}
        onTokenChange={setEditSessionToken}
        existingPictures={editPictures}
        onRemoveExisting={(path) => setEditPictures((prev) => prev.filter((p) => p !== path))}
      />
      <div className="flex items-center gap-2">
        <Button
          type="submit"
          variant="teacher"
          icon={Save}
          isLoading={updating}
          disabled={updating}
        >
          Enregistrer
        </Button>
        <Button type="button" variant="ghost" icon={X} onClick={onCancel} disabled={updating}>
          Annuler
        </Button>
      </div>
    </form>
  );
}
