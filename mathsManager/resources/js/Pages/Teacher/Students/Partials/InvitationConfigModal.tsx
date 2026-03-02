import { useState } from 'react';
import { router } from '@inertiajs/react';
import { StudentGroup } from '@/types/models';
import Modal from '@/Components/Common/UI/Modal';
import Button from '@/Components/Common/UI/Button';
import { AlertTriangle, RefreshCw } from 'lucide-react';
import { route } from 'ziggy-js';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  groups: StudentGroup[];
  hasActiveLink: boolean;
}

export default function InvitationConfigModal({ isOpen, onClose, groups, hasActiveLink }: Props) {
  const [maxUses, setMaxUses] = useState('1');
  const [groupId, setGroupId] = useState<number | ''>('');
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = () => {
    setIsSubmitting(true);
    router.post(
      route('teacher.invitation.configure'),
      { max_uses: parseInt(maxUses, 10) || 1, group_id: groupId || null },
      {
        preserveScroll: true,
        onSuccess: () => {
          onClose();
          setIsSubmitting(false);
        },
        onError: () => setIsSubmitting(false),
      }
    );
  };

  return (
    <Modal show={isOpen} onClose={onClose} maxWidth="md">
      <div className="p-6 space-y-5">
        <div>
          <h2 className="text-lg font-bold text-text-color">Configurer le lien d'invitation</h2>
          <p className="text-sm text-text-gray mt-1">
            Définissez les paramètres de votre lien d'invitation.
          </p>
        </div>

        {hasActiveLink && (
          <div className="flex items-start gap-3 bg-warning-color/10 border border-warning-color/30 rounded-xl p-3 text-sm text-warning-color">
            <AlertTriangle size={16} className="flex-shrink-0 mt-0.5" />
            <span>Un lien est déjà actif. Le générer à nouveau invalidera l'ancien.</span>
          </div>
        )}

        {/* Nombre d'utilisations */}
        <div>
          <label className="block text-sm font-bold text-text-color mb-1.5">
            Nombre d'utilisations maximum
          </label>
          <input
            type="number"
            min={1}
            max={1000}
            value={maxUses}
            onChange={(e) => setMaxUses(e.target.value)}
            className="w-full rounded-xl border border-border-color bg-surface-color text-text-color px-3 py-2 text-sm focus:outline-none focus:border-teacher-color focus:ring-1 focus:ring-teacher-color"
          />
          <p className="text-xs text-text-gray mt-1">Mettez 1 pour un lien à usage unique.</p>
        </div>

        {/* Groupe optionnel */}
        <div>
          <label className="block text-sm font-bold text-text-color mb-1.5">
            Groupe d'arrivée (optionnel)
          </label>
          <select
            value={groupId}
            onChange={(e) => setGroupId(e.target.value === '' ? '' : Number(e.target.value))}
            className="w-full rounded-xl border border-border-color bg-surface-color text-text-color px-3 py-2 text-sm focus:outline-none focus:border-teacher-color focus:ring-1 focus:ring-teacher-color"
          >
            <option value="">Aucun groupe (élève sans groupe)</option>
            {groups.map((g) => (
              <option key={g.id} value={g.id}>
                {g.name}
              </option>
            ))}
          </select>
          <p className="text-xs text-text-gray mt-1">
            Les élèves rejoindront automatiquement ce groupe.
          </p>
        </div>

        {/* Actions */}
        <div className="flex justify-end gap-3 pt-2">
          <Button variant="ghost" onClick={onClose} disabled={isSubmitting}>
            Annuler
          </Button>
          <Button
            variant="secondary"
            icon={RefreshCw}
            iconSize={16}
            onClick={handleSubmit}
            isLoading={isSubmitting}
          >
            {hasActiveLink ? 'Régénérer le lien' : 'Générer le lien'}
          </Button>
        </div>
      </div>
    </Modal>
  );
}
