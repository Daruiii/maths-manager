import { Head, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import { Eye } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import ConfirmationModal from '@/Components/Common/UI/ConfirmationModal';
import DsStatusContent from '@/Pages/Student/DS/Partials/DsStatusContent';
import AssignmentContentList from '@/Components/Features/Assignments/AssignmentContentList';
import { useDsTimer } from '@/Hooks/DS/useDsTimer';
import type { Ds } from '@/types/models';

function formatTime(seconds: number): string {
  const h = Math.floor(seconds / 3600);
  const m = Math.floor((seconds % 3600) / 60);
  const s = seconds % 60;
  if (h > 0) return `${h}h${String(m).padStart(2, '0')}`;
  return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
}

function defaultInstructions(ds: Ds): string {
  if (ds.custom_instructions) return ds.custom_instructions;
  if (ds.type_bac)
    return 'Simulation rigoureuse du baccalauréat. Respectez le temps imparti, soignez la présentation et encadrez vos résultats. La calculatrice est autorisée.';
  if (ds.harder_exercises)
    return 'Devoir de difficulté élevée (niveau supérieur). Respectez le temps imparti et soignez la présentation. La calculatrice est autorisée.';
  return 'Devoir surveillé — respectez le temps imparti et soignez la présentation. La calculatrice est autorisée.';
}

export default function DsShow({ ds }: { ds: Ds }) {
  const isTeacherPreview = ds.is_teacher_preview ?? false;
  const { remaining } = useDsTimer(
    ds.id,
    ds.timer_seconds,
    !isTeacherPreview && ds.status === 'ongoing'
  );
  const [sessionToken, setSessionToken] = useState<string | null>(null);
  const [message, setMessage] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [showFinishConfirm, setShowFinishConfirm] = useState(false);
  const { errors } = usePage().props;

  const title = ds.custom_title ?? 'Devoir Surveillé';
  const uploadError =
    typeof errors.upload_session_token === 'string' ? errors.upload_session_token : null;

  if (isTeacherPreview) {
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
            problems={ds.problems}
            exercises={ds.exercises}
            privateExercises={ds.private_exercises}
            variant="academic"
            title={title}
            level={ds.custom_level}
            instructions={defaultInstructions(ds)}
            showSolutions
          />
        </div>
      </AppLayout>
    );
  }

  function startDs() {
    router.patch(route('ds.status.update', ds.id), { status: 'ongoing' });
  }

  function pauseDs() {
    router.patch(route('ds.status.update', ds.id), { status: 'paused' });
  }

  function resumeDs() {
    router.patch(route('ds.status.update', ds.id), { status: 'ongoing' });
  }

  function finishDs() {
    setShowFinishConfirm(true);
  }

  function confirmFinishDs() {
    setShowFinishConfirm(false);
    router.patch(route('ds.status.update', ds.id), { status: 'finished' });
  }

  function submitCopy(e: React.SyntheticEvent) {
    e.preventDefault();
    if (!sessionToken || submitting) return;
    setSubmitting(true);
    router.post(
      route('ds.correction.submit', ds.id),
      { upload_session_token: sessionToken, message },
      { onFinish: () => setSubmitting(false) }
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
        <DsStatusContent
          ds={ds}
          remainingFormatted={formatTime(remaining)}
          instructions={defaultInstructions(ds)}
          urgent={remaining <= 600 && ds.status === 'ongoing'}
          sessionToken={sessionToken}
          message={message}
          submitting={submitting}
          uploadError={uploadError}
          onStart={startDs}
          onPause={pauseDs}
          onResume={resumeDs}
          onFinish={finishDs}
          onSubmitCopy={submitCopy}
          onTokenChange={setSessionToken}
          onMessageChange={setMessage}
        />
      </div>
      <ConfirmationModal
        isOpen={showFinishConfirm}
        onClose={() => setShowFinishConfirm(false)}
        onConfirm={confirmFinishDs}
        title="Terminer le DS ?"
        description="Le DS sera marqué comme terminé. Tu ne pourras plus le reprendre."
        type="danger"
        confirmText="Terminer"
      />
    </AppLayout>
  );
}
