import { User } from '@/types';
import ConfirmationModal from '@/Components/Common/UI/ConfirmationModal';
import { CheckCircle } from 'lucide-react';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  user: User | null;
  onConfirm: (user: User) => void;
}

export default function ApprovalModal({ isOpen, onClose, user, onConfirm }: Props) {
  if (!user) return null;

  return (
    <ConfirmationModal
      isOpen={isOpen}
      onClose={onClose}
      onConfirm={() => onConfirm(user)}
      title="Valider la candidature"
      type="success"
      confirmText="Valider le profil"
    >
      <p className="text-text-color leading-relaxed mb-4">
        Vous êtes sur le point d&apos;approuver définitivement la candidature de{' '}
        <strong className="text-success-color font-bold">
          {user.first_name} {user.last_name}
        </strong>
        .
      </p>
      <div className="bg-surface-color p-4 rounded-lg border border-border-color text-sm">
        <p className="font-bold mb-2 flex items-center gap-2">
          <CheckCircle size={16} className="text-success-color" /> Actions :
        </p>
        <ul className="list-disc list-inside space-y-1 text-text-gray ml-1">
          <li>
            Son compte deviendra officiellement{' '}
            <span className="font-bold text-text-color">Actif</span>.
          </li>
          <li>Un email de bienvenue lui sera envoyé.</li>
        </ul>
      </div>
    </ConfirmationModal>
  );
}
