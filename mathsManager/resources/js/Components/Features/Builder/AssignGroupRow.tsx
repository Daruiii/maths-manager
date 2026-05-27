import { Users, ChevronDown, ChevronRight } from 'lucide-react';
import type { StudentGroup, User as UserType } from '@/types/models';
import UserAvatar from '@/Components/Common/Avatar/UserAvatar';
import CheckboxCard, { CheckboxIndicator } from '@/Components/Common/UI/CheckboxCard';

interface Props {
  group: StudentGroup;
  groupStudents: UserType[];
  state: 'all' | 'some' | 'none';
  isExpanded: boolean;
  selectedStudentIds: Set<number>;
  onToggleGroup: () => void;
  onToggleExpand: () => void;
  onToggleStudent: (id: number) => void;
}

export default function AssignGroupRow({
  group,
  groupStudents,
  state,
  isExpanded,
  selectedStudentIds,
  onToggleGroup,
  onToggleExpand,
  onToggleStudent,
}: Props) {
  return (
    <CheckboxCard
      isSelected={state === 'all' || state === 'some'}
      onToggle={() => {}}
      as="div"
      className="overflow-hidden"
    >
      <div className="flex items-center gap-2 p-2.5">
        <CheckboxIndicator
          isSelected={state === 'all'}
          indeterminate={state === 'some'}
          onToggle={onToggleGroup}
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
            onClick={onToggleExpand}
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
                onToggle={() => onToggleStudent(s.id)}
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
}
