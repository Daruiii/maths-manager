import { useState, useCallback } from 'react';
import { BookOpen, Eye } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import { route } from 'ziggy-js';
import {
  MultipleChapter,
  StudentGroup,
  User,
  PickableItem,
  DSPreviewItem,
  Subchapter,
} from '@/types/models';
import ExercisePicker from '@/Pages/Teacher/DS/Partials/ExercisePicker';
import DSPreview from '@/Pages/Teacher/DS/Partials/DSPreview';
import AssignStep from '@/Pages/Teacher/DS/Partials/AssignStep';

interface Props {
  groups: StudentGroup[];
  students: User[];
  multipleChapters: MultipleChapter[];
  subchapters: Subchapter[];
  academies: string[];
  preselectedStudentId?: number | null;
  preselectedGroupId?: number | null;
}

type MobileTab = 'picker' | 'preview';

function makeUid(kind: string, id: number, index: number) {
  return `${kind}-${id}-${index}-${Date.now()}`;
}

export default function Create({
  groups,
  students,
  multipleChapters,
  subchapters,
  academies,
  preselectedStudentId,
  preselectedGroupId,
}: Props) {
  const [previewItems, setPreviewItems] = useState<DSPreviewItem[]>([]);
  const [mobileTab, setMobileTab] = useState<MobileTab>('picker');
  const [isAssignOpen, setIsAssignOpen] = useState(false);

  const handleToggle = useCallback((item: PickableItem) => {
    setPreviewItems((prev) => {
      const existingIndex = prev.findIndex(
        (i) => i.item.kind === item.kind && i.item.id === item.id
      );
      if (existingIndex !== -1) {
        return prev.filter((_, idx) => idx !== existingIndex);
      }
      setMobileTab('preview');
      return [...prev, { uid: makeUid(item.kind, item.id, prev.length), item }];
    });
  }, []);

  const handleRemove = useCallback((uid: string) => {
    setPreviewItems((prev) => prev.filter((i) => i.uid !== uid));
  }, []);

  const handleReorder = useCallback((items: DSPreviewItem[]) => {
    setPreviewItems(items);
  }, []);

  const handleAssign = () => setIsAssignOpen(true);

  return (
    <AppLayout title="Créer un DS" hideFooter>
      {/* Page header */}
      <div className="px-4 pt-4 pb-0 max-w-screen-xl mx-auto">
        <PageHeader
          title="Créer un DS"
          subtitle="Construisez votre DS en sélectionnant des exercices et problèmes, puis assignez-le à vos élèves ou groupes."
          breadcrumbs={[
            { label: 'Mes Élèves', href: route('teacher.students.index') },
            { label: 'Créer un DS' },
          ]}
        />
      </div>

      {/* ── Mobile tabs ── */}
      <div className="lg:hidden flex border-b border-border-color mx-4 mt-3">
        <button
          type="button"
          onClick={() => setMobileTab('picker')}
          className={`flex-1 py-2.5 text-sm font-medium flex items-center justify-center gap-2 border-b-2 transition-colors ${
            mobileTab === 'picker'
              ? 'border-teacher-color text-teacher-color'
              : 'border-transparent text-text-gray hover:text-text-color'
          }`}
        >
          <BookOpen size={15} />
          Exercices
        </button>
        <button
          type="button"
          onClick={() => setMobileTab('preview')}
          className={`flex-1 py-2.5 text-sm font-medium flex items-center justify-center gap-2 border-b-2 transition-colors ${
            mobileTab === 'preview'
              ? 'border-teacher-color text-teacher-color'
              : 'border-transparent text-text-gray hover:text-text-color'
          }`}
        >
          <Eye size={15} />
          Mon DS
          {previewItems.length > 0 && (
            <span className="ml-0.5 px-1.5 py-0.5 rounded-full bg-teacher-color text-white text-[10px] font-bold">
              {previewItems.length}
            </span>
          )}
        </button>
      </div>

      {/* ── Split layout ── */}
      <div className="max-w-screen-xl mx-auto flex h-[calc(100vh-72px-60px)] lg:h-[calc(100vh-72px-80px)]">
        {/* Left — Picker */}
        <div
          className={`w-full lg:w-[48%] border-r border-border-color flex flex-col overflow-hidden ${
            mobileTab !== 'picker' ? 'hidden lg:flex' : 'flex'
          }`}
        >
          <ExercisePicker
            multipleChapters={multipleChapters}
            subchapters={subchapters}
            academies={academies}
            previewItems={previewItems}
            onToggle={handleToggle}
          />
        </div>

        {/* Right — Preview */}
        <div
          className={`w-full lg:w-[52%] flex flex-col overflow-hidden ${
            mobileTab !== 'preview' ? 'hidden lg:flex' : 'flex'
          }`}
        >
          <DSPreview
            items={previewItems}
            onReorder={handleReorder}
            onRemove={handleRemove}
            onAssign={handleAssign}
          />
        </div>
      </div>

      {/* Assign step */}
      <AssignStep
        isOpen={isAssignOpen}
        onClose={() => setIsAssignOpen(false)}
        previewItems={previewItems}
        students={students}
        groups={groups}
        preselectedStudentId={preselectedStudentId}
        preselectedGroupId={preselectedGroupId}
      />
    </AppLayout>
  );
}
