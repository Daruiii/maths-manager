import { BookOpen, StickyNote, Tag } from 'lucide-react';
import { CatalogueClasse, CatalogueChapter, CatalogueSubchapter } from '@/types/api';
import { PrivateExerciseFormData, TeacherTag } from '@/types/models';
import InputLabel from '@/Components/Common/Form/InputLabel';
import TextAreaInput from '@/Components/Common/Form/TextAreaInput';
import InputError from '@/Components/Common/Form/InputError';
import ClassificationCascade from '@/Components/Features/PrivateExercise/ClassificationCascade';
import TagSelector from '@/Components/Features/PrivateExercise/TagSelector';

interface Props {
  data: PrivateExerciseFormData;
  errors: Partial<Record<keyof PrivateExerciseFormData, string>>;
  set: <K extends keyof PrivateExerciseFormData>(key: K, value: PrivateExerciseFormData[K]) => void;
  allTags: TeacherTag[];
  onCreateTag: (name: string, color?: string) => Promise<TeacherTag | null>;
  onUpdateTag?: (tag: TeacherTag) => void;
  onDeleteTag?: (id: number) => void;
  classes: CatalogueClasse[];
  chapters: CatalogueChapter[];
  subchapters: CatalogueSubchapter[];
}

export default function PrivateExerciseMetaColumn({
  data,
  errors,
  set,
  allTags,
  onCreateTag,
  onUpdateTag,
  onDeleteTag,
  classes,
  chapters,
  subchapters,
}: Props) {
  return (
    <>
      <div className="p-3 bg-surface-color border border-border-color rounded-2xl overflow-hidden">
        <InputLabel>
          <span className="flex items-center gap-1">
            <BookOpen size={11} /> Classification
          </span>
        </InputLabel>
        <p className="mb-2 text-xxs italic text-text-gray/60">
          Ces infos facilitent le filtrage et la recherche de vos exercices.
        </p>
        <ClassificationCascade
          classes={classes}
          chapters={chapters}
          subchapters={subchapters}
          classeId={data.classe_id}
          chapterId={data.chapter_id}
          subchapterId={data.subchapter_id}
          onClasseChange={(v) => set('classe_id', v)}
          onChapterChange={(v) => set('chapter_id', v)}
          onSubchapterChange={(v) => set('subchapter_id', v)}
        />
      </div>

      <div className="p-3 bg-surface-color border border-border-color rounded-2xl">
        <div className="flex items-center gap-2">
          <div className="flex shrink-0 items-center gap-1 whitespace-nowrap text-sm font-comfortaa-bold text-text-color">
            <Tag size={11} />
            <span>Tags</span>
          </div>
          <div className="min-w-0 flex-1">
            <TagSelector
              allTags={allTags}
              selectedIds={data.tag_ids}
              onChange={(ids: number[]) => set('tag_ids', ids)}
              onCreateTag={onCreateTag}
              onUpdateTag={onUpdateTag}
              onDeleteTag={onDeleteTag}
            />
          </div>
        </div>
      </div>

      <div className="p-3 bg-surface-color border border-border-color rounded-2xl">
        <InputLabel>
          <span className="flex items-center gap-1">
            <StickyNote size={11} /> Notes
          </span>
        </InputLabel>
        <TextAreaInput
          value={data.notes}
          onChange={(e) => set('notes', e.target.value)}
          placeholder="Source, remarques, contexte…"
          rows={5}
          className="w-full px-3 py-2 text-sm resize-none custom-scrollbar"
        />
        <InputError message={errors.notes} />
      </div>
    </>
  );
}
