import { useState, useEffect, useMemo } from 'react';
import { router, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';
import type { StudentGroup, User as UserType, DSPreviewItem } from '@/types/models';

interface UseAssignStepOptions {
  isOpen: boolean;
  students: UserType[];
  groups: StudentGroup[];
  preselectedStudentId?: number | null;
  preselectedGroupId?: number | null;
  previewItems: DSPreviewItem[];
  assignRoute: string;
  includeProblems?: boolean;
  customTitle?: string;
  customLevel?: string;
  customInstructions?: string;
  dueDate?: string;
  onSuccess?: () => void;
  onClose: () => void;
}

export function useAssignStep({
  isOpen,
  students,
  groups,
  preselectedStudentId,
  preselectedGroupId,
  previewItems,
  assignRoute,
  includeProblems = false,
  customTitle,
  customLevel,
  customInstructions,
  dueDate = '',
  onSuccess,
  onClose,
}: UseAssignStepOptions) {
  const { errors } = usePage().props;
  const [selectedStudentIds, setSelectedStudentIds] = useState<Set<number>>(new Set());
  const [selectedGroupIds, setSelectedGroupIds] = useState<Set<number>>(new Set());
  const [expandedGroups, setExpandedGroups] = useState<Set<number>>(new Set());
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [search, setSearch] = useState('');

  useEffect(() => {
    if (!isOpen) {
      setSelectedStudentIds(new Set());
      setSelectedGroupIds(new Set());
      setExpandedGroups(new Set());
      return;
    }
    if (preselectedStudentId) {
      setSelectedStudentIds(new Set([preselectedStudentId]));
      setSelectedGroupIds(new Set());
    }
    if (preselectedGroupId) {
      const ids = students.filter((s) => s.group_id === preselectedGroupId).map((s) => s.id);
      setSelectedStudentIds(new Set(ids));
      setSelectedGroupIds(new Set([preselectedGroupId]));
      setExpandedGroups(new Set([preselectedGroupId]));
    }
  }, [isOpen, preselectedStudentId, preselectedGroupId, students]);

  const toggleStudent = (id: number) => {
    setSelectedStudentIds((prev) => {
      const next = new Set(prev);
      next.has(id) ? next.delete(id) : next.add(id);
      return next;
    });
  };

  const toggleGroup = (groupId: number) => {
    const groupStudentIds = students.filter((s) => s.group_id === groupId).map((s) => s.id);
    const allSelected = groupStudentIds.every((id) => selectedStudentIds.has(id));
    setSelectedStudentIds((prev) => {
      const next = new Set(prev);
      if (allSelected) {
        groupStudentIds.forEach((id) => next.delete(id));
      } else {
        groupStudentIds.forEach((id) => next.add(id));
      }
      return next;
    });
    setSelectedGroupIds((prev) => {
      const next = new Set(prev);
      allSelected ? next.delete(groupId) : next.add(groupId);
      return next;
    });
  };

  const toggleExpanded = (groupId: number) => {
    setExpandedGroups((prev) => {
      const next = new Set(prev);
      next.has(groupId) ? next.delete(groupId) : next.add(groupId);
      return next;
    });
  };

  const groupSelectionState = (groupId: number): 'all' | 'some' | 'none' => {
    const groupStudentIds = students.filter((s) => s.group_id === groupId).map((s) => s.id);
    if (groupStudentIds.length === 0) return 'none';
    const selected = groupStudentIds.filter((id) => selectedStudentIds.has(id)).length;
    if (selected === groupStudentIds.length) return 'all';
    if (selected > 0) return 'some';
    return 'none';
  };

  const problemIds = includeProblems
    ? previewItems.filter((i) => i.item.kind === 'problem').map((i) => i.item.id)
    : [];
  const exerciseIds = previewItems.filter((i) => i.item.kind === 'exercise').map((i) => i.item.id);
  const privateIds = previewItems.filter((i) => i.item.kind === 'private').map((i) => i.item.id);
  const totalItems = problemIds.length + exerciseIds.length + privateIds.length;
  const totalRecipients = selectedStudentIds.size;
  const dueDateError = typeof errors.due_date === 'string' ? errors.due_date : null;
  const contentError = typeof errors.content === 'string' ? errors.content : null;

  const handleSubmit = () => {
    if (totalItems === 0 || totalRecipients === 0) return;
    setIsSubmitting(true);
    const payload = {
      exercise_ids: exerciseIds,
      private_exercise_ids: privateIds,
      student_ids: Array.from(selectedStudentIds),
      group_ids: Array.from(selectedGroupIds),
      custom_title: customTitle,
      custom_level: customLevel,
      custom_instructions: customInstructions,
      due_date: dueDate || null,
      ...(includeProblems ? { problem_ids: problemIds } : {}),
    };
    router.post(route(assignRoute), payload, {
      onSuccess: () => {
        setIsSubmitting(false);
        onSuccess?.();
        onClose();
      },
      onError: () => setIsSubmitting(false),
    });
  };

  const q = search.toLowerCase().trim();

  const matchesName = (s: UserType, query: string) => {
    const full = `${s.first_name} ${s.last_name}`.toLowerCase();
    const reversed = `${s.last_name} ${s.first_name}`.toLowerCase();
    return full.includes(query) || reversed.includes(query);
  };

  const filteredGroups = useMemo(() => {
    if (!q) return groups;
    return groups.filter(
      (g) =>
        g.name.toLowerCase().includes(q) ||
        students.some((s) => s.group_id === g.id && matchesName(s, q))
    );
  }, [groups, students, q]);

  const filteredUngrouped = useMemo(() => {
    const ungrouped = students.filter((s) => !s.group_id);
    if (!q) return ungrouped;
    return ungrouped.filter((s) => matchesName(s, q));
  }, [students, q]);

  return {
    search,
    setSearch,
    isSubmitting,
    selectedStudentIds,
    expandedGroups,
    toggleStudent,
    toggleGroup,
    toggleExpanded,
    groupSelectionState,
    handleSubmit,
    filteredGroups,
    filteredUngrouped,
    totalItems,
    totalRecipients,
    dueDateError,
    contentError,
  };
}
