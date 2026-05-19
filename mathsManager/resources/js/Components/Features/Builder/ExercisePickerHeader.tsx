import { useRef, useState, useEffect } from 'react';
import { ArrowUpDown, Filter, X } from 'lucide-react';
import SearchBar from '@/Components/Common/UI/SearchBar';
import IconButton from '@/Components/Common/UI/IconButton';
import { SortOption, ProblemSort, ExerciseSort, PrivateSort } from '@/types/ui';
import {
  PICKER_TABS,
  PROBLEM_SORT_OPTIONS,
  EXERCISE_SORT_OPTIONS,
  PRIVATE_SORT_OPTIONS,
} from '@/Constants/ds';
import { getNextSort } from '@/Utils/dsSort';

// ─── Types ────────────────────────────────────────────────────────────────────

interface Chip {
  key: string;
  label: string;
  onClear: () => void;
}

interface PickerTabDef {
  key: string;
  label: string;
}

interface Props {
  tab: string;
  currentTotal: number;
  searchValue: string;
  isFiltersOpen?: boolean;
  sort: ProblemSort | ExerciseSort | PrivateSort;
  tabs?: readonly PickerTabDef[];
  onTabChange: (tab: string) => void;
  onSearchChange: (value: string) => void;
  onSearchClear: () => void;
  onToggleFilters: () => void;
  onSortChange: (by: string, dir: 'asc' | 'desc') => void;
  chips: Chip[];
}

// ─── Component ───────────────────────────────────────────────────────────────

export default function ExercisePickerHeader({
  tab,
  currentTotal,
  searchValue,
  isFiltersOpen = false,
  sort,
  tabs = PICKER_TABS,
  onTabChange,
  onSearchChange,
  onSearchClear,
  onToggleFilters,
  onSortChange,
  chips,
}: Props) {
  const [isSortOpen, setIsSortOpen] = useState(false);
  const sortRef = useRef<HTMLDivElement>(null);

  // Ferme le dropdown au clic extérieur
  useEffect(() => {
    if (!isSortOpen) return;
    const handler = (e: MouseEvent) => {
      if (sortRef.current && !sortRef.current.contains(e.target as Node)) {
        setIsSortOpen(false);
      }
    };
    document.addEventListener('mousedown', handler);
    return () => document.removeEventListener('mousedown', handler);
  }, [isSortOpen]);

  const sortOptions: SortOption[] =
    tab === 'problems'
      ? PROBLEM_SORT_OPTIONS
      : tab === 'exercises'
        ? EXERCISE_SORT_OPTIONS
        : PRIVATE_SORT_OPTIONS;

  const handleSortSelect = (option: SortOption) => {
    const next = getNextSort(option, sort);
    onSortChange(next.by, next.dir);
    setIsSortOpen(false);
  };

  return (
    <div className="px-3 pt-2.5 pb-2 border-b border-border-color space-y-2 flex-shrink-0">
      <div className="flex items-center justify-between gap-2">
        <div className="flex gap-1.5">
          {tabs.map((item) => (
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

      <div className="flex items-center gap-1.5">
        <div className="flex-1">
          <SearchBar
            placeholder={
              tab === 'problems'
                ? 'Rechercher un problème…'
                : tab === 'exercises'
                  ? 'Rechercher un exercice…'
                  : 'Rechercher dans mes privés…'
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
          size="md"
        />
        <div ref={sortRef} className="relative">
          <IconButton
            icon={ArrowUpDown}
            variant="bordered"
            accentColor="teacher"
            isActive={!!sort.by}
            onClick={() => setIsSortOpen((v) => !v)}
            title="Trier"
            size="md"
          />
          {isSortOpen && (
            <div className="absolute right-0 top-full mt-1 z-20 bg-primary-color border border-border-color rounded-xl shadow-lg py-1 min-w-[140px]">
              {sortOptions.map((opt) => {
                const isSelected = sort.by === opt.by;
                const dirLabel = isSelected
                  ? sort.dir === 'asc'
                    ? opt.ascLabel
                    : opt.descLabel
                  : undefined;
                return (
                  <button
                    key={opt.by}
                    type="button"
                    onClick={() => handleSortSelect(opt)}
                    className={`w-full flex items-center justify-between gap-3 px-3 py-1.5 text-xs text-left transition-colors ${
                      isSelected
                        ? 'text-teacher-color bg-teacher-color/5'
                        : 'text-text-color hover:bg-surface-color'
                    }`}
                  >
                    <span>
                      {opt.label}
                      {dirLabel && <span className="ml-1 opacity-70">· {dirLabel}</span>}
                    </span>
                  </button>
                );
              })}
            </div>
          )}
        </div>
      </div>

      {chips.length > 0 && (
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
