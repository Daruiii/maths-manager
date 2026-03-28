import AppLayout from '@/Layouts/AppLayout';
import { Head } from '@inertiajs/react';
import { User } from '@/types/models';
import Card from '@/Components/Common/UI/Card';
import PageHeader from '@/Components/Common/UI/PageHeader';
import { CheckCircle } from 'lucide-react';
import MobileBottomSheet from '@/Components/Common/UI/MobileBottomSheet';

import ApplicantList from '@/Pages/Admin/Partials/ApplicantList';
import ApplicantDetails from '@/Pages/Admin/Partials/ApplicantDetails';
import RejectionModal from '@/Pages/Admin/Partials/RejectionModal';
import ApprovalModal from '@/Pages/Admin/Partials/ApprovalModal';
import InviteModal from '@/Pages/Admin/Partials/InviteModal';
import { useTeacherApplications } from '@/Hooks/Admin/useTeacherApplications';

interface Props {
  applications: User[];
}

export default function TeacherApplications({ applications }: Props) {
  const {
    filter,
    setFilter,
    searchQuery,
    setSearchQuery,
    filteredApplications,
    selectedUser,
    selectedUserId,
    handleSelectUser,
    isMobileModalOpen,
    setIsMobileModalOpen,
    isApprovalModalOpen,
    openApproveModal,
    closeApproveModal,
    executeApproval,
    isInviteModalOpen,
    openInviteModal,
    closeInviteModal,
    executeInvite,
    isRejectionModalOpen,
    rejectionNotes,
    setRejectionNotes,
    openRejectModal,
    closeRejectModal,
    executeReject,
  } = useTeacherApplications(applications);

  return (
    <AppLayout title="Candidatures Professeurs" hideFooter>
      <Head title="Candidatures Professeurs" />

      <div className="py-6 lg:py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto flex flex-col lg:h-[calc(100vh-72px)] lg:overflow-hidden gap-4">
        <PageHeader
          title="Candidatures Professeurs"
          subtitle={`${filteredApplications.length} candidature(s) affichée(s).`}
          breadcrumbs={[{ label: 'Admin' }, { label: 'Candidatures' }]}
        />

        {applications.length === 0 ? (
          <Card className="p-8 text-center text-text-gray bg-secondary-color">
            <CheckCircle size={48} className="mx-auto text-success-color mb-4" />
            <h3 className="text-xl font-bold mb-2 text-text-color">Tout est à jour !</h3>
            <p>Il n&apos;y a aucune candidature de professeur en attente pour le moment.</p>
          </Card>
        ) : (
          <div className="flex flex-col lg:flex-row gap-6 flex-1 min-h-0 relative">
            {/* Colonne Gauche : Liste */}
            <div className="w-full lg:w-1/3 flex-shrink-0 flex flex-col gap-4">
              <ApplicantList
                applications={filteredApplications}
                selectedUserId={selectedUserId}
                onSelect={handleSelectUser}
                filter={filter}
                setFilter={setFilter}
                searchQuery={searchQuery}
                setSearchQuery={setSearchQuery}
              />
            </div>

            {/* Colonne Droite : Détails */}
            <MobileBottomSheet
              isOpen={isMobileModalOpen}
              onClose={() => setIsMobileModalOpen(false)}
              desktopMode="column"
            >
              {selectedUser ? (
                <ApplicantDetails
                  user={selectedUser}
                  onApprove={openApproveModal}
                  onReject={openRejectModal}
                  onInvite={openInviteModal}
                />
              ) : (
                <div className="h-full hidden lg:flex items-center justify-center p-8 bg-secondary-color rounded-2xl border-2 border-border-color border-dashed">
                  <p className="text-text-gray font-bold text-lg">
                    Sélectionnez un candidat pour voir ses détails.
                  </p>
                </div>
              )}
            </MobileBottomSheet>
          </div>
        )}
      </div>

      {/* Modals — chacune dans son propre Partial */}
      <RejectionModal
        isOpen={isRejectionModalOpen}
        onClose={closeRejectModal}
        user={selectedUser}
        notes={rejectionNotes}
        setNotes={setRejectionNotes}
        onSubmit={executeReject}
      />
      <ApprovalModal
        isOpen={isApprovalModalOpen}
        onClose={closeApproveModal}
        user={selectedUser}
        onConfirm={executeApproval}
      />
      <InviteModal
        isOpen={isInviteModalOpen}
        onClose={closeInviteModal}
        user={selectedUser}
        onConfirm={executeInvite}
      />
    </AppLayout>
  );
}
