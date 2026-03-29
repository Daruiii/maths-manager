import { ArrowUpDown, Filter, X } from 'lucide-react';
import SearchBar from '@/Components/Common/UI/SearchBar';
import IconButton from '@/Components/Common/UI/IconButton';

interface Chip {
  key: string;
  label: string;
  onClear: () => void;
}

interface ExercisePickerHeaderProps {
  tab: 'problems' | 'exercises' | 'private';
  currentTotal: number;
  searchValue: string;
  isFiltersOpen?: boolean;
  onTabChange: (tab: 'problems' | 'exercises' | 'private') => void;
  onSearchChange: (value: string) => void;
  onSearchClear: () => void;
  onToggleFilters: () => void;
  chips: Chip[];
}

export default function ExercisePickerHeader({
  tab,
  currentTotal,
  searchValue,
  isFiltersOpen = false,
  onTabChange,
  onSearchChange,
  onSearchClear,
  onToggleFilters,
  chips,
}: ExercisePickerHeaderProps) {
  return (
    <div className="px-3 pt-2.5 pb-2 border-b border-border-color space-y-2 flex-shrink-0">
      {/* Tabs + compteur */}
      <div className="flex items-center justify-between gap-2">
        <div className="flex gap-1.5">
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
              className={`px-2.5 py-1 rounded-full text-xs font-medium border transition-colors ${
                tab === item.key
                  ? 'border-teacher-color text-teacher-color bg-teacher-color/10'
                  : 'border-border-color text-text-gray hover:text-text-color'
              }`}
            >
              {item.label}
            </button>
          ))}
        </div>
        {currentTotal > 0 && (
          <span className="text-xs text-text-gray shrink-0">({currentTotal})</span>
        )}
      </div>

      {/* Search + boutons */}
      {tab !== 'private' && (
        <div className="flex items-center gap-1.5">
          <div className="flex-1">
            <SearchBar
              placeholder={
                tab === 'problems' ? 'Rechercher un problème…' : 'Rechercher un exercice…'
              }
              value={searchValue}
              onChange={(e) => onSearchChange(e.target.value)}
              onClear={onSearchClear}
              focusRingClass="focus:border-teacher-color focus:ring-teacher-color"
            />
          </div>
          <IconButton
            icon={Filter}
            variant="bordered"
            accentColor="teacher"
            isActive={isFiltersOpen}
            onClick={onToggleFilters}
            title="Filtres"
          />
          <IconButton icon={ArrowUpDown} variant="bordered" accentColor="teacher" title="Trier" />
        </div>
      )}

      {/* Chips filtres actifs */}
      {tab !== 'private' && chips.length > 0 && (
        <div className="flex flex-wrap gap-1">
          {chips.map((chip) => (
            <button
              key={chip.key}
              type="button"
              onClick={chip.onClear}
              className="flex items-center gap-1 px-2 py-0.5 rounded-full border border-border-color text-xxs text-text-gray hover:text-text-color hover:border-teacher-color"
            >
              {chip.label}
              <X size={9} />
            </button>
          ))}
        </div>
      )}
    </div>
  );
}
