import { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import { Loader2, Trash2 } from 'lucide-react';
import { PrivateExercise, TeacherTag } from '@/types/models';
import { usePrivateExerciseForm } from '@/Hooks/PrivateExercise/usePrivateExerciseForm';
import { useContentSubmitBlocking } from '@/Hooks/Content/useContentSubmitBlocking';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import FormBlockingIssuesModal from '@/Components/Common/Form/FormBlockingIssuesModal';
import PrivateExerciseForm from '@/Components/Features/PrivateExercise/PrivateExerciseForm';
import ImageSection from '@/Components/Common/Form/ImageSection';
import ConfirmationModal from '@/Components/Common/UI/ConfirmationModal';

import { CatalogueClasse, CatalogueChapter, CatalogueSubchapter } from '@/types/api';

interface Props {
  exercise: PrivateExercise;
  classes: CatalogueClasse[];
  chapters: CatalogueChapter[];
  subchapters: CatalogueSubchapter[];
  tags: TeacherTag[];
}

export default function ExercicesEdit({
  exercise: initialExercise,
  classes,
  chapters,
  subchapters,
  tags: initialTags,
}: Props) {
  const [exercise, setExercise] = useState(initialExercise);
  const [allTags, setAllTags] = useState(initialTags);
  const [confirmDelete, setConfirmDelete] = useState(false);

  const {
    data,
    set,
    errors,
    processing,
    setFocusedField,
    submit,
    uploadImage,
    deleteImage,
    uploadingImage,
    imageError,
    createTag,
  } = usePrivateExerciseForm(exercise);
  const imageMap = Object.fromEntries(
    Object.entries(exercise.image_paths ?? {}).map(([k, v]) => [k, `/storage/${v}`])
  );
  const { blockingIssues, isSubmitBlockedModalOpen, closeSubmitBlockedModal, guardBeforeSubmit } =
    useContentSubmitBlocking();

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

  async function handleUpload(file: File) {
    const result = (await uploadImage(file, exercise.id)) as { name: string; path: string } | null;
    if (result) {
      setExercise((prev) => ({
        ...prev,
        image_paths: { ...(prev.image_paths ?? {}), [result.name]: result.path },
      }));
    }
  }

  async function handleDeleteImage(exerciseId: number, name: string) {
    await deleteImage(exerciseId, name);
    setExercise((prev) => {
      const paths = { ...(prev.image_paths ?? {}) };
      delete paths[name];
      return { ...prev, image_paths: paths };
    });
  }

  function handleSubmit(e: { preventDefault(): void }) {
    e.preventDefault();

    const canSubmit = guardBeforeSubmit({
      data,
      errors,
      images: imageMap,
    });

    if (!canSubmit) return;

    submit('teacher.exercices.update', { exercise: exercise.id });
  }

  function handleDelete() {
    router.delete(route('teacher.exercices.destroy', exercise.id));
  }

  return (
    <AppLayout hideFooter>
      <Head title={`Modifier — ${exercise.name}`} />

      <div className="flex h-[calc(100vh-72px)] min-h-0 flex-col">
        {/* Header */}
        <div className="flex-shrink-0 px-4 pt-4 pb-2 max-w-screen-xl mx-auto w-full">
          <PageHeader
            title="Modifier l'exercice"
            subtitle={exercise.name}
            breadcrumbs={[
              { label: 'Mon Bureau', href: route('teacher.bureau.index') },
              { label: 'Exercices', href: route('teacher.exercices.index') },
              { label: 'Modifier' },
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
              images={imageMap}
              imageSlot={
                <ImageSection
                  exercise={exercise}
                  onUpload={handleUpload}
                  onDelete={handleDeleteImage}
                  uploading={uploadingImage}
                  uploadError={imageError}
                />
              }
            />
          </div>

          {/* Action bar */}
          <div className="shrink-0 border-t border-border-color px-1 py-4">
            <div className="flex items-center justify-between">
              <button
                type="button"
                onClick={() => setConfirmDelete(true)}
                className="flex items-center gap-1.5 px-3 py-2 text-sm text-error-color hover:bg-error-color/10 rounded-xl transition-colors"
              >
                <Trash2 size={14} /> Supprimer
              </button>

              <button
                type="submit"
                disabled={processing}
                className="flex items-center gap-2 px-5 py-2 bg-teacher-color text-white text-sm font-comfortaa-bold rounded-xl hover:opacity-90 disabled:opacity-50 transition-opacity"
              >
                {processing && <Loader2 size={14} className="animate-spin" />}
                Enregistrer
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

      <ConfirmationModal
        isOpen={confirmDelete}
        onClose={() => setConfirmDelete(false)}
        onConfirm={handleDelete}
        title="Supprimer l'exercice"
        description={`"${exercise.name}" sera supprimé définitivement avec ses images.`}
        confirmText="Supprimer"
        type="danger"
      />
    </AppLayout>
  );
}
