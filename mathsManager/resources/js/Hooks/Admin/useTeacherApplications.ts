import { useState, useMemo } from 'react';
import { router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { User } from '@/types';

export type FilterStatus = 'all' | 'to_invite' | 'invited';

export function useTeacherApplications(initialApplications: User[]) {
  const [filter, setFilter] = useState<FilterStatus>('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedUserId, setSelectedUserId] = useState<number | null>(
    initialApplications.length > 0 ? initialApplications[0].id : null
  );

  const [isMobileModalOpen, setIsMobileModalOpen] = useState(false);
  const [isRejectionModalOpen, setIsRejectionModalOpen] = useState(false);
  const [isApprovalModalOpen, setIsApprovalModalOpen] = useState(false);
  const [isInviteModalOpen, setIsInviteModalOpen] = useState(false);
  const [rejectionNotes, setRejectionNotes] = useState('');

  const filteredApplications = useMemo(() => {
    return initialApplications.filter((app) => {
      if (filter === 'to_invite' && app.calendly_invite_sent) return false;
      if (filter === 'invited' && !app.calendly_invite_sent) return false;

      if (searchQuery.trim() !== '') {
        const query = searchQuery.toLowerCase();
        const fullName = `${app.first_name} ${app.last_name}`.toLowerCase();
        const email = app.email.toLowerCase();
        if (!fullName.includes(query) && !email.includes(query)) {
          return false;
        }
      }

      return true;
    });
  }, [initialApplications, filter, searchQuery]);

  const selectedUser = initialApplications.find((app) => app.id === selectedUserId) || null;

  const handleSelectUser = (id: number) => {
    setSelectedUserId(id);
    setIsMobileModalOpen(true);
  };

  const openApproveModal = () => setIsApprovalModalOpen(true);
  const closeApproveModal = () => setIsApprovalModalOpen(false);

  const openInviteModal = () => setIsInviteModalOpen(true);
  const closeInviteModal = () => setIsInviteModalOpen(false);

  const openRejectModal = () => setIsRejectionModalOpen(true);
  const closeRejectModal = () => setIsRejectionModalOpen(false);

  const executeApproval = (user: User) => {
    router.post(
      route('admin.applications.approve', user.id),
      {},
      {
        preserveScroll: true,
        onSuccess: () => {
          setIsApprovalModalOpen(false);
          setIsMobileModalOpen(false);
        },
      }
    );
  };

  const executeInvite = (user: User) => {
    router.post(
      route('admin.applications.invite', user.id),
      {},
      {
        preserveScroll: true,
        onSuccess: () => {
          setIsInviteModalOpen(false);
        },
      }
    );
  };

  const executeReject = (user: User) => {
    router.post(
      route('admin.applications.reject', user.id),
      { admin_notes: rejectionNotes },
      {
        preserveScroll: true,
        onSuccess: () => {
          setIsRejectionModalOpen(false);
          setIsMobileModalOpen(false);
          setRejectionNotes('');
        },
      }
    );
  };

  return {
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

    // Approval
    isApprovalModalOpen,
    openApproveModal,
    closeApproveModal,
    executeApproval,

    // Invite
    isInviteModalOpen,
    openInviteModal,
    closeInviteModal,
    executeInvite,

    // Rejection
    isRejectionModalOpen,
    rejectionNotes,
    setRejectionNotes,
    openRejectModal,
    closeRejectModal,
    executeReject,
  };
}
