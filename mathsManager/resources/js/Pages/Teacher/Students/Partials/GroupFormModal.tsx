import { useState } from 'react';
import { router } from '@inertiajs/react';
import { StudentGroup } from '@/types/models';
import Modal from '@/Components/Common/UI/Modal';
import Button from '@/Components/Common/UI/Button';
import { FolderPlus, Pencil } from 'lucide-react';
import { route } from 'ziggy-js';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  /** Si fourni, on est en mode édition. Sinon, création. */
  group?: StudentGroup;
}

export default function GroupFormModal({ isOpen, onClose, group }: Props) {
  const isEditing = !!group;
  const [name, setName] = useState(group?.name ?? '');
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = () => {
    if (!name.trim()) return;
    setIsSubmitting(true);

    const options = {
      preserveScroll: true,
      onSuccess: () => {
        onClose();
        setIsSubmitting(false);
        setName('');
      },
      onError: () => setIsSubmitting(false),
    };

    if (isEditing) {
      router.patch(route('teacher.groups.update', group.id), { name: name.trim() }, options);
    } else {
      router.post(route('teacher.groups.store'), { name: name.trim() }, options);
    }
  };

  return (
    <Modal show={isOpen} onClose={onClose} maxWidth="sm">
      <div className="p-6 space-y-5">
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 rounded-xl bg-teacher-color/10 flex items-center justify-center flex-shrink-0">
            {isEditing ? (
              <Pencil className="w-5 h-5 text-teacher-color" />
            ) : (
              <FolderPlus className="w-5 h-5 text-teacher-color" />
            )}
          </div>
          <div>
            <h2 className="text-base font-bold text-text-color">
              {isEditing ? 'Renommer le groupe' : 'Nouveau groupe'}
            </h2>
            <p className="text-xs text-text-gray">
              {isEditing
                ? `Renommer « ${group.name} »`
                : 'Créez un groupe pour organiser vos élèves.'}
            </p>
          </div>
        </div>

        <div>
          <label className="block text-sm font-bold text-text-color mb-1.5">Nom du groupe</label>
          <input
            type="text"
            value={name}
            onChange={(e) => setName(e.target.value)}
            onKeyDown={(e) => e.key === 'Enter' && handleSubmit()}
            placeholder="ex: Terminale, BTS1, Groupe du lundi…"
            maxLength={80}
            className="w-full rounded-xl border border-border-color bg-surface-color text-text-color px-3 py-2 text-sm focus:outline-none focus:border-teacher-color focus:ring-1 focus:ring-teacher-color"
            autoFocus
          />
        </div>

        <div className="flex justify-end gap-3 pt-1">
          <Button variant="ghost" onClick={onClose} disabled={isSubmitting}>
            Annuler
          </Button>
          <Button
            icon={isEditing ? Pencil : FolderPlus}
            iconSize={16}
            onClick={handleSubmit}
            isLoading={isSubmitting}
            disabled={!name.trim()}
          >
            {isEditing ? 'Renommer' : 'Créer'}
          </Button>
        </div>
      </div>
    </Modal>
  );
}
