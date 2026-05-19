import { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import FlashToast from '@/Components/Common/UI/FlashToast';
import {
  MultipleChapter,
  StudentGroup,
  User,
  Subchapter,
  TeacherTag,
  LoadedTemplate,
} from '@/types/models';
import ExercisePicker from '@/Pages/Teacher/DM/Partials/ExercisePicker';
import PreviewPanel from '@/Components/Features/Builder/PreviewPanel';
import BuilderContent from '@/Components/Features/Builder/BuilderContent';
import AssignStep from '@/Components/Features/Builder/AssignStep';
import SaveTemplateModal from '@/Components/Features/Builder/SaveTemplateModal';
import BuilderPageLayout from '@/Components/Features/Builder/BuilderPageLayout';
import { DM_DEFAULT_TITLE, DM_DEFAULT_LEVEL, DM_DEFAULT_INSTRUCTIONS } from '@/Constants/dm';
import { useDMBuilderDraft } from '@/Hooks/DM/useDMBuilderDraft';
import { useBuilderHandlers } from '@/Hooks/useBuilderHandlers';

interface Props {
  groups: StudentGroup[];
  students: User[];
  multipleChapters: MultipleChapter[];
  subchapters: Subchapter[];
  academies: string[];
  privateTags: TeacherTag[];
  preselectedStudentId?: number | null;
  preselectedGroupId?: number | null;
  initialTemplate?: LoadedTemplate | null;
}

export default function Create({
  groups,
  students,
  multipleChapters,
  subchapters,
  academies,
  privateTags,
  preselectedStudentId,
  preselectedGroupId,
  initialTemplate,
}: Props) {
  const {
    previewItems,
    setPreviewItems,
    dmTitle,
    setDmTitle,
    dmLevel,
    setDmLevel,
    dmInstructions,
    setDmInstructions,
    hadDraftOnMount,
    hadTemplateLoad,
    resetAll,
  } = useDMBuilderDraft(initialTemplate ?? undefined);

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
    <AppLayout title="Créer un DM" hideFooter>
      <FlashToast message={initMsg} type="info" onClose={() => setInitMsg(undefined)} noFlash />

      <BuilderPageLayout
        title="Créer un DM"
        subtitle="Créez un devoir maison avec des exercices et problèmes, puis assignez-le à vos élèves ou groupes."
        breadcrumbLabel="Créer un DM"
        entityLabel="DM"
        itemCount={previewItems.length}
        onReset={resetAll}
        mobileTab={mobileTab}
        onTabChange={setMobileTab}
        pickerSlot={
          <ExercisePicker
            multipleChapters={multipleChapters}
            subchapters={subchapters}
            academies={academies}
            privateTags={privateTags}
            previewItems={previewItems}
            onToggle={handleToggle}
          />
        }
        contentSlot={
          <BuilderContent
            items={previewItems}
            entityLabel="DM"
            includeProblems
            title={dmTitle}
            level={dmLevel}
            instructions={dmInstructions}
            defaultTitle={DM_DEFAULT_TITLE}
            defaultLevel={DM_DEFAULT_LEVEL}
            defaultInstructions={DM_DEFAULT_INSTRUCTIONS}
            onTitleChange={setDmTitle}
            onLevelChange={setDmLevel}
            onInstructionsChange={setDmInstructions}
          />
        }
        previewSlot={
          <PreviewPanel
            items={previewItems}
            onReorder={handleReorder}
            onRemove={handleRemove}
            onAssign={() => setIsAssignOpen(true)}
            onSave={() => setIsSaveOpen(true)}
            entityLabel="DM"
          />
        }
      />

      <SaveTemplateModal
        isOpen={isSaveOpen}
        onClose={() => setIsSaveOpen(false)}
        type="dm"
        payload={{
          items: previewItems,
          title: dmTitle,
          level: dmLevel,
          instructions: dmInstructions,
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
        assignRoute="teacher.dm.assign"
        title="Assigner le DM"
        entityLabel="DM"
        includeProblems
        customTitle={dmTitle}
        customLevel={dmLevel}
        customInstructions={dmInstructions}
      />
    </AppLayout>
  );
}
