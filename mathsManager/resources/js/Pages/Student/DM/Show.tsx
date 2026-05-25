import { Head, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import { CheckCircle, Clock, BookOpenCheck } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Button from '@/Components/Common/UI/Button';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import PictureGrid from '@/Components/Features/Corrections/PictureGrid';
import AssignmentContentList from '@/Components/Features/Assignments/AssignmentContentList';
import AssignmentMeta from '@/Components/Features/Assignments/AssignmentMeta';
import CopySubmitSection from '@/Components/Features/Assignments/CopySubmitSection';
import CorrectionResultBlock from '@/Components/Features/Assignments/CorrectionResultBlock';
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
            <AssignmentMeta teacher={dm.teacher} level={dm.custom_level} />
            {dm.custom_instructions && (
              <TheoremCard accent="student" lined>
                <p className="text-sm text-text-color leading-relaxed whitespace-pre-line">
                  {dm.custom_instructions}
                </p>
              </TheoremCard>
            )}
            {contentList}
            <Button variant="student" icon={CheckCircle} onClick={startDm}>
              Commencer le devoir
            </Button>
          </div>
        )}

        {dm.status === 'ongoing' && (
          <form onSubmit={submitCopy} className="space-y-4">
            {contentList}
            <CopySubmitSection
              sessionToken={sessionToken}
              onTokenChange={setSessionToken}
              message={message}
              onMessageChange={setMessage}
              submitting={submitting}
              uploadError={uploadError}
            />
          </form>
        )}

        {dm.status === 'finished' && cr && (
          <div className="space-y-4">
            <TheoremCard accent="teacher">
              <div className="flex items-center gap-2">
                <Clock size={16} className="text-teacher-color" />
                <p className="text-sm font-comfortaa-bold text-teacher-color">
                  Copie envoyée — en attente de correction
                </p>
              </div>
            </TheoremCard>
            {cr.pictures.length > 0 && (
              <TheoremCard accent="student">
                <SectionLabel>Votre copie</SectionLabel>
                <div className="mt-3">
                  <PictureGrid paths={cr.pictures} label="Copie" />
                </div>
              </TheoremCard>
            )}
            {cr.message && (
              <TheoremCard accent="student">
                <SectionLabel>Votre message</SectionLabel>
                <p className="mt-1 text-sm text-text-color">{cr.message}</p>
              </TheoremCard>
            )}
          </div>
        )}

        {dm.status === 'corrected' && cr && (
          <div className="space-y-4">
            <TheoremCard accent="student">
              <div className="flex items-center gap-2">
                <BookOpenCheck size={16} className="text-success-color" />
                <p className="text-sm font-comfortaa-bold text-success-color">Corrigé</p>
              </div>
            </TheoremCard>
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
            <CorrectionResultBlock cr={cr} />
          </div>
        )}
      </div>
    </AppLayout>
  );
}
