import { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import StatusCard from '@/Components/Common/UI/StatusCard';
import { StudentGroup, TeacherInvitation, User } from '@/types/models';
import { UserPlus } from 'lucide-react';
import GroupFolderCard from '@/Pages/Teacher/Students/Partials/GroupFolderCard';
import StudentCard from '@/Pages/Teacher/Students/Partials/StudentCard';
import StudentsToolbar from '@/Pages/Teacher/Students/Partials/StudentsToolbar';
import InvitationLinkCompact from '@/Pages/Teacher/Students/Partials/InvitationLinkCompact';
import InvitationConfigModal from '@/Pages/Teacher/Students/Partials/InvitationConfigModal';
import GroupFormModal from '@/Pages/Teacher/Students/Partials/GroupFormModal';

interface Props {
  groups: StudentGroup[];
  ungroupedStudents: User[];
  invitation: TeacherInvitation | null;
}

export default function Index({ groups, ungroupedStudents, invitation }: Props) {
  const [isInviteOpen, setIsInviteOpen] = useState(false);
  const [isGroupOpen, setIsGroupOpen] = useState(false);
  const [search, setSearch] = useState('');

  const isEmpty = groups.length === 0 && ungroupedStudents.length === 0;

  return (
    <AppLayout title="Mes Élèves">
      <div className="py-12 px-4 sm:px-6 lg:px-8">
        <div className="max-w-7xl mx-auto">
          <PageHeader
            title="Mes Élèves"
            subtitle="Gérez vos élèves et partagez votre lien d'invitation."
            breadcrumbs={[{ label: 'Mes Élèves' }]}
            action={
              <InvitationLinkCompact
                invitation={invitation}
                onConfigure={() => setIsInviteOpen(true)}
              />
            }
          />

          <div className="mt-8 space-y-6">
            <StudentsToolbar
              search={search}
              onSearchChange={setSearch}
              onNewGroup={() => setIsGroupOpen(true)}
            />

            {isEmpty ? (
              <div className="py-12 bg-secondary-color rounded-2xl border-2 border-border-color border-dashed">
                <StatusCard
                  icon={UserPlus}
                  title="Aucun élève pour le moment"
                  description="Partagez votre lien d'invitation pour que vos élèves vous rejoignent."
                />
              </div>
            ) : (
              <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                {groups.map((group) => (
                  <GroupFolderCard key={group.id} group={group} />
                ))}
                {ungroupedStudents.map((student) => (
                  <StudentCard key={student.id} student={student} />
                ))}
              </div>
            )}
          </div>
        </div>
      </div>

      <InvitationConfigModal
        isOpen={isInviteOpen}
        onClose={() => setIsInviteOpen(false)}
        groups={groups}
        hasActiveLink={!!invitation}
      />

      <GroupFormModal isOpen={isGroupOpen} onClose={() => setIsGroupOpen(false)} />
    </AppLayout>
  );
}
