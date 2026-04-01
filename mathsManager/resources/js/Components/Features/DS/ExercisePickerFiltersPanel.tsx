import { BookOpen, Calendar, Filter, GraduationCap, Layers, School, Tag } from 'lucide-react';
import {
  FilterInput,
  FilterSection,
  FilterSelect,
  FilterSelectOption,
} from '@/Components/Common/UI/FilterControls';
import { DIFFICULTY_OPTIONS } from '@/Constants/exercisePicker';

interface ProblemFilters {
  classId: string;
  chapterId: string;
  difficulty: string;
  year: string;
  academy: string;
}

interface ExerciseFilters {
  classId: string;
  chapterId: string;
  subchapterId: string;
  difficulty: string;
}

interface PrivateFilters {
  type: string;
  difficulty: string;
  tagId: string;
  classeId: string;
  chapterId: string;
  subchapterId: string;
}

interface TeacherTagOption {
  id: number;
  name: string;
}

interface ClassOption {
  id: number;
  name: string;
}

interface ExercisePickerFiltersPanelProps {
  tab: 'problems' | 'exercises' | 'private';
  isOpen: boolean;
  academies: string[];
  problemFilters: ProblemFilters;
  exerciseFilters: ExerciseFilters;
  privateFilters: PrivateFilters;
  privateTags: TeacherTagOption[];
  classesForProblems: ClassOption[];
  classesForExercises: ClassOption[];
  chapterOptions: FilterSelectOption[];
  exerciseChapterOptions: FilterSelectOption[];
  subchapterOptions: FilterSelectOption[];
  onProblemClassChange: (value: string) => void;
  onProblemChapterChange: (value: string) => void;
  onProblemDifficultyChange: (value: string) => void;
  onProblemYearChange: (value: string) => void;
  onProblemAcademyChange: (value: string) => void;
  onExerciseClassChange: (value: string) => void;
  onExerciseChapterChange: (value: string) => void;
  onExerciseSubchapterChange: (value: string) => void;
  onExerciseDifficultyChange: (value: string) => void;
  onPrivateTypeChange: (value: string) => void;
  onPrivateDifficultyChange: (value: string) => void;
  onPrivateTagChange: (value: string) => void;
  onPrivateClasseChange: (value: string) => void;
  onPrivateChapterChange: (value: string) => void;
  onPrivateSubchapterChange: (value: string) => void;
}

export default function ExercisePickerFiltersPanel({
  tab,
  isOpen,
  academies,
  problemFilters,
  exerciseFilters,
  privateFilters,
  privateTags,
  classesForProblems,
  classesForExercises,
  chapterOptions,
  exerciseChapterOptions,
  subchapterOptions,
  onProblemClassChange,
  onProblemChapterChange,
  onProblemDifficultyChange,
  onProblemYearChange,
  onProblemAcademyChange,
  onExerciseClassChange,
  onExerciseChapterChange,
  onExerciseSubchapterChange,
  onExerciseDifficultyChange,
  onPrivateTypeChange,
  onPrivateDifficultyChange,
  onPrivateTagChange,
  onPrivateClasseChange,
  onPrivateChapterChange,
  onPrivateSubchapterChange,
}: ExercisePickerFiltersPanelProps) {
  return (
    <div className={`px-3 pt-2 pb-3 border-b border-border-color ${isOpen ? 'block' : 'hidden'}`}>
      {tab === 'problems' && (
        <FilterSection icon={Filter}>
          <div className="grid grid-cols-2 gap-2">
            <FilterSelect
              label="Classe"
              icon={School}
              value={problemFilters.classId}
              onChange={onProblemClassChange}
              options={[
                { value: '', label: 'Toutes classes' },
                ...classesForProblems.map((classe) => ({
                  value: String(classe.id),
                  label: classe.name,
                })),
              ]}
            />
            <FilterSelect
              label="Chapitre"
              icon={BookOpen}
              value={problemFilters.chapterId}
              onChange={(value) => {
                if (value.startsWith('__group__')) return;
                onProblemChapterChange(value);
              }}
              options={chapterOptions}
            />
            <FilterSelect
              label="Difficulté"
              icon={Layers}
              value={problemFilters.difficulty}
              onChange={onProblemDifficultyChange}
              options={DIFFICULTY_OPTIONS.map((opt) => ({
                value: opt.value,
                label: opt.value ? `Diff. ${opt.label}` : 'Toute difficulté',
              }))}
            />
            <FilterInput
              label="Année"
              icon={Calendar}
              value={problemFilters.year}
              placeholder="2024"
              onChange={onProblemYearChange}
              type="text"
            />
            <FilterInput
              label="Académie"
              icon={GraduationCap}
              value={problemFilters.academy}
              placeholder="Rechercher une académie"
              onChange={onProblemAcademyChange}
              type="search"
              listId="academy-list"
              suggestions={academies}
            />
          </div>
        </FilterSection>
      )}

      {tab === 'exercises' && (
        <FilterSection icon={Filter}>
          <div className="grid grid-cols-2 gap-2">
            <FilterSelect
              label="Classe"
              icon={School}
              value={exerciseFilters.classId}
              onChange={onExerciseClassChange}
              options={[
                { value: '', label: 'Toutes classes' },
                ...classesForExercises.map((classe) => ({
                  value: String(classe.id),
                  label: classe.name,
                })),
              ]}
            />
            <FilterSelect
              label="Chapitre"
              icon={BookOpen}
              value={exerciseFilters.chapterId}
              onChange={onExerciseChapterChange}
              options={exerciseChapterOptions}
            />
            <FilterSelect
              label="Sous-chapitre"
              icon={BookOpen}
              value={exerciseFilters.subchapterId}
              onChange={(value) => {
                if (value.startsWith('__group__')) return;
                onExerciseSubchapterChange(value);
              }}
              options={subchapterOptions}
            />
            <FilterSelect
              label="Difficulté"
              icon={Layers}
              value={exerciseFilters.difficulty}
              onChange={onExerciseDifficultyChange}
              options={DIFFICULTY_OPTIONS.map((opt) => ({
                value: opt.value,
                label: opt.value ? `Diff. ${opt.label}` : 'Toute difficulté',
              }))}
            />
          </div>
        </FilterSection>
      )}

      {tab === 'private' && (
        <FilterSection icon={Filter}>
          <div className="grid grid-cols-2 gap-2">
            <FilterSelect
              label="Type"
              icon={Filter}
              value={privateFilters.type}
              onChange={onPrivateTypeChange}
              options={[
                { value: '', label: 'Tous types' },
                { value: 'basic', label: 'Exercice' },
                { value: 'problem', label: 'Problème' },
              ]}
            />
            <FilterSelect
              label="Difficulté"
              icon={Layers}
              value={privateFilters.difficulty}
              onChange={onPrivateDifficultyChange}
              options={DIFFICULTY_OPTIONS.map((opt) => ({
                value: opt.value,
                label: opt.value ? `Diff. ${opt.label}` : 'Toute difficulté',
              }))}
            />
            <FilterSelect
              label="Classe"
              icon={School}
              value={privateFilters.classeId}
              onChange={onPrivateClasseChange}
              options={[
                { value: '', label: 'Toutes classes' },
                ...classesForExercises.map((c) => ({ value: String(c.id), label: c.name })),
              ]}
            />
            <FilterSelect
              label="Chapitre"
              icon={BookOpen}
              value={privateFilters.chapterId}
              onChange={(value) => {
                if (value.startsWith('__group__')) return;
                onPrivateChapterChange(value);
              }}
              options={exerciseChapterOptions}
            />
            <FilterSelect
              label="Sous-chapitre"
              icon={BookOpen}
              value={privateFilters.subchapterId}
              onChange={(value) => {
                if (value.startsWith('__group__')) return;
                onPrivateSubchapterChange(value);
              }}
              options={subchapterOptions}
            />
            {privateTags.length > 0 && (
              <FilterSelect
                label="Tag"
                icon={Tag}
                value={privateFilters.tagId}
                onChange={onPrivateTagChange}
                options={[
                  { value: '', label: 'Tous tags' },
                  ...privateTags.map((t) => ({ value: String(t.id), label: t.name })),
                ]}
              />
            )}
          </div>
        </FilterSection>
      )}
    </div>
  );
}
