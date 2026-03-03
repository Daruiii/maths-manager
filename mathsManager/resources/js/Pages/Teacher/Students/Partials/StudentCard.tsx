import { useState } from 'react';
import { router } from '@inertiajs/react';
import { User } from '@/types/models';
import { Unlink, BookOpen, FileText } from 'lucide-react';
import ConfirmationModal from '@/Components/Common/UI/ConfirmationModal';
import UserCard from '@/Components/Features/User/UserCard';
import { route } from 'ziggy-js';

interface Props {
  student: User;
}

export default function StudentCard({ student }: Props) {
  const [isConfirmOpen, setIsConfirmOpen] = useState(false);

  const handleRemove = () => {
    router.delete(route('teacher.students.remove', student.id), {
      preserveScroll: true,
      onSuccess: () => setIsConfirmOpen(false),
    });
  };

  return (
    <>
      <UserCard
        user={student}
        // TODO: href={route('teacher.students.show', student.id)}
        accentColor="student"
        variant="dot-grid"
        hoverAction={
          <button
            onClick={(e) => {
              e.preventDefault();
              setIsConfirmOpen(true);
            }}
            className="p-1 rounded-lg text-text-gray hover:text-error-color hover:bg-error-color/10 transition-colors"
            title="Désassocier"
          >
            <Unlink size={13} />
          </button>
        }
      >
        <div className="flex items-center justify-center gap-2 pt-1">
          <button
            disabled
            title="DS - Bientôt disponible"
            className="p-1.5 rounded-lg border border-border-color/50 text-text-gray/40 cursor-not-allowed"
          >
            <BookOpen size={14} />
          </button>
          <button
            disabled
            title="Fiche - Bientôt disponible"
            className="p-1.5 rounded-lg border border-border-color/50 text-text-gray/40 cursor-not-allowed"
          >
            <FileText size={14} />
          </button>
        </div>
      </UserCard>

      <ConfirmationModal
        isOpen={isConfirmOpen}
        onClose={() => setIsConfirmOpen(false)}
        onConfirm={handleRemove}
        title="Désassocier cet élève ?"
        description={`${student.first_name} ${student.last_name} ne sera plus rattaché à votre classe. Il pourra rejoindre un autre professeur.`}
        confirmText="Désassocier"
        type="danger"
      />
    </>
  );
}
