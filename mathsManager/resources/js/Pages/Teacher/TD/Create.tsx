import { useCallback, useState } from 'react';
import { BookOpen, Eye, ListOrdered } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import FlashToast from '@/Components/Common/UI/FlashToast';
import { route } from 'ziggy-js';
import { StudentGroup, User, PickableItem, Subchapter, TeacherTag } from '@/types/models';
import ExercisePicker from '@/Pages/Teacher/TD/Partials/ExercisePicker';
import TDPreview from '@/Pages/Teacher/TD/Partials/TDPreview';
import TDContent from '@/Pages/Teacher/TD/Partials/TDContent';
import AssignStep from '@/Pages/Teacher/TD/Partials/AssignStep';
import TDBuilderActions from '@/Pages/Teacher/TD/Partials/TDBuilderActions';
import { useTDBuilderDraft, makeItemUid } from '@/Hooks/TD/useTDBuilderDraft';

interface Props {
  groups: StudentGroup[];
  students: User[];
  subchapters: Subchapter[];
  privateTags: TeacherTag[];
  preselectedStudentId?: number | null;
  preselectedGroupId?: number | null;
}

type MobileTab = 'picker' | 'preview' | 'sommaire';

export default function Create({
  groups,
  students,
  subchapters,
  privateTags,
  preselectedStudentId,
  preselectedGroupId,
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
    resetAll,
  } = useTDBuilderDraft();

  const [mobileTab, setMobileTab] = useState<MobileTab>('picker');
  const [isAssignOpen, setIsAssignOpen] = useState(false);

  const handleToggle = useCallback(
    (item: PickableItem) => {
      setPreviewItems((prev) => {
        const existingIndex = prev.findIndex(
          (i) => i.item.kind === item.kind && i.item.id === item.id
        );
        if (existingIndex !== -1) {
          return prev.filter((_, idx) => idx !== existingIndex);
        }
        setMobileTab('preview');
        return [...prev, { uid: makeItemUid(item.kind, item.id, prev.length), item }];
      });
    },
    [setPreviewItems]
  );

  const handleRemove = useCallback(
    (uid: string) => setPreviewItems((prev) => prev.filter((i) => i.uid !== uid)),
    [setPreviewItems]
  );

  const handleReorder = useCallback(
    (items: typeof previewItems) => setPreviewItems(items),
    [setPreviewItems]
  );

  return (
    <AppLayout title="Créer un TD" hideFooter>
      <FlashToast
        message={
          hadDraftOnMount ? 'Brouillon restauré — vos exercices ont été récupérés.' : undefined
        }
        type="info"
      />

      <div className="flex flex-col h-[calc(100vh-72px)]">
        <div className="flex-shrink-0 px-4 pt-4 pb-2 max-w-screen-xl mx-auto w-full">
          <PageHeader
            title="Créer un TD"
            subtitle="Sélectionnez des exercices pour construire votre fiche d'exercices, puis assignez-la à vos élèves ou groupes."
            breadcrumbs={[
              { label: 'Mes Élèves', href: route('teacher.students.index') },
              { label: 'Créer un TD' },
            ]}
            action={<TDBuilderActions itemCount={previewItems.length} onReset={resetAll} />}
          />
        </div>

        {/* Mobile tabs */}
        <div className="lg:hidden flex-shrink-0 flex border-b border-border-color mx-4">
          {(
            [
              { id: 'picker', label: 'Exercices', Icon: BookOpen },
              { id: 'preview', label: 'Aperçu', Icon: Eye },
              { id: 'sommaire', label: 'Sommaire', Icon: ListOrdered },
            ] as const
          ).map(({ id, label, Icon }) => (
            <button
              key={id}
              type="button"
              onClick={() => setMobileTab(id)}
              className={`flex-1 py-2.5 text-sm font-medium flex items-center justify-center gap-1.5 border-b-2 transition-colors ${
                mobileTab === id
                  ? 'border-teacher-color text-teacher-color'
                  : 'border-transparent text-text-gray hover:text-text-color'
              }`}
            >
              <Icon size={14} />
              {label}
              {id !== 'picker' && previewItems.length > 0 && (
                <span className="px-1.5 py-0.5 rounded-full bg-teacher-color text-white text-xxs font-bold">
                  {previewItems.length}
                </span>
              )}
            </button>
          ))}
        </div>

        <div className="flex-1 max-w-screen-xl mx-auto w-full flex overflow-hidden">
          {/* Left — Picker */}
          <div
            className={`w-full lg:w-[30%] border-r border-border-color flex flex-col overflow-hidden ${
              mobileTab !== 'picker' ? 'hidden lg:flex' : 'flex'
            }`}
          >
            <ExercisePicker
              subchapters={subchapters}
              privateTags={privateTags}
              previewItems={previewItems}
              onToggle={handleToggle}
            />
          </div>

          {/* Center — KaTeX preview */}
          <div
            className={`w-full lg:w-[54%] border-r border-border-color flex flex-col overflow-hidden ${
              mobileTab !== 'preview' ? 'hidden lg:flex' : 'flex'
            }`}
          >
            <TDContent
              items={previewItems}
              tdTitle={tdTitle}
              tdLevel={tdLevel}
              tdInstructions={tdInstructions}
              onTitleChange={setTdTitle}
              onLevelChange={setTdLevel}
              onInstructionsChange={setTdInstructions}
            />
          </div>

          {/* Right — Sommaire DnD */}
          <div
            className={`w-full lg:w-[16%] flex-col overflow-hidden ${
              mobileTab !== 'sommaire' ? 'hidden lg:flex' : 'flex'
            }`}
          >
            <TDPreview
              items={previewItems}
              onReorder={handleReorder}
              onRemove={handleRemove}
              onAssign={() => setIsAssignOpen(true)}
            />
          </div>
        </div>
      </div>

      <AssignStep
        isOpen={isAssignOpen}
        onClose={() => setIsAssignOpen(false)}
        onSuccess={resetAll}
        previewItems={previewItems}
        students={students}
        groups={groups}
        preselectedStudentId={preselectedStudentId}
        preselectedGroupId={preselectedGroupId}
        tdTitle={tdTitle}
        tdLevel={tdLevel}
        tdInstructions={tdInstructions}
      />
    </AppLayout>
  );
}
