import { ReactNode, useState } from 'react';
import { TeacherTag } from '@/types/models';
import { CatalogueClasse, CatalogueChapter, CatalogueSubchapter } from '@/types/api';
import { PrivateExerciseFormData, LatexField } from '@/types/models';
import LatexPanel from '@/Components/Common/Form/LatexPanel';
import PrivateExerciseMobileTabs, {
  PrivateExerciseMobileTab,
} from '@/Components/Features/PrivateExercise/PrivateExerciseMobileTabs';
import PrivateExerciseSettingsColumn from '@/Components/Features/PrivateExercise/PrivateExerciseSettingsColumn';
import PrivateExerciseMetaColumn from '@/Components/Features/PrivateExercise/PrivateExerciseMetaColumn';

function TypeToggle({
  value,
  onChange,
}: {
  value: string;
  onChange: (v: 'basic' | 'problem') => void;
}) {
  return (
    <div className="flex gap-2">
      {(['basic', 'problem'] as const).map((t) => (
        <button
          key={t}
          type="button"
          onClick={() => onChange(t)}
          className={`flex-1 py-1.5 text-xs rounded-lg border transition-colors font-comfortaa ${
            value === t
              ? 'bg-teacher-color border-teacher-color text-white'
              : 'border-border-color text-text-gray hover:border-teacher-color/50'
          }`}
        >
          {t === 'basic' ? 'Exercice' : 'Problème'}
        </button>
      ))}
    </div>
  );
}

interface Props {
  data: PrivateExerciseFormData;
  set: <K extends keyof PrivateExerciseFormData>(key: K, value: PrivateExerciseFormData[K]) => void;
  errors: Partial<Record<keyof PrivateExerciseFormData, string>>;
  setFocusedField: (field: LatexField) => void;
  allTags: TeacherTag[];
  onCreateTag: (name: string, color?: string) => Promise<TeacherTag | null>;
  onUpdateTag?: (tag: TeacherTag) => void;
  onDeleteTag?: (id: number) => void;
  classes: CatalogueClasse[];
  chapters: CatalogueChapter[];
  subchapters: CatalogueSubchapter[];
  images?: Record<string, string>;
  imageSlot?: ReactNode;
}

export default function PrivateExerciseForm({
  data,
  set,
  errors,
  setFocusedField,
  allTags,
  onCreateTag,
  onUpdateTag,
  onDeleteTag,
  classes,
  chapters,
  subchapters,
  images = {},
  imageSlot,
}: Props) {
  const [mobileTab, setMobileTab] = useState<PrivateExerciseMobileTab>('latex');

  return (
    <div className="flex h-full min-h-0 flex-col gap-3">
      <PrivateExerciseMobileTabs mobileTab={mobileTab} onChange={setMobileTab} />

      <div className="grid min-h-0 flex-1 grid-cols-1 gap-3 xl:grid-cols-[220px_minmax(0,1fr)_320px] xl:gap-4">
        {/* ── Col gauche : Identité + Paramètres ── */}
        <div
          className={`order-2 min-h-0 space-y-3 xl:order-1 xl:pr-1 ${
            mobileTab === 'settings' ? 'block' : 'hidden xl:block'
          }`}
        >
          <PrivateExerciseSettingsColumn
            data={data}
            errors={errors}
            set={set}
            typeToggle={<TypeToggle value={data.type} onChange={(v) => set('type', v)} />}
          />
        </div>

        {/* ── Col centrale : Éditeur LaTeX + image strip ── */}
        <div
          className={`order-1 min-h-0 xl:order-2 ${
            mobileTab === 'latex' ? 'block' : 'hidden xl:block'
          }`}
        >
          <LatexPanel
            data={data}
            set={set}
            errors={errors}
            setFocusedField={setFocusedField}
            images={images}
            imageSlot={imageSlot}
          />
        </div>

        {/* ── Col droite : Recherche + Notes ── */}
        <div
          className={`order-3 min-h-0 space-y-3 xl:pl-1 ${
            mobileTab === 'meta' ? 'block' : 'hidden xl:block'
          }`}
        >
          <PrivateExerciseMetaColumn
            data={data}
            errors={errors}
            set={set}
            allTags={allTags}
            onCreateTag={onCreateTag}
            onUpdateTag={onUpdateTag}
            onDeleteTag={onDeleteTag}
            classes={classes}
            chapters={chapters}
            subchapters={subchapters}
          />
        </div>
      </div>
    </div>
  );
}
