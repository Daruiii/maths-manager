import { ArrowUpDown, Filter, FolderPlus } from 'lucide-react';
import SearchBar from '@/Components/Common/UI/SearchBar';
import Button from '@/Components/Common/UI/Button';

interface Props {
  search: string;
  onSearchChange: (value: string) => void;
  onNewGroup: () => void;
}

export default function StudentsToolbar({ search, onSearchChange, onNewGroup }: Props) {
  return (
    <div className="flex flex-col sm:flex-row gap-3">
      <SearchBar
        value={search}
        onChange={(e) => onSearchChange(e.target.value)}
        onClear={() => onSearchChange('')}
        placeholder="Rechercher un élève..."
        focusRingClass="focus:border-teacher-color focus:ring-teacher-color"
        className="flex-1"
        filter={
          <button
            disabled
            title="Bientôt disponible"
            className="flex items-center gap-1.5 text-sm text-text-gray border border-border-color rounded-xl px-3 py-2 opacity-50 cursor-not-allowed"
          >
            <Filter size={16} />
          </button>
        }
        sort={
          <button
            disabled
            title="Bientôt disponible"
            className="flex items-center gap-1.5 text-sm text-text-gray border border-border-color rounded-xl px-3 py-2 opacity-50 cursor-not-allowed"
          >
            <ArrowUpDown size={16} />
          </button>
        }
      />
      <Button variant="secondary" icon={FolderPlus} iconSize={18} onClick={onNewGroup}>
        <span className="sm:inline">Nouveau groupe</span>
      </Button>
    </div>
  );
}
