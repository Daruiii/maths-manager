import { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import FlashToast from '@/Components/Common/UI/FlashToast';
import { StudentGroup, User, Subchapter, TeacherTag, LoadedTemplate } from '@/types/models';
import ExercisePicker from '@/Pages/Teacher/TD/Partials/ExercisePicker';
import PreviewPanel from '@/Components/Features/Builder/PreviewPanel';
import BuilderContent from '@/Components/Features/Builder/BuilderContent';
import AssignStep from '@/Components/Features/Builder/AssignStep';
import SaveTemplateModal from '@/Components/Features/Builder/SaveTemplateModal';
import BuilderPageLayout from '@/Components/Features/Builder/BuilderPageLayout';
import { TD_DEFAULT_TITLE, TD_DEFAULT_LEVEL, TD_DEFAULT_INSTRUCTIONS } from '@/Constants/td';
import { useTDBuilderDraft } from '@/Hooks/TD/useTDBuilderDraft';
import { useBuilderHandlers } from '@/Hooks/useBuilderHandlers';

interface Props {
  groups: StudentGroup[];
  students: User[];
  subchapters: Subchapter[];
  privateTags: TeacherTag[];
  preselectedStudentId?: number | null;
  preselectedGroupId?: number | null;
  initialTemplate?: LoadedTemplate | null;
}

export default function Create({
  groups,
  students,
  subchapters,
  privateTags,
  preselectedStudentId,
  preselectedGroupId,
  initialTemplate,
}: Props) {
  const {
    previewItems,
    setPreviewItems,
    tdTitle,
    setTdTitle,
    tdLevel,
    setTdLevel,
    tdInstructions,
    setTdInstructions,
    hadDraftOnMount,
    hadTemplateLoad,
    resetAll,
  } = useTDBuilderDraft(initialTemplate ?? undefined);

  const [initMsg, setInitMsg] = useState<string | undefined>(() => {
    if (hadTemplateLoad) return 'Modèle chargé — vous pouvez modifier le contenu.';
    if (hadDraftOnMount) return 'Brouillon restauré — vos exercices ont été récupérés.';
    return undefined;
  });

  const [isAssignOpen, setIsAssignOpen] = useState(false);
  const [isSaveOpen, setIsSaveOpen] = useState(false);

  const { mobileTab, setMobileTab, handleToggle, handleRemove, handleReorder } =
    useBuilderHandlers(setPreviewItems);

  return (
    <AppLayout title="Créer un TD" hideFooter>
      <FlashToast message={initMsg} type="info" onClose={() => setInitMsg(undefined)} noFlash />

      <BuilderPageLayout
        title="Créer un TD"
        subtitle="Sélectionnez des exercices pour construire votre fiche d'exercices, puis assignez-la à vos élèves ou groupes."
        breadcrumbLabel="Créer un TD"
        entityLabel="TD"
        itemCount={previewItems.length}
        onReset={resetAll}
        mobileTab={mobileTab}
        onTabChange={setMobileTab}
        pickerSlot={
          <ExercisePicker
            subchapters={subchapters}
            privateTags={privateTags}
            previewItems={previewItems}
            onToggle={handleToggle}
          />
        }
        contentSlot={
          <BuilderContent
            items={previewItems}
            entityLabel="TD"
            title={tdTitle}
            level={tdLevel}
            instructions={tdInstructions}
            defaultTitle={TD_DEFAULT_TITLE}
            defaultLevel={TD_DEFAULT_LEVEL}
            defaultInstructions={TD_DEFAULT_INSTRUCTIONS}
            onTitleChange={setTdTitle}
            onLevelChange={setTdLevel}
            onInstructionsChange={setTdInstructions}
          />
        }
        previewSlot={
          <PreviewPanel
            items={previewItems}
            onReorder={handleReorder}
            onRemove={handleRemove}
            onAssign={() => setIsAssignOpen(true)}
            onSave={() => setIsSaveOpen(true)}
            entityLabel="TD"
          />
        }
      />

      <SaveTemplateModal
        isOpen={isSaveOpen}
        onClose={() => setIsSaveOpen(false)}
        type="td"
        payload={{
          items: previewItems,
          title: tdTitle,
          level: tdLevel,
          instructions: tdInstructions,
        }}
        groups={groups}
        editingTemplate={initialTemplate ?? undefined}
      />

      <AssignStep
        isOpen={isAssignOpen}
        onClose={() => setIsAssignOpen(false)}
        onSuccess={resetAll}
        previewItems={previewItems}
        students={students}
        groups={groups}
        preselectedStudentId={preselectedStudentId}
        preselectedGroupId={preselectedGroupId}
        assignRoute="teacher.td.assign"
        title="Assigner le TD"
        entityLabel="TD"
        customTitle={tdTitle}
        customLevel={tdLevel}
        customInstructions={tdInstructions}
      />
    </AppLayout>
  );
}
