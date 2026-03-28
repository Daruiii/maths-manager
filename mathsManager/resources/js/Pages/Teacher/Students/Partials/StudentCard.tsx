import { useState } from 'react';
import { router, Link } from '@inertiajs/react';
import { StudentGroup, User } from '@/types/models';
import { Unlink, BookOpen, FileText, FolderInput } from 'lucide-react';
import ConfirmationModal from '@/Components/Common/UI/ConfirmationModal';
import UserCard from '@/Components/Features/User/UserCard';
import AssignGroupModal from '@/Pages/Teacher/Students/Partials/AssignGroupModal';
import { route } from 'ziggy-js';

interface Props {
  student: User;
  groups: StudentGroup[];
  showGroupBadge?: boolean;
}

export default function StudentCard({ student, groups, showGroupBadge = true }: Props) {
  const [isConfirmOpen, setIsConfirmOpen] = useState(false);
  const [isAssignOpen, setIsAssignOpen] = useState(false);

  const group = student.group_id ? groups.find((g) => g.id === student.group_id) : null;

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
        accentColor="student"
        variant="dot-grid"
        topLeftContent={
          showGroupBadge && group ? (
            <Link
              href={route('teacher.students.group', group.id)}
              onClick={(e) => e.stopPropagation()}
              className="flex items-center gap-1 text-[10px] text-text-gray bg-surface-color border border-border-color hover:border-teacher-color hover:text-teacher-color px-1.5 py-0.5 rounded-full max-w-[90px] transition-colors"
            >
              <FolderInput size={9} className="flex-shrink-0" />
              <span className="truncate">{group.name}</span>
            </Link>
          ) : undefined
        }
        hoverAction={
          <div className="flex gap-1">
            <button
              onClick={(e) => {
                e.preventDefault();
                setIsAssignOpen(true);
              }}
              className="p-1 rounded-lg text-text-gray hover:text-teacher-color hover:bg-teacher-color/10 transition-colors"
              title="Assigner à un groupe"
            >
              <FolderInput size={13} />
            </button>
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
          </div>
        }
      >
        <div className="flex items-center justify-center gap-2 pt-1">
          <button
            onClick={(e) => {
              e.preventDefault();
              router.visit(route('teacher.ds.create', { student: student.id }));
            }}
            title="Créer un DS"
            className="p-1.5 rounded-lg border border-border-color/50 text-text-gray hover:text-teacher-color hover:border-teacher-color hover:bg-teacher-color/10 transition-colors"
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

      <AssignGroupModal
        isOpen={isAssignOpen}
        onClose={() => setIsAssignOpen(false)}
        student={student}
        groups={groups}
      />

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
