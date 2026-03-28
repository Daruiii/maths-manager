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
  const [selectedStudentIds, setSelectedStudentIds] = useState<Set<number>>(new Set());
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
    if (preselectedStudentId) setSelectedStudentIds(new Set([preselectedStudentId]));
    if (preselectedGroupId) setSelectedGroupIds(new Set([preselectedGroupId]));
  }, [isOpen, preselectedStudentId, preselectedGroupId]);

  const toggle = <T extends number>(set: Set<T>, id: T): Set<T> => {
    const next = new Set(set);
    next.has(id) ? next.delete(id) : next.add(id);
    return next;
  };

  const totalRecipients = useMemo(() => {
    const groupStudentIds = new Set(
      students
        .filter(
          (s) => s.group_id !== null && s.group_id !== undefined && selectedGroupIds.has(s.group_id)
        )
        .map((s) => s.id)
    );
    return new Set([...groupStudentIds, ...selectedStudentIds]).size;
  }, [students, selectedGroupIds, selectedStudentIds]);
  const problemIds = previewItems.filter((i) => i.item.kind === 'problem').map((i) => i.item.id);
  const exerciseIds = previewItems.filter((i) => i.item.kind === 'exercise').map((i) => i.item.id);

  const handleSubmit = () => {
    if (problemIds.length + exerciseIds.length === 0 || totalRecipients === 0) return;
    setIsSubmitting(true);
    router.post(
      route('teacher.ds.assign'),
      {
        problem_ids: problemIds,
        exercise_ids: exerciseIds,
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
  const ungroupedStudents = students.filter((s) => !s.group_id);

  const filteredGroups = useMemo(() => {
    if (!q) return groups;
    return groups.filter(
      (g) =>
        g.name.toLowerCase().includes(q) ||
        students.some((s) => s.group_id === g.id && matchesName(s, q))
    );
  }, [groups, students, q]);

  const filteredUngrouped = useMemo(() => {
    if (!q) return ungroupedStudents;
    return ungroupedStudents.filter((s) => matchesName(s, q));
  }, [ungroupedStudents, q]);

  return (
    <SlidePanel
      isOpen={isOpen}
      onClose={onClose}
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
              const isSelected = selectedGroupIds.has(group.id);
              const isExpanded = expandedGroups.has(group.id);
              const groupStudents = students.filter((s) => s.group_id === group.id);

              return (
                <CheckboxCard
                  key={group.id}
                  isSelected={isSelected}
                  onToggle={() => {}}
                  as="div"
                  className="overflow-hidden"
                >
                  <div className="flex items-center gap-2 p-2.5">
                    <CheckboxIndicator
                      isSelected={isSelected}
                      onToggle={() => setSelectedGroupIds(toggle(selectedGroupIds, group.id))}
                    />
                    <Users size={14} className="text-teacher-color flex-shrink-0" />
                    <span className="flex-1 text-sm text-text-color font-medium">{group.name}</span>
                    <span className="text-xs text-text-gray">
                      {group.students_count} élève{(group.students_count ?? 0) > 1 ? 's' : ''}
                    </span>
                    {groupStudents.length > 0 && (
                      <button
                        type="button"
                        onClick={() => setExpandedGroups(toggle(expandedGroups, group.id))}
                        className="p-0.5 text-text-gray hover:text-text-color"
                      >
                        {isExpanded ? <ChevronDown size={14} /> : <ChevronRight size={14} />}
                      </button>
                    )}
                  </div>
                  {isExpanded && groupStudents.length > 0 && (
                    <div className="border-t border-border-color bg-secondary-color/50 px-3 py-2 space-y-1.5">
                      {groupStudents.map((s) => {
                        const coveredByGroup = isSelected;
                        const isStudentSelected = coveredByGroup || selectedStudentIds.has(s.id);
                        return (
                          <CheckboxCard
                            key={s.id}
                            isSelected={isStudentSelected}
                            onToggle={
                              coveredByGroup
                                ? undefined
                                : () => setSelectedStudentIds(toggle(selectedStudentIds, s.id))
                            }
                            className="flex items-center gap-2 p-2"
                          >
                            <CheckboxIndicator isSelected={isStudentSelected} />
                            <UserAvatar user={s} size="sm" />
                            <span className="text-xs text-text-color">
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
                  onToggle={() => setSelectedStudentIds(toggle(selectedStudentIds, student.id))}
                  className="flex items-center gap-3 p-2.5"
                >
                  <CheckboxIndicator isSelected={isSelected} />
                  <UserAvatar user={student} size="sm" />
                  <span className="text-sm text-text-color">
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
