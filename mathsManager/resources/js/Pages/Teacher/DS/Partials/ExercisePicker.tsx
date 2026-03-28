import { useEffect, useRef, useState } from 'react';
import { Loader2, SearchX, Lock } from 'lucide-react';
import EmptyState from '@/Components/Common/UI/EmptyState';
import { PickableItem, MultipleChapter, DSPreviewItem, Subchapter } from '@/types/models';
import PickerCard from '@/Components/Features/DS/PickerCard';
import { useProblemSearch } from '@/Hooks/DS/useProblemSearch';
import { useExerciseSearch } from '@/Hooks/DS/useExerciseSearch';
import ExercisePickerFiltersPanel from '@/Components/Features/DS/ExercisePickerFiltersPanel';
import ExercisePickerHeader from '@/Components/Features/DS/ExercisePickerHeader';
import { getDifficultyLabel } from '@/Constants/exercisePicker';
import { useExercisePickerOptions } from '@/Hooks/DS/useExercisePickerOptions';

interface Props {
  multipleChapters: MultipleChapter[];
  subchapters: Subchapter[];
  academies: string[];
  previewItems: DSPreviewItem[];
  onToggle: (item: PickableItem) => void;
}

type PickerTab = 'problems' | 'exercises' | 'private';

export default function ExercisePicker({
  multipleChapters,
  subchapters,
  academies,
  previewItems,
  onToggle,
}: Props) {
  const [tab, setTab] = useState<PickerTab>('problems');
  const [isFiltersOpen, setIsFiltersOpen] = useState(false);
  const {
    problems,
    loading,
    loadingMore,
    hasMore,
    total,
    filters,
    hasActiveFilters,
    error,
    loadMore,
    setSearch,
    setChapterId,
    setClassId,
    setDifficulty,
    setYear,
    setAcademy,
    resetFilters,
  } = useProblemSearch();

  const {
    exercises,
    loading: exercisesLoading,
    loadingMore: exercisesLoadingMore,
    hasMore: exercisesHasMore,
    total: exercisesTotal,
    filters: exercisesFilters,
    hasActiveFilters: exercisesHasActiveFilters,
    error: exercisesError,
    loadMore: loadMoreExercises,
    setSearch: setExerciseSearch,
    setClassId: setExerciseClassId,
    setChapterId: setExerciseChapterId,
    setSubchapterId,
    setDifficulty: setExerciseDifficulty,
    resetFilters: resetExerciseFilters,
  } = useExerciseSearch();

  const selectedIds = new Set(previewItems.map((i) => `${i.item.kind}-${i.item.id}`));

  const sentinelRef = useRef<HTMLDivElement>(null);
  useEffect(() => {
    const el = sentinelRef.current;
    if (!el || typeof window === 'undefined' || !window.IntersectionObserver) return;

    const observer = new window.IntersectionObserver(
      ([entry]) => {
        if (!entry.isIntersecting) return;
        if (tab === 'problems') loadMore();
        if (tab === 'exercises') loadMoreExercises();
      },
      { threshold: 0.1 }
    );

    observer.observe(el);
    return () => observer.disconnect();
  }, [loadMore, loadMoreExercises, tab]);

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
    problemClassId: filters.classId,
    exerciseClassId: exercisesFilters.classId,
    exerciseChapterId: exercisesFilters.chapterId,
  });

  const currentTotal = tab === 'problems' ? total : tab === 'exercises' ? exercisesTotal : 0;
  const currentLoading =
    tab === 'problems' ? loading : tab === 'exercises' ? exercisesLoading : false;
  const currentHasMore =
    tab === 'problems' ? hasMore : tab === 'exercises' ? exercisesHasMore : false;
  const currentLoadingMore =
    tab === 'problems' ? loadingMore : tab === 'exercises' ? exercisesLoadingMore : false;
  const currentError = tab === 'problems' ? error : tab === 'exercises' ? exercisesError : null;
  const showResetFilters =
    tab === 'problems' ? hasActiveFilters : tab === 'exercises' ? exercisesHasActiveFilters : false;

  const problemChips = [
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

  const exerciseChips = [
    exercisesFilters.classId
      ? {
          key: 'class',
          label: `Classe: ${exerciseClassMap.get(exercisesFilters.classId) ?? exercisesFilters.classId}`,
          onClear: () => setExerciseClassId(''),
        }
      : null,
    exercisesFilters.chapterId
      ? {
          key: 'chapter',
          label: `Chapitre: ${exerciseChapterMap.get(exercisesFilters.chapterId) ?? exercisesFilters.chapterId}`,
          onClear: () => setExerciseChapterId(''),
        }
      : null,
    exercisesFilters.subchapterId
      ? {
          key: 'subchapter',
          label: `Sous-chapitre: ${subchapterMap.get(exercisesFilters.subchapterId) ?? exercisesFilters.subchapterId}`,
          onClear: () => setSubchapterId(''),
        }
      : null,
    exercisesFilters.difficulty
      ? {
          key: 'difficulty',
          label: `Diff.: ${getDifficultyLabel(exercisesFilters.difficulty) ?? exercisesFilters.difficulty}`,
          onClear: () => setExerciseDifficulty(''),
        }
      : null,
  ].filter(Boolean) as Array<{ key: string; label: string; onClear: () => void }>;

  const currentChips = tab === 'problems' ? problemChips : exerciseChips;

  return (
    <div className="flex flex-col h-full">
      <ExercisePickerHeader
        tab={tab}
        currentTotal={currentTotal}
        searchValue={tab === 'problems' ? filters.search : exercisesFilters.search}
        onTabChange={setTab}
        onSearchChange={(value) => {
          if (tab === 'problems') setSearch(value);
          if (tab === 'exercises') setExerciseSearch(value);
        }}
        onSearchClear={() => {
          if (tab === 'problems') setSearch('');
          if (tab === 'exercises') setExerciseSearch('');
        }}
        isFiltersOpen={isFiltersOpen}
        onToggleFilters={() => setIsFiltersOpen((prev) => !prev)}
        chips={tab === 'private' ? [] : currentChips}
      />

      {tab !== 'private' && (
        <ExercisePickerFiltersPanel
          tab={tab}
          isOpen={isFiltersOpen}
          academies={academies}
          problemFilters={filters}
          exerciseFilters={exercisesFilters}
          classesForProblems={classesForProblems}
          classesForExercises={classesForExercises}
          chapterOptions={chapterOptions}
          exerciseChapterOptions={exerciseChapterOptions}
          subchapterOptions={subchapterOptions}
          onProblemClassChange={(value) => {
            setClassId(value);
            setChapterId('');
          }}
          onProblemChapterChange={setChapterId}
          onProblemDifficultyChange={setDifficulty}
          onProblemYearChange={setYear}
          onProblemAcademyChange={setAcademy}
          onExerciseClassChange={setExerciseClassId}
          onExerciseChapterChange={setExerciseChapterId}
          onExerciseSubchapterChange={setSubchapterId}
          onExerciseDifficultyChange={setExerciseDifficulty}
        />
      )}

      <div className="flex-1 overflow-y-auto p-3 space-y-2">
        {currentLoading && (
          <div className="flex items-center justify-center py-12">
            <Loader2 size={24} className="animate-spin text-teacher-color" />
          </div>
        )}

        {currentError && !currentLoading && (
          <EmptyState icon={SearchX} description={currentError} accentColor="default" />
        )}

        {!currentLoading &&
          currentError === null &&
          tab !== 'private' &&
          ((tab === 'problems' && problems.length === 0) ||
            (tab === 'exercises' && exercises.length === 0)) && (
            <EmptyState
              icon={SearchX}
              description="Aucun exercice trouvé"
              accentColor="default"
              action={
                showResetFilters
                  ? {
                      label: 'Effacer les filtres',
                      onClick: tab === 'problems' ? resetFilters : resetExerciseFilters,
                    }
                  : undefined
              }
            />
          )}

        {tab === 'problems' &&
          !currentLoading &&
          problems.map((problem) => (
            <PickerCard
              key={problem.id}
              item={problem}
              isSelected={selectedIds.has(`problem-${problem.id}`)}
              onToggle={onToggle}
            />
          ))}

        {tab === 'exercises' &&
          !currentLoading &&
          exercises.map((exercise) => (
            <PickerCard
              key={exercise.id}
              item={exercise}
              isSelected={selectedIds.has(`exercise-${exercise.id}`)}
              onToggle={onToggle}
            />
          ))}

        {tab === 'private' && (
          <EmptyState icon={Lock} description="Les exercices privés arrivent bientôt." />
        )}

        {currentHasMore && !currentLoading && tab !== 'private' && (
          <div ref={sentinelRef} className="flex items-center justify-center py-4">
            {currentLoadingMore ? (
              <Loader2 size={18} className="animate-spin text-teacher-color" />
            ) : (
              <div className="h-4" />
            )}
          </div>
        )}
      </div>
    </div>
  );
}
