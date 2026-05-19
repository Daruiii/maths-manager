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
import ExercisePicker from '@/Pages/Teacher/DS/Partials/ExercisePicker';
import PreviewPanel from '@/Components/Features/Builder/PreviewPanel';
import BuilderContent from '@/Components/Features/Builder/BuilderContent';
import AssignStep from '@/Components/Features/Builder/AssignStep';
import SaveTemplateModal from '@/Components/Features/Builder/SaveTemplateModal';
import BuilderPageLayout from '@/Components/Features/Builder/BuilderPageLayout';
import { DS_DEFAULT_TITLE, DS_DEFAULT_LEVEL, DS_DEFAULT_INSTRUCTIONS } from '@/Constants/ds';
import { useDSBuilderDraft } from '@/Hooks/DS/useDSBuilderDraft';
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
    dsTitle,
    setDsTitle,
    dsLevel,
    setDsLevel,
    dsInstructions,
    setDsInstructions,
    hadDraftOnMount,
    hadTemplateLoad,
    resetAll,
  } = useDSBuilderDraft(initialTemplate ?? undefined);

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
    <AppLayout title="Créer un DS" hideFooter>
      <FlashToast message={initMsg} type="info" onClose={() => setInitMsg(undefined)} noFlash />

      <BuilderPageLayout
        title="Créer un DS"
        subtitle="Profitez de la base MathsManager pour construire votre DS en sélectionnant des exercices et problèmes, puis assignez-le à vos élèves ou groupes."
        breadcrumbLabel="Créer un DS"
        entityLabel="DS"
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
            entityLabel="DS"
            includeProblems
            showTime
            title={dsTitle}
            level={dsLevel}
            instructions={dsInstructions}
            defaultTitle={DS_DEFAULT_TITLE}
            defaultLevel={DS_DEFAULT_LEVEL}
            defaultInstructions={DS_DEFAULT_INSTRUCTIONS}
            onTitleChange={setDsTitle}
            onLevelChange={setDsLevel}
            onInstructionsChange={setDsInstructions}
          />
        }
        previewSlot={
          <PreviewPanel
            items={previewItems}
            onReorder={handleReorder}
            onRemove={handleRemove}
            onAssign={() => setIsAssignOpen(true)}
            onSave={() => setIsSaveOpen(true)}
            entityLabel="DS"
            showTime
          />
        }
      />

      <SaveTemplateModal
        isOpen={isSaveOpen}
        onClose={() => setIsSaveOpen(false)}
        type="ds"
        payload={{
          items: previewItems,
          title: dsTitle,
          level: dsLevel,
          instructions: dsInstructions,
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
        assignRoute="teacher.ds.assign"
        title="Assigner le DS"
        entityLabel="DS"
        includeProblems
        customTitle={dsTitle}
        customLevel={dsLevel}
        customInstructions={dsInstructions}
      />
    </AppLayout>
  );
}
