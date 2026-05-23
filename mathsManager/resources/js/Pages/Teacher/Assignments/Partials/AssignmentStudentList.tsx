import { ChevronDown, ChevronRight, X } from 'lucide-react';
import SearchInput from '@/Components/Common/Form/SearchInput';
import AssignmentStudentRow from '@/Pages/Teacher/Assignments/Partials/AssignmentStudentRow';
import { getTeacherStatusLabel } from '@/Constants/statuses';
import type { AssignmentGroup, AssignmentItem } from '@/Pages/Teacher/Assignments/Partials/types';
import type { BatchType } from '@/types/api';

interface Props {
  type: BatchType;
  items: AssignmentItem[];
  displayedItems: AssignmentItem[];
  groupedItems: AssignmentGroup[];
  showGroupHeaders: boolean;
  statusFilter: string | null;
  studentSearch: string;
  collapsedGroups: Set<string>;
  onStatusFilterChange: (status: string | null) => void;
  onStudentSearchChange: (search: string) => void;
  onToggleGroup: (key: string) => void;
  onUnlockStudent: (id: number) => void;
}

export default function AssignmentStudentList({
  type,
  items,
  displayedItems,
  groupedItems,
  showGroupHeaders,
  statusFilter,
  studentSearch,
  collapsedGroups,
  onStatusFilterChange,
  onStudentSearchChange,
  onToggleGroup,
  onUnlockStudent,
}: Props) {
  return (
    <section className="space-y-3 animate-fadeInUp" style={{ animationDelay: '80ms' }}>
      <div className="flex items-center justify-between gap-3">
        <p className="mm-section-header">
          {statusFilter
            ? `${displayedItems.length} élève${displayedItems.length > 1 ? 's' : ''} — ${getTeacherStatusLabel(statusFilter, type)}`
            : `${items.length} élève${items.length > 1 ? 's' : ''}`}
        </p>
        <div className="flex items-center gap-2">
          {statusFilter && (
            <button
              onClick={() => onStatusFilterChange(null)}
              className="text-[11px] font-comfortaa-bold text-teacher-color hover:underline flex items-center gap-1"
            >
              <X size={11} /> Filtre
            </button>
          )}
          <SearchInput
            value={studentSearch}
            onChange={onStudentSearchChange}
            placeholder="Chercher un élève…"
            className="w-44"
          />
        </div>
      </div>

      <div className="bg-secondary-color border border-border-color rounded-2xl overflow-hidden">
        {displayedItems.length === 0 ? (
          <div className="flex flex-col items-center py-10 gap-2 text-text-gray text-sm">
            <p>Aucun élève ne correspond.</p>
          </div>
        ) : showGroupHeaders ? (
          groupedItems.map((group) => (
            <StudentGroup
              key={group.key}
              group={group}
              type={type}
              isCollapsed={collapsedGroups.has(group.key)}
              onToggleGroup={onToggleGroup}
              onUnlockStudent={onUnlockStudent}
            />
          ))
        ) : (
          displayedItems.map((item, i) => (
            <AssignmentStudentRow
              key={item.id}
              item={item}
              type={type}
              isLast={i === displayedItems.length - 1}
              onUnlockStudent={onUnlockStudent}
            />
          ))
        )}
      </div>
    </section>
  );
}

interface StudentGroupProps {
  group: AssignmentGroup;
  type: BatchType;
  isCollapsed: boolean;
  onToggleGroup: (key: string) => void;
  onUnlockStudent: (id: number) => void;
}

function StudentGroup({
  group,
  type,
  isCollapsed,
  onToggleGroup,
  onUnlockStudent,
}: StudentGroupProps) {
  return (
    <div>
      <button
        type="button"
        onClick={() => onToggleGroup(group.key)}
        className="w-full flex items-center justify-between px-4 py-2 bg-primary-color/30 border-b border-border-color hover:bg-primary-color/50 transition-colors"
      >
        <span className="flex items-center gap-1.5 text-[11px] font-comfortaa-bold text-text-color uppercase tracking-wide">
          {isCollapsed ? (
            <ChevronRight size={12} className="text-teacher-color" />
          ) : (
            <ChevronDown size={12} className="text-teacher-color" />
          )}
          {group.name ?? 'Sans groupe'}
        </span>
        <span className="text-[11px] text-text-gray">{group.items.length}</span>
      </button>
      {!isCollapsed &&
        group.items.map((item, i) => (
          <AssignmentStudentRow
            key={item.id}
            item={item}
            type={type}
            isLast={i === group.items.length - 1}
            onUnlockStudent={onUnlockStudent}
          />
        ))}
    </div>
  );
}
