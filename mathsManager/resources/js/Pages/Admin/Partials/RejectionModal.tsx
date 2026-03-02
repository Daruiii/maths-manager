import { User } from '@/types';
import ConfirmationModal from '@/Components/Common/UI/ConfirmationModal';
import TextAreaInput from '@/Components/Common/Form/TextAreaInput';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  user: User | null;
  notes: string;
  setNotes: (notes: string) => void;
  onSubmit: (user: User) => void;
}

export default function RejectionModal({
  isOpen,
  onClose,
  user,
  notes,
  setNotes,
  onSubmit,
}: Props) {
  if (!user) return null;

  return (
    <ConfirmationModal
      isOpen={isOpen}
      onClose={onClose}
      onConfirm={() => onSubmit(user)}
      title="Refuser la candidature"
      type="danger"
      confirmText="Confirmer le refus"
    >
      {/* Message d'avertissement mis en évidence */}
      <div className="bg-surface-color border border-border-color rounded-xl p-4 sm:p-5 mb-6">
        <p className="text-text-color leading-relaxed">
          Vous êtes sur le point de refuser la candidature de{' '}
          <strong className="text-teacher-color font-bold">
            {user.first_name} {user.last_name}
          </strong>
          . Un email automatique lui sera envoyé pour l'informer de cette décision.
        </p>
      </div>

      {/* Zone de texte avec label */}
      <div className="space-y-3">
        <label
          htmlFor="notes"
          className="flex items-center gap-2 text-sm font-bold text-text-gray tracking-wider uppercase"
        >
          Motif / Note interne
          <span className="text-xxs bg-secondary-color text-text-gray px-2 py-0.5 rounded-full border border-border-color normal-case tracking-normal">
            Optionnel
          </span>
        </label>
        <TextAreaInput
          id="notes"
          className="w-full text-sm"
          rows={3}
          placeholder="Ex: Expérience insuffisante pour le niveau requis..."
          value={notes}
          onChange={(e) => setNotes(e.target.value)}
        />
      </div>
    </ConfirmationModal>
  );
}
