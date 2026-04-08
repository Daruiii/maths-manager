import { useMemo } from 'react';
import {
  PickableItem,
  MultipleChapter,
  DSPreviewItem,
  Subchapter,
  TeacherTag,
} from '@/types/models';
import { DM_PICKER_TABS } from '@/Constants/dm';
import { useDMExercisePicker } from '@/Hooks/DM/useDMExercisePicker';
import ExercisePickerFiltersPanel from '@/Components/Features/DS/ExercisePickerFiltersPanel';
import ExercisePickerHeader from '@/Components/Features/DS/ExercisePickerHeader';
import ExercisePickerList from '@/Pages/Teacher/DS/Partials/ExercisePickerList';

interface Props {
  multipleChapters: MultipleChapter[];
  subchapters: Subchapter[];
  academies: string[];
  privateTags: TeacherTag[];
  previewItems: DSPreviewItem[];
  onToggle: (item: PickableItem) => void;
}

export default function DmExercisePicker({
  multipleChapters,
  subchapters,
  academies,
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
    problemSearch,
    exerciseSearch,
    privateSearch,
    pickerOptions,
  } = useDMExercisePicker({ multipleChapters, subchapters, privateTags });

  const selectedIds = useMemo(
    () => new Set(previewItems.map((i) => `${i.item.kind}-${i.item.id}`)),
    [previewItems]
  );

  return (
    <div className="flex flex-col h-full overflow-hidden">
      <ExercisePickerHeader
        tab={tab}
        tabs={DM_PICKER_TABS}
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
        academies={academies}
        problemFilters={problemSearch.filters}
        exerciseFilters={exerciseSearch.filters}
        privateFilters={privateSearch.filters}
        privateTags={privateTags}
        classesForProblems={pickerOptions.classesForProblems}
        classesForExercises={pickerOptions.classesForExercises}
        chapterOptions={pickerOptions.chapterOptions}
        exerciseChapterOptions={pickerOptions.exerciseChapterOptions}
        subchapterOptions={pickerOptions.subchapterOptions}
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
        onPrivateTypeChange={(v) => privateSearch.setType(v as 'basic' | 'problem' | '')}
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
