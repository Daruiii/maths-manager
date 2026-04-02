import { Tag, Timer } from 'lucide-react';
import { TeacherTag } from '@/types/models';
import { CatalogueClasse, CatalogueChapter, CatalogueSubchapter } from '@/types/api';
import { PrivateExerciseFormData, LatexField } from '@/Hooks/Bureau/usePrivateExerciseForm';
import LatexPreviewField from '@/Components/Common/Form/LatexPreviewField';
import DifficultyPicker from '@/Components/Common/Form/DifficultyPicker';
import ClassificationCascade from '@/Components/Features/PrivateExercise/ClassificationCascade';
import TagSelector from '@/Components/Features/PrivateExercise/TagSelector';

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
  imageUrls?: string[];
}

// ─── Type toggle ──────────────────────────────────────────────────────────────

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

// ─── Composant principal ──────────────────────────────────────────────────────

/**
 * Formulaire d'exercice privé — réutilisé par Create et Edit.
 * 2 colonnes sur desktop : métadonnées à gauche, LaTeX à droite.
 */
export default function ExerciseForm({
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
  imageUrls = [],
}: Props) {
  return (
    <div className="grid grid-cols-1 lg:grid-cols-[300px_1fr] gap-6">
      {/* ── Colonne gauche : métadonnées ── */}
      <div className="space-y-5">
        {/* Nom */}
        <div className="space-y-1.5">
          <label className="text-xs font-comfortaa-bold text-text-color">Nom *</label>
          <input
            type="text"
            value={data.name}
            onChange={(e) => set('name', e.target.value)}
            placeholder="Ex : Suites arithmétiques — TD1"
            className={`w-full px-3 py-2 text-sm bg-surface-color border rounded-xl text-text-color placeholder:text-text-gray/50 outline-none focus:border-teacher-color transition-colors ${
              errors.name ? 'border-error-color' : 'border-border-color'
            }`}
          />
          {errors.name && <p className="text-xxs text-error-color">{errors.name}</p>}
        </div>

        {/* Type */}
        <div className="space-y-1.5">
          <label className="text-xs font-comfortaa-bold text-text-color">Type</label>
          <TypeToggle value={data.type} onChange={(v) => set('type', v)} />
        </div>

        {/* Difficulté */}
        <div className="space-y-1.5">
          <label className="text-xs font-comfortaa-bold text-text-color">Difficulté</label>
          <DifficultyPicker value={data.difficulty} onChange={(v) => set('difficulty', v)} />
        </div>

        {/* Durée */}
        <div className="space-y-1.5">
          <label className="flex items-center gap-1 text-xs font-comfortaa-bold text-text-color">
            <Timer size={12} /> Durée (min)
          </label>
          <input
            type="number"
            value={data.time}
            onChange={(e) => set('time', e.target.value)}
            placeholder="Ex : 20"
            min={1}
            max={300}
            className="w-full px-3 py-2 text-sm bg-surface-color border border-border-color rounded-xl text-text-color placeholder:text-text-gray/50 outline-none focus:border-teacher-color transition-colors"
          />
        </div>

        {/* Notes */}
        <div className="space-y-1.5">
          <label className="text-xs font-comfortaa-bold text-text-color">Notes personnelles</label>
          <textarea
            value={data.notes}
            onChange={(e) => set('notes', e.target.value)}
            placeholder="Source, remarques, contexte pédagogique…"
            rows={3}
            className="w-full px-3 py-2 text-sm bg-surface-color border border-border-color rounded-xl text-text-color placeholder:text-text-gray/50 outline-none focus:border-teacher-color transition-colors resize-none custom-scrollbar"
          />
          {errors.notes && <p className="text-xxs text-error-color">{errors.notes}</p>}
        </div>

        {/* Classification */}
        <div className="space-y-1.5">
          <label className="text-xs font-comfortaa-bold text-text-color">Classification</label>
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

        {/* Tags */}
        <div className="space-y-1.5">
          <label className="flex items-center gap-1 text-xs font-comfortaa-bold text-text-color">
            <Tag size={12} /> Tags
          </label>
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

      {/* ── Colonne droite : LaTeX ── */}
      <div className="space-y-5">
        <LatexPreviewField
          label="Énoncé"
          value={data.latex_statement}
          onChange={(v) => set('latex_statement', v)}
          onFocus={() => setFocusedField('latex_statement')}
          placeholder="\text{Soit } f : x \mapsto x^2..."
          images={imageUrls}
          error={errors.latex_statement}
          rows={7}
        />
        <LatexPreviewField
          label="Solution"
          value={data.latex_solution}
          onChange={(v) => set('latex_solution', v)}
          onFocus={() => setFocusedField('latex_solution')}
          placeholder="Solution LaTeX…"
          images={imageUrls}
          rows={6}
        />
        <LatexPreviewField
          label="Indice"
          value={data.latex_clue}
          onChange={(v) => set('latex_clue', v)}
          onFocus={() => setFocusedField('latex_clue')}
          placeholder="Indice LaTeX…"
          rows={4}
        />
      </div>
    </div>
  );
}
