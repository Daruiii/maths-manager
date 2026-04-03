import ConfirmationModal from '@/Components/Common/UI/ConfirmationModal';
import { PrivateExerciseBlockingIssue } from '@/Utils/privateExerciseValidation';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  issues: PrivateExerciseBlockingIssue[];
  title?: string;
  description?: string;
}

export default function FormBlockingIssuesModal({
  isOpen,
  onClose,
  issues,
  title = 'Enregistrement bloqué',
  description = "Corrigez ces points avant d'enregistrer.",
}: Props) {
  return (
    <ConfirmationModal
      isOpen={isOpen}
      onClose={onClose}
      onConfirm={onClose}
      title={title}
      description={description}
      confirmText="J'ai compris"
      cancelText="Fermer"
      type="warning"
    >
      <ul className="max-h-56 list-disc space-y-1 overflow-y-auto pl-5 text-sm text-text-color custom-scrollbar">
        {issues.map((issue) => (
          <li key={issue.key}>{issue.message}</li>
        ))}
      </ul>
    </ConfirmationModal>
  );
}
