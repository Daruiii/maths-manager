import { useMemo } from 'react';
import { PickableItem, DSPreviewItem, Subchapter, TeacherTag } from '@/types/models';
import { useTDExercisePicker } from '@/Hooks/TD/useTDExercisePicker';
import { TD_PICKER_TABS } from '@/Constants/td';
import ExercisePickerFiltersPanel from '@/Components/Features/Builder/ExercisePickerFiltersPanel';
import ExercisePickerHeader from '@/Components/Features/Builder/ExercisePickerHeader';
import ExercisePickerList from '@/Components/Features/Builder/ExercisePickerList';

interface Props {
  subchapters: Subchapter[];
  privateTags: TeacherTag[];
  previewItems: DSPreviewItem[];
  onToggle: (item: PickableItem) => void;
}

export default function ExercisePicker({
  subchapters,
  privateTags,
  previewItems,
  onToggle,
}: Props) {
  const {
    tab,
    setTab,
    isFiltersOpen,
    setIsFiltersOpen,
    activeSearch,
    currentChips,
    exerciseSearch,
    privateSearch,
    pickerOptions,
  } = useTDExercisePicker({ subchapters, privateTags });

  const selectedIds = useMemo(
    () => new Set(previewItems.map((i) => `${i.item.kind}-${i.item.id}`)),
    [previewItems]
  );

  return (
    <div className="flex flex-col h-full overflow-hidden">
      <ExercisePickerHeader
        tab={tab}
        tabs={TD_PICKER_TABS}
        currentTotal={activeSearch.total}
        searchValue={activeSearch.searchValue}
        onTabChange={setTab as (tab: string) => void}
        onSearchChange={activeSearch.setSearch}
        onSearchClear={() => activeSearch.setSearch('')}
        isFiltersOpen={isFiltersOpen}
        onToggleFilters={() => setIsFiltersOpen((prev) => !prev)}
        sort={activeSearch.sort}
        onSortChange={activeSearch.setSort}
        chips={currentChips}
      />

      <ExercisePickerFiltersPanel
        tab={tab}
        isOpen={isFiltersOpen}
        academies={[]}
        problemFilters={{ classId: '', chapterId: '', difficulty: '', year: '', academy: '' }}
        exerciseFilters={exerciseSearch.filters}
        privateFilters={privateSearch.filters}
        showPrivateType={false}
        privateTags={privateTags}
        classesForProblems={[]}
        classesForExercises={pickerOptions.classesForExercises}
        chapterOptions={[]}
        exerciseChapterOptions={pickerOptions.exerciseChapterOptions}
        subchapterOptions={pickerOptions.subchapterOptions}
        onProblemClassChange={() => {}}
        onProblemChapterChange={() => {}}
        onProblemDifficultyChange={() => {}}
        onProblemYearChange={() => {}}
        onProblemAcademyChange={() => {}}
        onExerciseClassChange={exerciseSearch.setClassId}
        onExerciseChapterChange={exerciseSearch.setChapterId}
        onExerciseSubchapterChange={exerciseSearch.setSubchapterId}
        onExerciseDifficultyChange={exerciseSearch.setDifficulty}
        onPrivateTypeChange={() => {}}
        onPrivateDifficultyChange={privateSearch.setDifficulty}
        onPrivateTagChange={privateSearch.setTagId}
        onPrivateClasseChange={privateSearch.setClasseId}
        onPrivateChapterChange={privateSearch.setChapterId}
        onPrivateSubchapterChange={privateSearch.setSubchapterId}
      />

      <ExercisePickerList
        tab={tab}
        items={activeSearch.items}
        selectedIds={selectedIds}
        loading={activeSearch.loading}
        loadingMore={activeSearch.loadingMore}
        hasMore={activeSearch.hasMore}
        error={activeSearch.error}
        onToggle={onToggle}
        onLoadMore={activeSearch.loadMore}
        onResetFilters={activeSearch.resetFilters}
        showResetFilters={activeSearch.hasActiveFilters}
      />
    </div>
  );
}
