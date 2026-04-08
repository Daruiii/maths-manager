import { ArrowUpDown, Filter, FolderPlus } from 'lucide-react';
import SearchBar from '@/Components/Common/UI/SearchBar';
import Button from '@/Components/Common/UI/Button';

interface Props {
  search: string;
  onSearchChange: (value: string) => void;
  onNewGroup?: () => void;
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
            className="flex items-center justify-center h-10 w-10 text-sm text-text-gray border border-border-color rounded-xl opacity-50 cursor-not-allowed"
          >
            <Filter size={16} />
          </button>
        }
        sort={
          <button
            disabled
            title="Bientôt disponible"
            className="flex items-center justify-center h-10 w-10 text-sm text-text-gray border border-border-color rounded-xl opacity-50 cursor-not-allowed"
          >
            <ArrowUpDown size={16} />
          </button>
        }
      />
      {onNewGroup && (
        <Button
          variant="secondary"
          icon={FolderPlus}
          iconSize={18}
          onClick={onNewGroup}
          className="h-10 px-4 py-0 shrink-0"
        >
          <span className="sm:inline font-medium">Nouveau groupe</span>
        </Button>
      )}
    </div>
  );
}
