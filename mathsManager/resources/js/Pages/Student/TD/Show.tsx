import { Head, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { Clock, BookOpenCheck, Send } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import ConfirmationModal from '@/Components/Common/UI/ConfirmationModal';
import FloatingActionDock from '@/Components/Common/UI/FloatingActionDock';
import AssignmentContentList from '@/Components/Features/Assignments/AssignmentContentList';
import AssignmentMeta from '@/Components/Features/Assignments/AssignmentMeta';
import type { Td } from '@/types/models';

export default function TdShow({ td }: { td: Td }) {
  const [requesting, setRequesting] = useState(false);
  const [confirmOpen, setConfirmOpen] = useState(false);

  const title = td.custom_title ?? "Fiche d'exercices";
  const shouldShowTraining = td.status === 'not_started' || td.status === 'ongoing';

  useEffect(() => {
    if (td.is_teacher || td.status !== 'not_started') return;

    router.patch(
      route('td.status.update', td.id),
      { status: 'ongoing' },
      { preserveScroll: true, preserveState: true }
    );
  }, [td.id, td.is_teacher, td.status]);

  function requestUnlock() {
    setRequesting(true);
    router.post(route('td.request-unlock', td.id), {}, { onFinish: () => setRequesting(false) });
  }

  const contentList = (
    <AssignmentContentList
      problems={[]}
      exercises={td.exercises}
      privateExercises={td.private_exercises}
      showSolutions={td.correction_unlocked}
    />
  );

  return (
    <AppLayout>
      <Head title={title} />
      <div className="max-w-3xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title={title}
          breadcrumbs={
            td.is_teacher
              ? [
                  { label: 'Mon Bureau', href: route('teacher.bureau.index') },
                  { label: 'Devoirs envoyés', href: route('teacher.bureau.devoirs') },
                  { label: title },
                ]
              : [
                  { label: 'Mes devoirs', href: route('student.assignments.index') },
                  { label: title },
                ]
          }
        />

        {shouldShowTraining && (
          <>
            <div className="space-y-4">
              <AssignmentMeta teacher={td.teacher} level={td.custom_level} />
              {td.custom_instructions && (
                <TheoremCard accent="student" lined>
                  <p className="text-sm text-text-color leading-relaxed whitespace-pre-line">
                    {td.custom_instructions}
                  </p>
                </TheoremCard>
              )}
              {contentList}
            </div>

            {!td.is_teacher && (
              <>
                <FloatingActionDock
                  label="Demander les solutions"
                  mobileLabel="Demander"
                  description="Fiche terminée ? Demande les solutions à ton professeur."
                  icon={Send}
                  onClick={() => setConfirmOpen(true)}
                />

                <ConfirmationModal
                  isOpen={confirmOpen}
                  onClose={() => setConfirmOpen(false)}
                  onConfirm={requestUnlock}
                  title="Demander la correction ?"
                  description="Ton professeur recevra une demande et pourra débloquer les solutions."
                  confirmText="Envoyer la demande"
                  type="success"
                  isSubmitting={requesting}
                />
              </>
            )}
          </>
        )}

        {td.status === 'correction_requested' && (
          <div className="space-y-4">
            {!td.is_teacher && (
              <TheoremCard accent="tertiary">
                <div className="flex items-center gap-2">
                  <Clock size={16} className="text-text-gray" />
                  <p className="text-sm font-comfortaa-bold text-text-color">
                    Correction demandée — en attente de ton professeur
                  </p>
                </div>
              </TheoremCard>
            )}
            {contentList}
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
            {contentList}
          </div>
        )}
      </div>
    </AppLayout>
  );
}
