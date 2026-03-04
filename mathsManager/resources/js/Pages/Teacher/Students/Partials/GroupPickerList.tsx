import { useMemo, useState } from 'react';
import { StudentGroup } from '@/types/models';
import { Folder, FolderMinus, Search } from 'lucide-react';

interface Props {
  groups: StudentGroup[];
  selectedGroupId: number | null;
  onSelect: (id: number | null) => void;
}

const itemBase =
  'w-full flex items-center gap-3 px-4 py-3 rounded-xl border transition-colors text-left';
const itemActive = 'border-teacher-color bg-teacher-color/10 text-teacher-color';
const itemIdle =
  'border-border-color bg-secondary-color text-text-gray hover:border-teacher-color/50 hover:text-text-color';

export default function GroupPickerList({ groups, selectedGroupId, onSelect }: Props) {
  const [search, setSearch] = useState('');

  const filtered = useMemo(() => {
    const q = search.toLowerCase().trim();
    return q ? groups.filter((g) => g.name.toLowerCase().includes(q)) : groups;
  }, [groups, search]);

  return (
    <>
      {groups.length > 5 && (
        <div className="relative">
          <Search
            size={14}
            className="absolute left-3 top-1/2 -translate-y-1/2 text-text-gray pointer-events-none"
          />
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Rechercher un groupe..."
            className="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-border-color bg-secondary-color text-text-color placeholder:text-text-gray focus:outline-none focus:border-teacher-color focus:ring-1 focus:ring-teacher-color/30"
          />
        </div>
      )}

      <div className="space-y-2 max-h-60 overflow-y-auto custom-scrollbar pr-1">
        <button
          onClick={() => onSelect(null)}
          className={`${itemBase} ${selectedGroupId === null ? itemActive : itemIdle}`}
        >
          <FolderMinus size={16} className="flex-shrink-0" />
          <span className="text-sm font-medium">Sans groupe</span>
        </button>

        {filtered.length === 0 && search && (
          <p className="text-xs text-text-gray text-center py-3">
            Aucun groupe trouvé pour «&nbsp;{search}&nbsp;»
          </p>
        )}

        {filtered.length === 0 && !search && groups.length === 0 && (
          <p className="text-xs text-text-gray text-center py-3">
            Aucun groupe créé. Créez-en un depuis la toolbar.
          </p>
        )}

        {filtered.map((group) => (
          <button
            key={group.id}
            onClick={() => onSelect(group.id)}
            className={`${itemBase} ${selectedGroupId === group.id ? itemActive : itemIdle}`}
          >
            <Folder size={16} className="flex-shrink-0" />
            <span className="text-sm font-medium">{group.name}</span>
            {group.students_count !== undefined && (
              <span className="ml-auto text-xs opacity-60 flex-shrink-0">
                {group.students_count} élève{group.students_count > 1 ? 's' : ''}
              </span>
            )}
          </button>
        ))}
      </div>
    </>
  );
}
