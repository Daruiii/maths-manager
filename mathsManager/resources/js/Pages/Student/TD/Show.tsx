import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { CheckCircle, Clock, BookOpenCheck, Send } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Button from '@/Components/Common/UI/Button';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import AssignmentContentList from '@/Components/Features/Assignments/AssignmentContentList';
import type { Td } from '@/types/models';

export default function TdShow({ td }: { td: Td }) {
  const [requesting, setRequesting] = useState(false);

  const title = td.custom_title ?? "Fiche d'exercices";

  function startTd() {
    router.patch(route('td.status.update', td.id), { status: 'ongoing' });
  }

  function requestUnlock() {
    setRequesting(true);
    router.post(
      route('td.request-unlock', td.id),
      {},
      {
        onFinish: () => setRequesting(false),
      }
    );
  }

  return (
    <AppLayout>
      <Head title={title} />
      <div className="max-w-3xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title={title}
          breadcrumbs={[
            { label: 'Mes devoirs', href: route('student.assignments.index') },
            { label: title },
          ]}
        />

        {td.status === 'not_started' && (
          <div className="space-y-4">
            {td.teacher && (
              <p className="text-sm text-text-gray">
                Professeur :{' '}
                <span className="font-comfortaa-bold text-text-color">
                  {td.teacher.first_name} {td.teacher.last_name}
                </span>
              </p>
            )}
            {td.custom_level && (
              <span className="inline-flex text-xs px-2.5 py-0.5 rounded-full bg-student-color/10 text-student-color font-comfortaa-bold">
                {td.custom_level}
              </span>
            )}
            {td.custom_instructions && (
              <TheoremCard accent="student" lined>
                <p className="text-sm text-text-color leading-relaxed whitespace-pre-line">
                  {td.custom_instructions}
                </p>
              </TheoremCard>
            )}
            <AssignmentContentList
              problems={[]}
              exercises={td.exercises}
              privateExercises={td.private_exercises}
            />
            <Button variant="student" icon={CheckCircle} onClick={startTd}>
              Commencer la fiche
            </Button>
          </div>
        )}

        {td.status === 'ongoing' && (
          <div className="space-y-4">
            <AssignmentContentList
              problems={[]}
              exercises={td.exercises}
              privateExercises={td.private_exercises}
            />
            <TheoremCard accent="student" dotted>
              <SectionLabel>Correction</SectionLabel>
              <p className="mt-2 text-sm text-text-gray leading-relaxed">
                Quand tu as terminé, demande à ton professeur de débloquer la correction.
              </p>
              <div className="mt-4">
                <Button
                  variant="student"
                  icon={Send}
                  isLoading={requesting}
                  onClick={requestUnlock}
                >
                  Demander la correction
                </Button>
              </div>
            </TheoremCard>
          </div>
        )}

        {td.status === 'correction_requested' && (
          <div className="space-y-4">
            <TheoremCard accent="teacher">
              <div className="flex items-center gap-2">
                <Clock size={16} className="text-teacher-color" />
                <p className="text-sm font-comfortaa-bold text-teacher-color">
                  Correction demandée — en attente de votre professeur
                </p>
              </div>
            </TheoremCard>
            <AssignmentContentList
              problems={[]}
              exercises={td.exercises}
              privateExercises={td.private_exercises}
            />
          </div>
        )}

        {td.status === 'correction_unlocked' && (
          <div className="space-y-4">
            <TheoremCard accent="student">
              <div className="flex items-center gap-2">
                <BookOpenCheck size={16} className="text-success-color" />
                <p className="text-sm font-comfortaa-bold text-success-color">
                  Correction débloquée
                </p>
              </div>
            </TheoremCard>
            <AssignmentContentList
              problems={[]}
              exercises={td.exercises}
              privateExercises={td.private_exercises}
              showSolutions
            />
          </div>
        )}
      </div>
    </AppLayout>
  );
}
