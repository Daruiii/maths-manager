import { useState } from 'react';
import { Link, router } from '@inertiajs/react';
import { StudentGroup } from '@/types/models';
import { Folder, Pencil, Trash2 } from 'lucide-react';
import ConfirmationModal from '@/Components/Common/UI/ConfirmationModal';
import IconButton from '@/Components/Common/UI/IconButton';
import GroupFormModal from '@/Pages/Teacher/Students/Partials/GroupFormModal';
import { route } from 'ziggy-js';
import { CONTENT_TYPE_META } from '@/Constants/contentTypes';

interface Props {
  group: StudentGroup;
}

export default function GroupFolderCard({ group }: Props) {
  const [isEditOpen, setIsEditOpen] = useState(false);
  const [isDeleteOpen, setIsDeleteOpen] = useState(false);

  const handleDelete = () => {
    router.delete(route('teacher.groups.destroy', group.id), {
      preserveScroll: true,
      onSuccess: () => setIsDeleteOpen(false),
    });
  };

  return (
    <>
      <div className="relative card-dot-grid bg-secondary-color border border-border-color rounded-2xl p-4 flex flex-col items-center gap-3 hover:border-teacher-color/50 hover:bg-teacher-color/5 transition-colors group">
        {/* Actions rapides */}
        <div className="absolute top-2 right-2 flex gap-1">
          <IconButton
            icon={Pencil}
            iconSize={13}
            accentColor="teacher"
            onClick={(e) => {
              e.preventDefault();
              setIsEditOpen(true);
            }}
            title="Renommer"
          />
          <IconButton
            icon={Trash2}
            iconSize={13}
            accentColor="error"
            onClick={(e) => {
              e.preventDefault();
              setIsDeleteOpen(true);
            }}
            title="Supprimer"
          />
        </div>

        {/* Corps cliquable → navigation */}
        <Link
          href={route('teacher.students.group', group.id)}
          className="flex flex-col items-center gap-3 w-full"
        >
          <div className="w-14 h-14 rounded-2xl bg-teacher-color/10 flex items-center justify-center group-hover:bg-teacher-color/20 transition-colors">
            <Folder className="w-8 h-8 text-teacher-color" strokeWidth={1.5} />
          </div>
          <div className="text-center">
            <p className="text-sm font-bold text-text-color leading-tight truncate w-full max-w-[120px]">
              {group.name}
            </p>
            <p className="text-xs text-text-gray mt-0.5">
              <span className="text-student-color font-bold">{group.students_count ?? 0}</span>{' '}
              élève{(group.students_count ?? 0) > 1 ? 's' : ''}
            </p>
          </div>
        </Link>

        {/* Actions groupe (placeholder) */}
        <div className="flex items-center justify-center gap-2 pt-1">
          <IconButton
            icon={CONTENT_TYPE_META.ds.icon}
            variant="bordered"
            accentColor="teacher"
            onClick={(e) => {
              e.preventDefault();
              router.visit(route('teacher.ds.create', { group: group.id }));
            }}
            title={CONTENT_TYPE_META.ds.createForGroupLabel}
          />
          <IconButton
            icon={CONTENT_TYPE_META.td.icon}
            variant="bordered"
            accentColor="teacher"
            onClick={(e) => {
              e.preventDefault();
              router.visit(route('teacher.td.create', { group: group.id }));
            }}
            title={CONTENT_TYPE_META.td.createForGroupLabel}
          />
          <IconButton
            icon={CONTENT_TYPE_META.dm.icon}
            variant="bordered"
            accentColor="teacher"
            onClick={(e) => {
              e.preventDefault();
              router.visit(route('teacher.dm.create', { group: group.id }));
            }}
            title={CONTENT_TYPE_META.dm.createForGroupLabel}
          />
        </div>
      </div>

      <GroupFormModal isOpen={isEditOpen} onClose={() => setIsEditOpen(false)} group={group} />

      <ConfirmationModal
        isOpen={isDeleteOpen}
        onClose={() => setIsDeleteOpen(false)}
        onConfirm={handleDelete}
        title="Supprimer ce groupe ?"
        description={`Le groupe « ${group.name} » sera supprimé. Les élèves seront déplacés dans "Sans groupe".`}
        confirmText="Supprimer"
        type="danger"
      />
    </>
  );
}
