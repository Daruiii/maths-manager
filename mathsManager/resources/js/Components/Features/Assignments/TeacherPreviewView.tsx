import { Head } from '@inertiajs/react';
import { Eye } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import AssignmentContentList from '@/Components/Features/Assignments/AssignmentContentList';
import type { AssignmentListItem } from '@/types/models';

interface Props {
  title: string;
  level?: string | null;
  instructions?: string | null;
  problems: AssignmentListItem[];
  exercises: AssignmentListItem[];
  privateExercises: AssignmentListItem[];
}

export default function TeacherPreviewView({
  title,
  level,
  instructions,
  problems,
  exercises,
  privateExercises,
}: Props) {
  return (
    <AppLayout>
      <Head title={title} />
      <div className="max-w-3xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title={title}
          breadcrumbs={[
            { label: 'Devoirs envoyés', href: route('teacher.bureau.devoirs') },
            { label: title },
          ]}
        />
        <div className="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-teacher-color/[0.06] border border-teacher-color/20 text-teacher-color text-xs font-comfortaa-bold">
          <Eye size={13} />
          Prévisualisation — vue enseignant (avec corrigés)
        </div>
        <AssignmentContentList
          problems={problems}
          exercises={exercises}
          privateExercises={privateExercises}
          variant="academic"
          title={title}
          level={level}
          instructions={instructions}
          showSolutions
        />
      </div>
    </AppLayout>
  );
}
