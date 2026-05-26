import { Head, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import { CheckCircle, AlertTriangle } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Button from '@/Components/Common/UI/Button';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import AssignmentContentList from '@/Components/Features/Assignments/AssignmentContentList';
import AssignmentMeta from '@/Components/Features/Assignments/AssignmentMeta';
import AwaitingCorrectionCard from '@/Components/Features/Assignments/AwaitingCorrectionCard';
import CopySubmitModal from '@/Components/Features/Assignments/CopySubmitModal';
import CorrectionHero from '@/Components/Features/Assignments/CorrectionHero';
import TeacherPreviewView from '@/Components/Features/Assignments/TeacherPreviewView';
import type { Dm } from '@/types/models';

export default function DmShow({ dm }: { dm: Dm }) {
  const isTeacherPreview = dm.is_teacher_preview ?? false;
  const [sessionToken, setSessionToken] = useState<string | null>(null);
  const [message, setMessage] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const { errors } = usePage().props;

  const title = dm.custom_title ?? 'Devoir Maison';
  const cr = dm.correction_request;
  const uploadError =
    typeof errors.upload_session_token === 'string' ? errors.upload_session_token : null;

  if (isTeacherPreview) {
    return (
      <TeacherPreviewView
        title={title}
        level={dm.custom_level}
        instructions={dm.custom_instructions}
        problems={dm.problems}
        exercises={dm.exercises}
        privateExercises={dm.private_exercises}
      />
    );
  }

  function startDm() {
    router.patch(route('dm.status.update', dm.id), { status: 'ongoing' });
  }

  function submitCopy(e: React.SyntheticEvent) {
    e.preventDefault();
    if (!sessionToken || submitting) return;
    setSubmitting(true);
    router.post(
      route('dm.correction.submit', dm.id),
      { upload_session_token: sessionToken, message },
      { onFinish: () => setSubmitting(false) }
    );
  }

  const contentList = (
    <AssignmentContentList
      problems={dm.problems}
      exercises={dm.exercises}
      privateExercises={dm.private_exercises}
      variant="academic"
      title={title}
      level={dm.custom_level}
      instructions={dm.custom_instructions}
    />
  );

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

        {dm.status === 'not_started' && (
          <div className="space-y-4">
            <div className="flex items-center justify-between gap-3 flex-wrap">
              <AssignmentMeta teacher={dm.teacher} level={dm.custom_level} />
              <Button variant="ghost" size="sm" icon={CheckCircle} onClick={startDm}>
                Commencer le devoir
              </Button>
            </div>
            {dm.custom_instructions && (
              <TheoremCard accent="student" lined>
                <p className="text-sm text-text-color leading-relaxed whitespace-pre-line">
                  {dm.custom_instructions}
                </p>
              </TheoremCard>
            )}
            {contentList}
          </div>
        )}

        {dm.status === 'ongoing' && (
          <div className="space-y-4">
            {contentList}
            <CopySubmitModal
              onSubmit={submitCopy}
              sessionToken={sessionToken}
              onTokenChange={setSessionToken}
              message={message}
              onMessageChange={setMessage}
              submitting={submitting}
              uploadError={uploadError}
              label="Envoyer le DM"
              description="DM terminé ? Envoie ta copie pour que ton professeur puisse corriger."
            />
          </div>
        )}

        {dm.status === 'finished' &&
          (cr ? (
            <AwaitingCorrectionCard cr={cr} />
          ) : (
            <div className="space-y-4">
              <TheoremCard accent="teacher">
                <div className="flex items-center gap-2">
                  <AlertTriangle size={15} className="text-warning-color shrink-0" />
                  <p className="text-sm font-comfortaa-bold text-warning-color">
                    Délai dépassé — envoie ta copie pour recevoir ta correction.
                  </p>
                </div>
              </TheoremCard>
              {contentList}
              <CopySubmitModal
                onSubmit={submitCopy}
                sessionToken={sessionToken}
                onTokenChange={setSessionToken}
                message={message}
                onMessageChange={setMessage}
                submitting={submitting}
                uploadError={uploadError}
                label="Envoyer le DM"
                description="Délai dépassé — envoie ta copie pour recevoir ta correction."
              />
            </div>
          ))}

        {dm.status === 'corrected' && cr && (
          <div className="space-y-4">
            <CorrectionHero cr={cr} solutionsAnchor="assignment-content" />
            <div id="assignment-content" className="border-t border-border-color pt-4">
              <AssignmentContentList
                problems={dm.problems}
                exercises={dm.exercises}
                privateExercises={dm.private_exercises}
                variant="academic"
                title={title}
                level={dm.custom_level}
                instructions={dm.custom_instructions}
                showSolutions
              />
            </div>
          </div>
        )}
      </div>
    </AppLayout>
  );
}
