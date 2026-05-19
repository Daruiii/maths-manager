import { useEffect, useState } from 'react';
import { router } from '@inertiajs/react';
import { StudentGroup, User } from '@/types/models';
import Modal from '@/Components/Common/UI/Modal';
import Button from '@/Components/Common/UI/Button';
import { FolderInput } from 'lucide-react';
import { route } from 'ziggy-js';
import GroupPickerList from '@/Pages/Teacher/Students/Partials/GroupPickerList';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  student: User;
  groups: StudentGroup[];
}

export default function AssignGroupModal({ isOpen, onClose, student, groups }: Props) {
  const [selectedGroupId, setSelectedGroupId] = useState<number | null>(student.group_id ?? null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  // Reset la sélection à chaque ouverture (évite de garder l'état d'un autre élève)
  useEffect(() => {
    if (isOpen) setSelectedGroupId(student.group_id ?? null);
  }, [isOpen, student.group_id]);

  const handleSubmit = () => {
    setIsSubmitting(true);
    router.patch(
      route('teacher.students.updateGroup', student.id),
      { group_id: selectedGroupId },
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
    <Modal show={isOpen} onClose={onClose} maxWidth="sm">
      <div className="p-6 space-y-4">
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 rounded-xl bg-teacher-color/10 flex items-center justify-center flex-shrink-0">
            <FolderInput className="w-5 h-5 text-teacher-color" />
          </div>
          <div>
            <h2 className="text-base font-bold text-text-color">Assigner à un groupe</h2>
            <p className="text-xs text-text-gray">
              {student.first_name} {student.last_name}
            </p>
          </div>
        </div>

        <GroupPickerList
          groups={groups}
          selectedGroupId={selectedGroupId}
          onSelect={setSelectedGroupId}
        />

        <div className="flex gap-2 pt-1">
          <Button variant="secondary" className="flex-1" onClick={onClose}>
            Annuler
          </Button>
          <Button
            className="flex-1"
            onClick={handleSubmit}
            disabled={selectedGroupId === (student.group_id ?? null) || isSubmitting}
            isLoading={isSubmitting}
          >
            Confirmer
          </Button>
        </div>
      </div>
    </Modal>
  );
}
