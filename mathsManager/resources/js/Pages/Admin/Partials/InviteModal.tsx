import { User } from '@/types';
import ConfirmationModal from '@/Components/Common/UI/ConfirmationModal';
import { Calendar } from 'lucide-react';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  user: User | null;
  onConfirm: (user: User) => void;
}

export default function InviteModal({ isOpen, onClose, user, onConfirm }: Props) {
  if (!user) return null;

  return (
    <ConfirmationModal
      isOpen={isOpen}
      onClose={onClose}
      onConfirm={() => onConfirm(user)}
      title="Envoyer l'invitation à un entretien"
      type="info"
      confirmText={user.calendly_invite_sent ? "Renvoyer l'invitation" : "Envoyer l'invitation"}
    >
      <p className="text-text-color leading-relaxed mb-4">
        Vous allez envoyer un email automatique avec le lien{' '}
        <b className="text-tertiary-color">Calendly</b> à{' '}
        <span className="font-bold text-text-color">
          {user.first_name} {user.last_name}
        </span>
        .
      </p>
      {user.calendly_invite_sent ? (
        <p className="text-warning-color text-sm font-bold bg-warning-color/10 p-3 rounded-md mb-4">
          ⚠️ Une invitation a déjà été envoyée à ce candidat. Confirmez-vous le renvoi de
          l&apos;email ?
        </p>
      ) : null}
      <div className="bg-surface-color p-4 rounded-lg border border-border-color text-sm">
        <p className="font-bold mb-2 flex items-center gap-2">
          <Calendar size={16} className="text-info-color" /> Actions :
        </p>
        <ul className="list-disc list-inside space-y-1 text-text-gray ml-1">
          <li>Envoi d&apos;un email avec le lien Calendly.</li>
          <li>
            Le profil sera tagué comme{' '}
            <span className="font-bold text-text-color">Invitation Envoyée</span>.
          </li>
          <li>
            Le compte <strong>ne sera pas</strong> encore approuvé ni activé.
          </li>
        </ul>
      </div>
    </ConfirmationModal>
  );
}
