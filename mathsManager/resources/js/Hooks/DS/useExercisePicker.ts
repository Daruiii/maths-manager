import { useState, useMemo } from 'react';
import { PickableItem, MultipleChapter, Subchapter } from '@/types/models';
import { ProblemSort, ExerciseSort, PrivateSort } from '@/types/ui';
import { PickerTab } from '@/Constants/ds';
import { getDifficultyLabel } from '@/Constants/exercisePicker';
import { useProblemSearch } from '@/Hooks/DS/useProblemSearch';
import { useExerciseSearch } from '@/Hooks/DS/useExerciseSearch';
import { usePrivateExerciseSearch } from '@/Hooks/DS/usePrivateExerciseSearch';
import { useExercisePickerOptions } from '@/Hooks/DS/useExercisePickerOptions';

// ─── Types ────────────────────────────────────────────────────────────────────

interface Chip {
  key: string;
  label: string;
  onClear: () => void;
}

export interface ActiveSearch {
  items: PickableItem[];
  total: number;
  searchValue: string;
  sort: ProblemSort | ExerciseSort | PrivateSort;
  loading: boolean;
  loadingMore: boolean;
  hasMore: boolean;
  error: string | null;
  hasActiveFilters: boolean;
  setSearch: (v: string) => void;
  setSort: (by: string, dir: 'asc' | 'desc') => void;
  loadMore: () => void;
  resetFilters: () => void;
}

interface Options {
  multipleChapters: MultipleChapter[];
  subchapters: Subchapter[];
}

// ─── Hook ─────────────────────────────────────────────────────────────────────

export function useExercisePicker({ multipleChapters, subchapters }: Options) {
  const [tab, setTab] = useState<PickerTab>('problems');
  const [isFiltersOpen, setIsFiltersOpen] = useState(false);

  const problemSearch = useProblemSearch();
  const exerciseSearch = useExerciseSearch();
  const privateSearch = usePrivateExerciseSearch();

  const pickerOptions = useExercisePickerOptions({
    multipleChapters,
    subchapters,
    problemClassId: problemSearch.filters.classId,
    exerciseClassId: exerciseSearch.filters.classId,
    exerciseChapterId: exerciseSearch.filters.chapterId,
  });

  const activeSearch = useMemo((): ActiveSearch => {
    if (tab === 'problems') {
      return {
        items: problemSearch.problems as PickableItem[],
        total: problemSearch.total,
        searchValue: problemSearch.filters.search,
        sort: problemSearch.sort,
        loading: problemSearch.loading,
        loadingMore: problemSearch.loadingMore,
        hasMore: problemSearch.hasMore,
        error: problemSearch.error,
        hasActiveFilters: problemSearch.hasActiveFilters,
        setSearch: problemSearch.setSearch,
        setSort: (by, dir) =>
          problemSearch.setSort({ by: by as typeof problemSearch.sort.by, dir }),
        loadMore: problemSearch.loadMore,
        resetFilters: problemSearch.resetFilters,
      };
    }
    if (tab === 'exercises') {
      return {
        items: exerciseSearch.exercises as PickableItem[],
        total: exerciseSearch.total,
        searchValue: exerciseSearch.filters.search,
        sort: exerciseSearch.sort,
        loading: exerciseSearch.loading,
        loadingMore: exerciseSearch.loadingMore,
        hasMore: exerciseSearch.hasMore,
        error: exerciseSearch.error,
        hasActiveFilters: exerciseSearch.hasActiveFilters,
        setSearch: exerciseSearch.setSearch,
        setSort: (by, dir) =>
          exerciseSearch.setSort({ by: by as typeof exerciseSearch.sort.by, dir }),
        loadMore: exerciseSearch.loadMore,
        resetFilters: exerciseSearch.resetFilters,
      };
    }
    return {
      items: privateSearch.exercises as PickableItem[],
      total: privateSearch.total,
      searchValue: privateSearch.filters.search,
      sort: privateSearch.sort,
      loading: privateSearch.loading,
      loadingMore: privateSearch.loadingMore,
      hasMore: privateSearch.hasMore,
      error: privateSearch.error,
      hasActiveFilters: privateSearch.hasActiveFilters,
      setSearch: privateSearch.setSearch,
      setSort: (by, dir) =>
        privateSearch.setSort({ by: by as typeof privateSearch.sort.by, dir }),
      loadMore: privateSearch.loadMore,
      resetFilters: privateSearch.resetFilters,
    };
  }, [tab, problemSearch, exerciseSearch, privateSearch]);

  const currentChips = useMemo((): Chip[] => {
    const { problemClassMap, chapterMap, exerciseClassMap, exerciseChapterMap, subchapterMap } =
      pickerOptions;

    if (tab === 'problems') {
      const { filters, setClassId, setChapterId, setDifficulty, setYear, setAcademy } =
        problemSearch;
      return [
        filters.classId
          ? {
              key: 'class',
              label: `Classe: ${problemClassMap.get(filters.classId) ?? filters.classId}`,
              onClear: () => { setClassId(''); setChapterId(''); },
            }
          : null,
        filters.chapterId
          ? {
              key: 'chapter',
              label: `Chapitre: ${chapterMap.get(filters.chapterId) ?? filters.chapterId}`,
              onClear: () => setChapterId(''),
            }
          : null,
        filters.difficulty
          ? {
              key: 'difficulty',
              label: `Diff.: ${getDifficultyLabel(filters.difficulty) ?? filters.difficulty}`,
              onClear: () => setDifficulty(''),
            }
          : null,
        filters.year
          ? { key: 'year', label: `Année: ${filters.year}`, onClear: () => setYear('') }
          : null,
        filters.academy
          ? { key: 'academy', label: `Académie: ${filters.academy}`, onClear: () => setAcademy('') }
          : null,
      ].filter(Boolean) as Chip[];
    }

    if (tab === 'exercises') {
      const { filters, setClassId, setChapterId, setSubchapterId, setDifficulty } = exerciseSearch;
      return [
        filters.classId
          ? {
              key: 'class',
              label: `Classe: ${exerciseClassMap.get(filters.classId) ?? filters.classId}`,
              onClear: () => setClassId(''),
            }
          : null,
        filters.chapterId
          ? {
              key: 'chapter',
              label: `Chapitre: ${exerciseChapterMap.get(filters.chapterId) ?? filters.chapterId}`,
              onClear: () => setChapterId(''),
            }
          : null,
        filters.subchapterId
          ? {
              key: 'subchapter',
              label: `Sous-chapitre: ${subchapterMap.get(filters.subchapterId) ?? filters.subchapterId}`,
              onClear: () => setSubchapterId(''),
            }
          : null,
        filters.difficulty
          ? {
              key: 'difficulty',
              label: `Diff.: ${getDifficultyLabel(filters.difficulty) ?? filters.difficulty}`,
              onClear: () => setDifficulty(''),
            }
          : null,
      ].filter(Boolean) as Chip[];
    }

    return [];
  }, [tab, problemSearch, exerciseSearch, pickerOptions]);

  return {
    tab,
    setTab,
    isFiltersOpen,
    setIsFiltersOpen,
    activeSearch,
    currentChips,
    problemSearch,
    exerciseSearch,
    pickerOptions,
  };
}
