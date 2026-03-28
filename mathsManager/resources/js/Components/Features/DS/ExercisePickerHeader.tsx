import { ArrowUpDown, Filter, X } from 'lucide-react';
import SearchBar from '@/Components/Common/UI/SearchBar';

interface Chip {
  key: string;
  label: string;
  onClear: () => void;
}

interface ExercisePickerHeaderProps {
  tab: 'problems' | 'exercises' | 'private';
  currentTotal: number;
  showResetFilters: boolean;
  searchValue: string;
  onTabChange: (tab: 'problems' | 'exercises' | 'private') => void;
  onSearchChange: (value: string) => void;
  onSearchClear: () => void;
  onResetFilters: () => void;
  onToggleFilters: () => void;
  chips: Chip[];
}

export default function ExercisePickerHeader({
  tab,
  currentTotal,
  showResetFilters,
  searchValue,
  onTabChange,
  onSearchChange,
  onSearchClear,
  onResetFilters,
  onToggleFilters,
  chips,
}: ExercisePickerHeaderProps) {
  return (
    <div className="p-4 border-b border-border-color space-y-3 flex-shrink-0">
      <div className="flex items-center justify-between">
        <h2 className="text-sm font-comfortaa-bold text-text-color">
          Exercices
          {currentTotal > 0 && (
            <span className="ml-1.5 text-xs font-normal text-text-gray">({currentTotal})</span>
          )}
        </h2>

        {showResetFilters && (
          <button
            type="button"
            onClick={onResetFilters}
            className="flex items-center gap-1 text-xs text-error-color hover:text-error-color/80"
          >
            <X size={12} /> Réinitialiser
          </button>
        )}
      </div>

      <div className="flex gap-2">
        {(
          [
            { key: 'problems', label: 'Problems' },
            { key: 'exercises', label: 'Exercices' },
            { key: 'private', label: 'Privés' },
          ] as const
        ).map((item) => (
          <button
            key={item.key}
            type="button"
            onClick={() => onTabChange(item.key)}
            className={`px-3 py-1.5 rounded-full text-xs font-medium border-2 transition-colors ${
              tab === item.key
                ? 'border-teacher-color text-teacher-color bg-teacher-color/10'
                : 'border-border-color text-text-gray hover:text-text-color'
            }`}
          >
            {item.label}
          </button>
        ))}
      </div>

      {tab !== 'private' && (
        <div className="flex items-center gap-2">
          <div className="flex-1">
            <SearchBar
              placeholder="Rechercher un exercice…"
              value={searchValue}
              onChange={(e) => onSearchChange(e.target.value)}
              onClear={onSearchClear}
              focusRingClass="focus:border-teacher-color focus:ring-teacher-color"
            />
          </div>
          <button
            type="button"
            onClick={onToggleFilters}
            title="Filtres"
            className="inline-flex items-center justify-center h-10 w-10 rounded-xl border-2 border-border-color text-text-color hover:border-teacher-color"
          >
            <Filter size={16} className="text-teacher-color" />
          </button>
          <button
            type="button"
            title="Trier"
            className="inline-flex items-center justify-center h-10 w-10 rounded-xl border-2 border-border-color text-text-color hover:border-teacher-color"
          >
            <ArrowUpDown size={16} className="text-text-gray" />
          </button>
        </div>
      )}

      {tab !== 'private' && chips.length > 0 && (
        <div className="flex flex-wrap gap-2">
          {chips.map((chip) => (
            <button
              key={chip.key}
              type="button"
              onClick={chip.onClear}
              className="flex items-center gap-1 px-2 py-1 rounded-full border border-border-color text-[11px] text-text-gray hover:text-text-color hover:border-teacher-color"
            >
              {chip.label}
              <X size={11} />
            </button>
          ))}
        </div>
      )}
    </div>
  );
}
