import { useState, useMemo } from 'react';
import { PickableItem, Subchapter, TeacherTag } from '@/types/models';
import { ExerciseSort, PrivateSort } from '@/types/ui';
import { TDPickerTab } from '@/Constants/td';
import { getDifficultyLabel } from '@/Constants/exercisePicker';
import { useTDExerciseSearch } from '@/Hooks/TD/useTDExerciseSearch';
import { useTDPrivateSearch } from '@/Hooks/TD/useTDPrivateSearch';
import { useExercisePickerOptions } from '@/Hooks/Builder/useExercisePickerOptions';

interface Chip {
  key: string;
  label: string;
  onClear: () => void;
}

export interface TDActiveSearch {
  items: PickableItem[];
  total: number;
  searchValue: string;
  sort: ExerciseSort | PrivateSort;
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
  subchapters: Subchapter[];
  privateTags?: TeacherTag[];
}

export function useTDExercisePicker({ subchapters, privateTags = [] }: Options) {
  const [tab, setTab] = useState<TDPickerTab>('exercises');
  const [isFiltersOpen, setIsFiltersOpen] = useState(false);

  const exerciseSearch = useTDExerciseSearch();
  const privateSearch = useTDPrivateSearch();

  const pickerOptions = useExercisePickerOptions({
    multipleChapters: [],
    subchapters,
    problemClassId: '',
    exerciseClassId: exerciseSearch.filters.classId,
    exerciseChapterId: exerciseSearch.filters.chapterId,
  });

  const activeSearch = useMemo((): TDActiveSearch => {
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
      setSort: (by, dir) => privateSearch.setSort({ by: by as typeof privateSearch.sort.by, dir }),
      loadMore: privateSearch.loadMore,
      resetFilters: privateSearch.resetFilters,
    };
  }, [tab, exerciseSearch, privateSearch]);

  const currentChips = useMemo((): Chip[] => {
    const { exerciseClassMap, exerciseChapterMap, subchapterMap } = pickerOptions;

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
              label: `Sous-ch.: ${subchapterMap.get(filters.subchapterId) ?? filters.subchapterId}`,
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

    const { filters, setDifficulty, setTagId, setClasseId, setChapterId, setSubchapterId } =
      privateSearch;
    const tagLabel = privateTags.find((t) => String(t.id) === filters.tagId)?.name;
    return [
      filters.difficulty
        ? {
            key: 'difficulty',
            label: `Diff.: ${getDifficultyLabel(filters.difficulty) ?? filters.difficulty}`,
            onClear: () => setDifficulty(''),
          }
        : null,
      filters.classeId
        ? {
            key: 'classe',
            label: `Classe: ${exerciseClassMap.get(filters.classeId) ?? filters.classeId}`,
            onClear: () => setClasseId(''),
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
            label: `Sous-ch.: ${subchapterMap.get(filters.subchapterId) ?? filters.subchapterId}`,
            onClear: () => setSubchapterId(''),
          }
        : null,
      filters.tagId
        ? { key: 'tag', label: `Tag: ${tagLabel ?? filters.tagId}`, onClear: () => setTagId('') }
        : null,
    ].filter(Boolean) as Chip[];
  }, [tab, exerciseSearch, privateSearch, privateTags, pickerOptions]);

  return {
    tab,
    setTab,
    isFiltersOpen,
    setIsFiltersOpen,
    activeSearch,
    currentChips,
    exerciseSearch,
    privateSearch,
    pickerOptions,
  };
}
