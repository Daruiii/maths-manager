import SearchInput from '@/Components/Common/Form/SearchInput';
import Select from '@/Components/Common/Form/Select';
import type {
  TeacherGroupOption,
  ViewMode,
} from '@/Pages/Teacher/Bureau/Partials/bureauDevoirsTypes';

interface Props {
  view: ViewMode;
  pendingOnly: boolean;
  totalPending: number;
  groups: TeacherGroupOption[];
  groupId: number | null;
  search: string;
  onViewChange: (view: ViewMode) => void;
  onPendingOnlyChange: (pendingOnly: boolean) => void;
  onGroupChange: (groupId: number | null) => void;
  onSearchChange: (search: string) => void;
}

export default function BureauDevoirsFilters({
  view,
  pendingOnly,
  totalPending,
  groups,
  groupId,
  search,
  onViewChange,
  onPendingOnlyChange,
  onGroupChange,
  onSearchChange,
}: Props) {
  return (
    <div className="flex items-center gap-2 flex-wrap justify-between">
      <div className="flex items-center gap-2 flex-wrap">
        <div className="flex rounded-xl border border-border-color overflow-hidden text-[11px] font-comfortaa-bold">
          {(['active', 'archived'] as ViewMode[]).map((mode) => (
            <button
              key={mode}
              onClick={() => onViewChange(mode)}
              className={`px-3 py-1.5 transition-colors ${
                view === mode
                  ? 'bg-teacher-color/10 text-teacher-color'
                  : 'text-text-gray hover:text-text-color'
              }`}
            >
              {mode === 'active' ? 'Actifs' : 'Archivés'}
            </button>
          ))}
        </div>
        {view === 'active' && totalPending > 0 && (
          <button
            onClick={() => onPendingOnlyChange(!pendingOnly)}
            className={`flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-[11px] font-comfortaa-bold transition-colors ${
              pendingOnly
                ? 'bg-warning-color/15 text-warning-color border-warning-color/30'
                : 'border-border-color text-text-gray hover:text-text-color'
            }`}
          >
            À traiter{pendingOnly && <span className="font-cmu-serif">{totalPending}</span>}
          </button>
        )}
        {groups.length > 0 && (
          <Select
            size="sm"
            searchable
            value={groupId !== null ? String(groupId) : ''}
            onChange={(value) => onGroupChange(value ? Number(value) : null)}
            placeholder="Toutes les classes"
            searchPlaceholder="Chercher une classe…"
            options={[
              { value: '', label: 'Toutes les classes' },
              ...groups.map((group) => ({ value: String(group.id), label: group.name })),
            ]}
            className="w-40"
          />
        )}
      </div>
      <SearchInput
        value={search}
        onChange={onSearchChange}
        placeholder="Rechercher un devoir…"
        className="w-40 sm:w-52"
      />
    </div>
  );
}
