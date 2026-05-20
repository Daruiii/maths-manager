import { Calendar, Users } from 'lucide-react';
import type { StudentGroup, User as UserType, DSPreviewItem } from '@/types/models';
import Button from '@/Components/Common/UI/Button';
import SearchBar from '@/Components/Common/UI/SearchBar';
import SlidePanel from '@/Components/Common/UI/SlidePanel';
import CheckboxCard, { CheckboxIndicator } from '@/Components/Common/UI/CheckboxCard';
import EmptyState from '@/Components/Common/UI/EmptyState';
import UserAvatar from '@/Components/Common/UI/UserAvatar';
import AssignGroupRow from '@/Components/Features/Builder/AssignGroupRow';
import { useAssignStep } from '@/Hooks/Builder/useAssignStep';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  onSuccess?: () => void;
  previewItems: DSPreviewItem[];
  students: UserType[];
  groups: StudentGroup[];
  preselectedStudentId?: number | null;
  preselectedGroupId?: number | null;
  assignRoute: string;
  title: string;
  entityLabel: string;
  includeProblems?: boolean;
  customTitle?: string;
  customLevel?: string;
  customInstructions?: string;
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
  assignRoute,
  title,
  entityLabel,
  includeProblems = false,
  customTitle,
  customLevel,
  customInstructions,
}: Props) {
  const {
    search,
    setSearch,
    dueDate,
    setDueDate,
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
    today,
    dueDateError,
    contentError,
  } = useAssignStep({
    isOpen,
    students,
    groups,
    preselectedStudentId,
    preselectedGroupId,
    previewItems,
    assignRoute,
    includeProblems,
    customTitle,
    customLevel,
    customInstructions,
    onSuccess,
    onClose,
  });

  return (
    <SlidePanel
      isOpen={isOpen}
      onClose={onClose}
      size="sm"
      title={title}
      subtitle={`${previewItems.length} exercice${previewItems.length > 1 ? 's' : ''} · 1 ${entityLabel} créé par élève`}
      footer={
        <Button
          onClick={handleSubmit}
          disabled={totalRecipients === 0 || totalItems === 0}
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

      <section className="rounded-2xl border border-border-color bg-secondary-color p-3 space-y-2">
        <label className="flex items-center gap-2 text-xs font-comfortaa-bold text-text-gray uppercase tracking-wide">
          <Calendar size={13} className="text-teacher-color" />
          Échéance optionnelle
        </label>
        <input
          type="date"
          min={today}
          value={dueDate}
          onChange={(e) => setDueDate(e.target.value)}
          className="w-full rounded-xl border border-border-color bg-surface-color px-3 py-2 text-sm text-text-color focus:outline-none focus:border-teacher-color/50"
        />
        {dueDateError && <p className="text-xs text-error-color">{dueDateError}</p>}
        {contentError && <p className="text-xs text-error-color">{contentError}</p>}
      </section>

      {filteredGroups.length > 0 && (
        <section>
          <p className="text-xs font-medium text-text-gray uppercase tracking-wide mb-2">Groupes</p>
          <div className="space-y-1.5">
            {filteredGroups.map((group) => (
              <AssignGroupRow
                key={group.id}
                group={group}
                groupStudents={students.filter((s) => s.group_id === group.id)}
                state={groupSelectionState(group.id)}
                isExpanded={expandedGroups.has(group.id)}
                selectedStudentIds={selectedStudentIds}
                onToggleGroup={() => toggleGroup(group.id)}
                onToggleExpand={() => toggleExpanded(group.id)}
                onToggleStudent={toggleStudent}
              />
            ))}
          </div>
        </section>
      )}

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
            search.trim()
              ? 'Aucun résultat pour cette recherche.'
              : 'Aucun élève dans votre classe pour le moment.'
          }
          accentColor="default"
        />
      )}
    </SlidePanel>
  );
}
