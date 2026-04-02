import { useRef, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import { Loader2, Upload, X, Check, Copy } from 'lucide-react';
import { TeacherTag } from '@/types/models';
import { usePrivateExerciseForm } from '@/Hooks/Bureau/usePrivateExerciseForm';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import PrivateExerciseForm from '@/Components/Features/PrivateExercise/PrivateExerciseForm';

import { CatalogueClasse, CatalogueChapter, CatalogueSubchapter } from '@/types/api';

interface Props {
  classes: CatalogueClasse[];
  chapters: CatalogueChapter[];
  subchapters: CatalogueSubchapter[];
  tags: TeacherTag[];
}

export default function ExercicesCreate({
  classes,
  chapters,
  subchapters,
  tags: initialTags,
}: Props) {
  const {
    data,
    set,
    errors,
    processing,
    setFocusedField,
    submit,
    createTag,
    pendingImageMap,
    addPendingImage,
    removePendingImage,
    pendingImages,
  } = usePrivateExerciseForm();
  const [allTags, setAllTags] = useState(initialTags);
  const [copiedName, setCopiedName] = useState<string | null>(null);
  const fileInputRef = useRef<HTMLInputElement>(null);

  async function handleCreateTag(name: string, color?: string) {
    const tag = await createTag(name, color);
    if (tag) setAllTags((prev) => [...prev, tag]);
    return tag;
  }

  function handleUpdateTag(updated: TeacherTag) {
    setAllTags((prev) => prev.map((t) => (t.id === updated.id ? updated : t)));
  }
  function handleDeleteTag(id: number) {
    setAllTags((prev) => prev.filter((t) => t.id !== id));
  }

  function handleSubmit(e: { preventDefault(): void }) {
    e.preventDefault();
    submit('teacher.exercices.store');
  }

  function copyLatex(name: string) {
    navigator.clipboard.writeText(`\\graph{${name}}{0.5}{Description}`);
    setCopiedName(name);
    setTimeout(() => setCopiedName(null), 2000);
  }

  return (
    <AppLayout>
      <Head title="Nouvel exercice" />

      <div className="max-w-6xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title="Nouvel exercice"
          breadcrumbs={[
            { label: 'Mon Bureau', href: route('teacher.bureau.index') },
            { label: 'Exercices', href: route('teacher.exercices.index') },
            { label: 'Nouveau' },
          ]}
        />

        <form onSubmit={handleSubmit} className="space-y-6">
          <PrivateExerciseForm
            data={data}
            set={set}
            errors={errors}
            setFocusedField={setFocusedField}
            allTags={allTags}
            onCreateTag={handleCreateTag}
            onUpdateTag={handleUpdateTag}
            onDeleteTag={handleDeleteTag}
            classes={classes}
            chapters={chapters}
            subchapters={subchapters}
            images={pendingImageMap}
          />

          {/* Images en attente */}
          <div className="p-4 bg-surface-color border border-border-color rounded-2xl space-y-3">
            <p className="text-xs font-comfortaa-bold text-text-color">Images</p>

            {Object.keys(pendingImages).length > 0 && (
              <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                {Object.entries(pendingImages).map(([name, { blobUrl }]) => (
                  <div
                    key={name}
                    className="relative group rounded-xl overflow-hidden border border-border-color"
                  >
                    <img src={blobUrl} alt={name} className="w-full h-24 object-cover" />

                    <div className="absolute top-1.5 right-1.5 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                      <button
                        type="button"
                        onClick={() => copyLatex(name)}
                        className="p-1 bg-black/60 text-white rounded-full hover:bg-teacher-color transition-colors"
                        title="Copier le code LaTeX"
                      >
                        {copiedName === name ? <Check size={10} /> : <Copy size={10} />}
                      </button>
                      <button
                        type="button"
                        onClick={() => removePendingImage(name)}
                        className="p-1 bg-black/60 text-white rounded-full hover:bg-error-color transition-colors"
                      >
                        <X size={10} />
                      </button>
                    </div>

                    <div className="absolute bottom-0 inset-x-0 bg-black/50 px-2 py-1">
                      <p className="text-xxs text-white/80 truncate">{name}</p>
                      <p className="text-xxs text-white/50 font-mono truncate">
                        \graph{'{'}
                        {name}
                        {'}{0.5}{…}'}
                      </p>
                    </div>
                  </div>
                ))}
              </div>
            )}

            <input
              ref={fileInputRef}
              type="file"
              accept="image/*"
              className="hidden"
              onChange={(e) => {
                const file = e.target.files?.[0];
                if (file) addPendingImage(file);
                e.target.value = '';
              }}
            />

            <button
              type="button"
              onClick={() => fileInputRef.current?.click()}
              className="flex items-center gap-2 px-3 py-2 text-xs border border-dashed border-border-color rounded-xl text-text-gray hover:border-teacher-color hover:text-teacher-color transition-colors"
            >
              <Upload size={14} /> Ajouter une image
            </button>

            <p className="text-xxs text-text-gray/70 italic">
              Les images seront uploadées à la création. Hover →{' '}
              <span className="font-mono">\graph{'{'}img-1{'}'}{'{'}0.5{'}'}{'{'}Description{'}'}</span>
            </p>
          </div>

          <div className="flex items-center justify-end gap-3 pt-2 border-t border-border-color">
            <Link
              href={route('teacher.exercices.index')}
              className="px-4 py-2 text-sm text-text-gray hover:text-text-color transition-colors"
            >
              Annuler
            </Link>
            <button
              type="submit"
              disabled={processing}
              className="flex items-center gap-2 px-5 py-2.5 bg-teacher-color text-white text-sm font-comfortaa-bold rounded-xl hover:opacity-90 disabled:opacity-50 transition-opacity"
            >
              {processing && <Loader2 size={14} className="animate-spin" />}
              Créer l'exercice
            </button>
          </div>
        </form>
      </div>
    </AppLayout>
  );
}
