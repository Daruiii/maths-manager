import { useState, useEffect, useMemo } from 'react';
import { router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { Users, ChevronDown, ChevronRight } from 'lucide-react';
import { StudentGroup, User as UserType, DSPreviewItem } from '@/types/models';
import UserAvatar from '@/Components/Common/UI/UserAvatar';
import Button from '@/Components/Common/UI/Button';
import SearchBar from '@/Components/Common/UI/SearchBar';
import SlidePanel from '@/Components/Common/UI/SlidePanel';
import CheckboxCard, { CheckboxIndicator } from '@/Components/Common/UI/CheckboxCard';
import EmptyState from '@/Components/Common/UI/EmptyState';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  onSuccess?: () => void;
  previewItems: DSPreviewItem[];
  students: UserType[];
  groups: StudentGroup[];
  preselectedStudentId?: number | null;
  preselectedGroupId?: number | null;
  dsTitle?: string;
  dsLevel?: string;
  dsInstructions?: string;
}

export default function AssignStep({
  isOpen,
  onClose,
  onSuccess,
  previewItems,
  students,
  groups,
  preselectedStudentId,
  preselectedGroupId,
  dsTitle,
  dsLevel,
  dsInstructions,
}: Props) {
  /** Single source of truth — individual student IDs who will receive the DS */
  const [selectedStudentIds, setSelectedStudentIds] = useState<Set<number>>(new Set());
  /** Metadata only — tracks which groups were bulk-selected (for batch history) */
  const [selectedGroupIds, setSelectedGroupIds] = useState<Set<number>>(new Set());
  const [expandedGroups, setExpandedGroups] = useState<Set<number>>(new Set());
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [search, setSearch] = useState('');

  useEffect(() => {
    if (!isOpen) {
      setSelectedStudentIds(new Set());
      setSelectedGroupIds(new Set());
      return;
    }
    if (preselectedStudentId) {
      setSelectedStudentIds(new Set([preselectedStudentId]));
    }
    if (preselectedGroupId) {
      const groupStudentIds = students
        .filter((s) => s.group_id === preselectedGroupId)
        .map((s) => s.id);
      setSelectedStudentIds(new Set(groupStudentIds));
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

  /** Select all / deselect all students in a group */
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
    // Track group selection as metadata for batch history
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

  /** Derive group checkbox state from selectedStudentIds */
  const groupSelectionState = (groupId: number): 'all' | 'some' | 'none' => {
    const groupStudentIds = students.filter((s) => s.group_id === groupId).map((s) => s.id);
    if (groupStudentIds.length === 0) return 'none';
    const selectedCount = groupStudentIds.filter((id) => selectedStudentIds.has(id)).length;
    if (selectedCount === groupStudentIds.length) return 'all';
    if (selectedCount > 0) return 'some';
    return 'none';
  };

  const problemIds = previewItems.filter((i) => i.item.kind === 'problem').map((i) => i.item.id);
  const exerciseIds = previewItems.filter((i) => i.item.kind === 'exercise').map((i) => i.item.id);
  const privateIds = previewItems.filter((i) => i.item.kind === 'private').map((i) => i.item.id);
  const totalRecipients = selectedStudentIds.size;

  const handleSubmit = () => {
    if (problemIds.length + exerciseIds.length + privateIds.length === 0 || totalRecipients === 0)
      return;
    setIsSubmitting(true);
    router.post(
      route('teacher.ds.assign'),
      {
        problem_ids: problemIds,
        exercise_ids: exerciseIds,
        private_exercise_ids: privateIds,
        student_ids: Array.from(selectedStudentIds),
        group_ids: Array.from(selectedGroupIds),
        custom_title: dsTitle,
        custom_level: dsLevel,
        custom_instructions: dsInstructions,
      },
      {
        onSuccess: () => {
          setIsSubmitting(false);
          onSuccess?.();
          onClose();
        },
        onError: () => setIsSubmitting(false),
      }
    );
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

  return (
    <SlidePanel
      isOpen={isOpen}
      onClose={onClose}
      size="sm"
      title="Assigner le DS"
      subtitle={`${previewItems.length} exercice${previewItems.length > 1 ? 's' : ''} · 1 DS créé par élève`}
      footer={
        <Button
          onClick={handleSubmit}
          disabled={totalRecipients === 0 || problemIds.length + exerciseIds.length === 0}
          isLoading={isSubmitting}
          variant="teacher"
          className="w-full justify-center"
        >
          Assigner à{' '}
          {totalRecipients > 0
            ? `${totalRecipients} destinataire${totalRecipients > 1 ? 's' : ''}`
            : '…'}
        </Button>
      }
    >
      <SearchBar
        placeholder="Rechercher un élève ou un groupe…"
        value={search}
        onChange={(e) => setSearch(e.target.value)}
        onClear={() => setSearch('')}
        focusRingClass="focus:border-teacher-color focus:ring-teacher-color"
      />

      {/* Groups */}
      {filteredGroups.length > 0 && (
        <section>
          <p className="text-xs font-medium text-text-gray uppercase tracking-wide mb-2">Groupes</p>
          <div className="space-y-1.5">
            {filteredGroups.map((group) => {
              const state = groupSelectionState(group.id);
              const isExpanded = expandedGroups.has(group.id);
              const groupStudents = students.filter((s) => s.group_id === group.id);

              return (
                <CheckboxCard
                  key={group.id}
                  isSelected={state === 'all' || state === 'some'}
                  onToggle={() => {}}
                  as="div"
                  className="overflow-hidden"
                >
                  <div className="flex items-center gap-2 p-2.5">
                    <CheckboxIndicator
                      isSelected={state === 'all'}
                      indeterminate={state === 'some'}
                      onToggle={() => toggleGroup(group.id)}
                    />
                    <Users size={14} className="text-teacher-color flex-shrink-0" />
                    <span className="flex-1 min-w-0 text-sm text-text-color font-medium truncate">
                      {group.name}
                    </span>
                    <span className="text-xs text-text-gray shrink-0">
                      {group.students_count} élève{(group.students_count ?? 0) > 1 ? 's' : ''}
                    </span>
                    {groupStudents.length > 0 && (
                      <button
                        type="button"
                        onClick={() => toggleExpanded(group.id)}
                        className="p-0.5 text-text-gray hover:text-text-color"
                      >
                        {isExpanded ? <ChevronDown size={14} /> : <ChevronRight size={14} />}
                      </button>
                    )}
                  </div>

                  {isExpanded && groupStudents.length > 0 && (
                    <div className="border-t border-border-color bg-secondary-color/50 px-3 py-2 space-y-1.5">
                      {groupStudents.map((s) => {
                        const isSelected = selectedStudentIds.has(s.id);
                        return (
                          <CheckboxCard
                            key={s.id}
                            isSelected={isSelected}
                            onToggle={() => toggleStudent(s.id)}
                            className="flex items-center gap-2 p-2"
                          >
                            <CheckboxIndicator isSelected={isSelected} />
                            <UserAvatar user={s} size="sm" />
                            <span className="text-xs text-text-color flex-1 min-w-0 truncate">
                              {s.first_name} {s.last_name}
                            </span>
                          </CheckboxCard>
                        );
                      })}
                    </div>
                  )}
                </CheckboxCard>
              );
            })}
          </div>
        </section>
      )}

      {/* Ungrouped students */}
      {filteredUngrouped.length > 0 && (
        <section>
          <p className="text-xs font-medium text-text-gray uppercase tracking-wide mb-2">
            Élèves sans groupe
          </p>
          <div className="space-y-1.5">
            {filteredUngrouped.map((student) => {
              const isSelected = selectedStudentIds.has(student.id);
              return (
                <CheckboxCard
                  key={student.id}
                  isSelected={isSelected}
                  onToggle={() => toggleStudent(student.id)}
                  className="flex items-center gap-3 p-2.5"
                >
                  <CheckboxIndicator isSelected={isSelected} />
                  <UserAvatar user={student} size="sm" />
                  <span className="text-sm text-text-color flex-1 min-w-0 truncate">
                    {student.first_name} {student.last_name}
                  </span>
                </CheckboxCard>
              );
            })}
          </div>
        </section>
      )}

      {filteredGroups.length === 0 && filteredUngrouped.length === 0 && (
        <EmptyState
          icon={Users}
          description={
            q
              ? 'Aucun résultat pour cette recherche.'
              : 'Aucun élève dans votre classe pour le moment.'
          }
          accentColor="default"
        />
      )}
    </SlidePanel>
  );
}
