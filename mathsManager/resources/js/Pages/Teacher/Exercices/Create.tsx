import { useRef, useState } from 'react';
import { Head } from '@inertiajs/react';
import { Loader2 } from 'lucide-react';
import { TeacherTag } from '@/types/models';
import { usePrivateExerciseForm } from '@/Hooks/PrivateExercise/usePrivateExerciseForm';
import { useContentSubmitBlocking } from '@/Hooks/Content/useContentSubmitBlocking';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import FormBlockingIssuesModal from '@/Components/Common/Form/FormBlockingIssuesModal';
import PrivateExerciseForm from '@/Components/Features/PrivateExercise/PrivateExerciseForm';
import PendingImagesSection from '@/Components/Common/Form/PendingImagesSection';
import { buildGraphSnippet } from '@/Utils/latexInsertion';

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
  } = usePrivateExerciseForm();
  const { blockingIssues, isSubmitBlockedModalOpen, closeSubmitBlockedModal, guardBeforeSubmit } =
    useContentSubmitBlocking();
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

    const canSubmit = guardBeforeSubmit({
      data,
      errors,
      images: pendingImageMap,
    });

    if (!canSubmit) return;

    submit('teacher.exercices.store');
  }

  function copyLatex(name: string) {
    navigator.clipboard.writeText(buildGraphSnippet(name));
    setCopiedName(name);
    setTimeout(() => setCopiedName(null), 2000);
  }

  return (
    <AppLayout hideFooter>
      <Head title="Nouvel exercice" />

      <div className="flex h-[calc(100vh-72px)] min-h-0 flex-col">
        {/* Header */}
        <div className="flex-shrink-0 px-4 pt-4 pb-2 max-w-screen-xl mx-auto w-full">
          <PageHeader
            title="Nouvel exercice"
            breadcrumbs={[
              { label: 'Mon Bureau', href: route('teacher.bureau.index') },
              { label: 'Exercices', href: route('teacher.exercices.index') },
              { label: 'Nouveau' },
            ]}
          />
        </div>

        {/* Form */}
        <form
          onSubmit={handleSubmit}
          className="mx-auto flex w-full max-w-screen-xl min-h-0 flex-1 flex-col overflow-hidden px-4"
        >
          {/* Form body */}
          <div className="flex-1 min-h-0 overflow-y-auto pb-3">
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
              imageSlot={
                <PendingImagesSection
                  pendingImageMap={pendingImageMap}
                  fileInputRef={fileInputRef}
                  copiedName={copiedName}
                  onCopy={copyLatex}
                  onRemove={removePendingImage}
                  onFileChange={addPendingImage}
                />
              }
            />
          </div>

          {/* Action bar */}
          <div className="shrink-0 border-t border-border-color px-1 py-4">
            <div className="flex items-center justify-end">
              <button
                type="submit"
                disabled={processing}
                className="flex items-center gap-2 px-5 py-2 bg-teacher-color text-white text-sm font-comfortaa-bold rounded-xl hover:opacity-90 disabled:opacity-50 transition-opacity"
              >
                {processing && <Loader2 size={14} className="animate-spin" />}
                Créer l'exercice
              </button>
            </div>
          </div>
        </form>
      </div>

      <FormBlockingIssuesModal
        isOpen={isSubmitBlockedModalOpen}
        onClose={closeSubmitBlockedModal}
        issues={blockingIssues}
        description="Corrigez ces points avant d'enregistrer l'exercice."
      />
    </AppLayout>
  );
}
