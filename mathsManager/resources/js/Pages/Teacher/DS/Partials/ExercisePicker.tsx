import { useState, useMemo } from 'react';
import { PickableItem, MultipleChapter, DSPreviewItem, Subchapter } from '@/types/models';
import { useProblemSearch } from '@/Hooks/DS/useProblemSearch';
import { useExerciseSearch } from '@/Hooks/DS/useExerciseSearch';
import { usePrivateExerciseSearch } from '@/Hooks/DS/usePrivateExerciseSearch';
import ExercisePickerFiltersPanel from '@/Components/Features/DS/ExercisePickerFiltersPanel';
import ExercisePickerHeader from '@/Components/Features/DS/ExercisePickerHeader';
import { getDifficultyLabel } from '@/Constants/exercisePicker';
import { useExercisePickerOptions } from '@/Hooks/DS/useExercisePickerOptions';
import { PickerTab } from '@/Constants/ds';
import ExercisePickerList from './ExercisePickerList';

interface Props {
  multipleChapters: MultipleChapter[];
  subchapters: Subchapter[];
  academies: string[];
  previewItems: DSPreviewItem[];
  onToggle: (item: PickableItem) => void;
}

export default function ExercisePicker({
  multipleChapters,
  subchapters,
  academies,
  previewItems,
  onToggle,
}: Props) {
  const [tab, setTab] = useState<PickerTab>('problems');
  const [isFiltersOpen, setIsFiltersOpen] = useState(false);

  const problemSearch = useProblemSearch();
  const exerciseSearch = useExerciseSearch();
  const privateSearch = usePrivateExerciseSearch();

  const selectedIds = useMemo(
    () => new Set(previewItems.map((i) => `${i.item.kind}-${i.item.id}`)),
    [previewItems]
  );

  const {
    classesForProblems,
    classesForExercises,
    problemClassMap,
    exerciseClassMap,
    exerciseChapterMap,
    exerciseChapterOptions,
    chapterMap,
    subchapterMap,
    chapterOptions,
    subchapterOptions,
  } = useExercisePickerOptions({
    multipleChapters,
    subchapters,
    problemClassId: problemSearch.filters.classId,
    exerciseClassId: exerciseSearch.filters.classId,
    exerciseChapterId: exerciseSearch.filters.chapterId,
  });

  const currentChips = useMemo(() => {
    if (tab === 'problems') {
      const { filters, setClassId, setChapterId, setDifficulty, setYear, setAcademy } =
        problemSearch;
      return [
        filters.classId
          ? {
              key: 'class',
              label: `Classe: ${problemClassMap.get(filters.classId) ?? filters.classId}`,
              onClear: () => {
                setClassId('');
                setChapterId('');
              },
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
          ? {
              key: 'year',
              label: `Année: ${filters.year}`,
              onClear: () => setYear(''),
            }
          : null,
        filters.academy
          ? {
              key: 'academy',
              label: `Académie: ${filters.academy}`,
              onClear: () => setAcademy(''),
            }
          : null,
      ].filter(Boolean) as Array<{ key: string; label: string; onClear: () => void }>;
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
      ].filter(Boolean) as Array<{ key: string; label: string; onClear: () => void }>;
    }

    return [];
  }, [tab, problemSearch, exerciseSearch, problemClassMap, exerciseClassMap]);

  return (
    <div className="flex flex-col h-full overflow-hidden">
      <ExercisePickerHeader
        tab={tab}
        currentTotal={
          tab === 'problems'
            ? problemSearch.total
            : tab === 'exercises'
              ? exerciseSearch.total
              : privateSearch.total
        }
        searchValue={
          tab === 'problems'
            ? problemSearch.filters.search
            : tab === 'exercises'
              ? exerciseSearch.filters.search
              : privateSearch.filters.search
        }
        onTabChange={setTab}
        onSearchChange={(value) => {
          if (tab === 'problems') problemSearch.setSearch(value);
          if (tab === 'exercises') exerciseSearch.setSearch(value);
          if (tab === 'private') privateSearch.setSearch(value);
        }}
        onSearchClear={() => {
          if (tab === 'problems') problemSearch.setSearch('');
          if (tab === 'exercises') exerciseSearch.setSearch('');
          if (tab === 'private') privateSearch.setSearch('');
        }}
        isFiltersOpen={isFiltersOpen}
        onToggleFilters={() => setIsFiltersOpen((prev) => !prev)}
        sort={
          tab === 'problems'
            ? problemSearch.sort
            : tab === 'exercises'
              ? exerciseSearch.sort
              : privateSearch.sort
        }
        onSortChange={(by, dir) => {
          if (tab === 'problems')
            problemSearch.setSort({ by: by as typeof problemSearch.sort.by, dir });
          if (tab === 'exercises')
            exerciseSearch.setSort({ by: by as typeof exerciseSearch.sort.by, dir });
          if (tab === 'private')
            privateSearch.setSort({ by: by as typeof privateSearch.sort.by, dir });
        }}
        chips={currentChips}
      />

      {tab !== 'private' && (
        <ExercisePickerFiltersPanel
          tab={tab}
          isOpen={isFiltersOpen}
          academies={academies}
          problemFilters={problemSearch.filters}
          exerciseFilters={exerciseSearch.filters}
          classesForProblems={classesForProblems}
          classesForExercises={classesForExercises}
          chapterOptions={chapterOptions}
          exerciseChapterOptions={exerciseChapterOptions}
          subchapterOptions={subchapterOptions}
          onProblemClassChange={(value) => {
            problemSearch.setClassId(value);
            problemSearch.setChapterId('');
          }}
          onProblemChapterChange={problemSearch.setChapterId}
          onProblemDifficultyChange={problemSearch.setDifficulty}
          onProblemYearChange={problemSearch.setYear}
          onProblemAcademyChange={problemSearch.setAcademy}
          onExerciseClassChange={exerciseSearch.setClassId}
          onExerciseChapterChange={exerciseSearch.setChapterId}
          onExerciseSubchapterChange={exerciseSearch.setSubchapterId}
          onExerciseDifficultyChange={exerciseSearch.setDifficulty}
        />
      )}

      <ExercisePickerList
        tab={tab}
        items={
          tab === 'problems'
            ? problemSearch.problems
            : tab === 'exercises'
              ? exerciseSearch.exercises
              : privateSearch.exercises
        }
        selectedIds={selectedIds}
        loading={
          tab === 'problems'
            ? problemSearch.loading
            : tab === 'exercises'
              ? exerciseSearch.loading
              : privateSearch.loading
        }
        loadingMore={
          tab === 'problems'
            ? problemSearch.loadingMore
            : tab === 'exercises'
              ? exerciseSearch.loadingMore
              : privateSearch.loadingMore
        }
        hasMore={
          tab === 'problems'
            ? problemSearch.hasMore
            : tab === 'exercises'
              ? exerciseSearch.hasMore
              : privateSearch.hasMore
        }
        error={
          tab === 'problems'
            ? problemSearch.error
            : tab === 'exercises'
              ? exerciseSearch.error
              : privateSearch.error
        }
        onToggle={onToggle}
        onLoadMore={
          tab === 'problems'
            ? problemSearch.loadMore
            : tab === 'exercises'
              ? exerciseSearch.loadMore
              : privateSearch.loadMore
        }
        onResetFilters={
          tab === 'problems'
            ? problemSearch.resetFilters
            : tab === 'exercises'
              ? exerciseSearch.resetFilters
              : privateSearch.resetFilters
        }
        showResetFilters={
          tab === 'problems'
            ? problemSearch.hasActiveFilters
            : tab === 'exercises'
              ? exerciseSearch.hasActiveFilters
              : privateSearch.hasActiveFilters
        }
      />
    </div>
  );
}
