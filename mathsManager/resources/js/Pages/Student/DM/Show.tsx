import { Head, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import { CheckCircle, Clock, Send } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Button from '@/Components/Common/UI/Button';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import UploadSessionWidget from '@/Components/Features/Uploads/UploadSessionWidget';
import PictureGrid from '@/Components/Features/Corrections/PictureGrid';
import AssignmentContentList from '@/Components/Features/Assignments/AssignmentContentList';
import type { Dm } from '@/types/models';

export default function DmShow({ dm }: { dm: Dm }) {
  const [sessionToken, setSessionToken] = useState<string | null>(null);
  const [message, setMessage] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const { errors } = usePage().props;

  const title = dm.custom_title ?? 'Devoir Maison';
  const cr = dm.correction_request;
  const uploadError =
    typeof errors.upload_session_token === 'string' ? errors.upload_session_token : null;

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

  return (
    <AppLayout>
      <Head title={title} />
      <div className="max-w-3xl mx-auto px-4 py-6 space-y-6">
        <PageHeader title={title} breadcrumbs={[{ label: title }]} />

        {dm.status === 'not_started' && (
          <div className="space-y-4">
            {dm.teacher && (
              <p className="text-sm text-text-gray">
                Professeur :{' '}
                <span className="font-comfortaa-bold text-text-color">
                  {dm.teacher.first_name} {dm.teacher.last_name}
                </span>
              </p>
            )}
            {dm.custom_level && (
              <span className="inline-flex text-xs px-2.5 py-0.5 rounded-full bg-student-color/10 text-student-color font-comfortaa-bold">
                {dm.custom_level}
              </span>
            )}
            {dm.custom_instructions && (
              <TheoremCard accent="student" lined>
                <p className="text-sm text-text-color leading-relaxed whitespace-pre-line">
                  {dm.custom_instructions}
                </p>
              </TheoremCard>
            )}
            <AssignmentContentList
              problems={dm.problems}
              exercises={dm.exercises}
              privateExercises={dm.private_exercises}
              variant="academic"
              title={title}
              level={dm.custom_level}
              instructions={dm.custom_instructions}
            />
            <Button variant="student" icon={CheckCircle} onClick={startDm}>
              Commencer le devoir
            </Button>
          </div>
        )}

        {dm.status === 'ongoing' && (
          <form onSubmit={submitCopy} className="space-y-4">
            <AssignmentContentList
              problems={dm.problems}
              exercises={dm.exercises}
              privateExercises={dm.private_exercises}
              variant="academic"
              title={title}
              level={dm.custom_level}
              instructions={dm.custom_instructions}
            />
            <TheoremCard accent="student" dotted>
              <SectionLabel>Envoyer votre copie</SectionLabel>
              <div className="mt-3">
                <UploadSessionWidget
                  purpose="correction_submission"
                  accentColor="student"
                  onTokenChange={setSessionToken}
                />
              </div>
            </TheoremCard>
            {uploadError && (
              <div className="rounded-xl border border-error-color/20 bg-error-color/10 px-4 py-3 text-sm text-error-color">
                {uploadError}
              </div>
            )}
            <textarea
              value={message}
              onChange={(e) => setMessage(e.target.value)}
              placeholder="Message pour le professeur (optionnel)"
              rows={3}
              className="w-full rounded-xl border border-border-color bg-secondary-color px-4 py-3 text-sm text-text-color placeholder:text-text-gray resize-none focus:outline-none focus:border-student-color/50"
            />
            <Button
              type="submit"
              variant="student"
              icon={Send}
              isLoading={submitting}
              disabled={!sessionToken}
            >
              Envoyer ma copie
            </Button>
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
            {cr.grade !== null && (
              <div className="flex items-center justify-center py-6">
                <span
                  className={`text-5xl font-comfortaa-bold ${cr.grade >= 10 ? 'text-success-color' : 'text-error-color'}`}
                >
                  {cr.grade}
                  <span className="text-2xl text-text-gray">/20</span>
                </span>
              </div>
            )}
            <div className="grid sm:grid-cols-2 gap-4">
              {cr.pictures.length > 0 && (
                <TheoremCard accent="student">
                  <SectionLabel>Votre copie</SectionLabel>
                  <div className="mt-3">
                    <PictureGrid paths={cr.pictures} label="Copie" />
                  </div>
                </TheoremCard>
              )}
              {cr.correction_pictures && cr.correction_pictures.length > 0 && (
                <TheoremCard accent="teacher">
                  <SectionLabel>Correction</SectionLabel>
                  <div className="mt-3">
                    <PictureGrid paths={cr.correction_pictures} label="Correction" />
                  </div>
                </TheoremCard>
              )}
            </div>
            {cr.correction_message && (
              <TheoremCard accent="teacher">
                <SectionLabel>Message du professeur</SectionLabel>
                <p className="mt-2 text-sm text-text-color leading-relaxed">
                  {cr.correction_message}
                </p>
              </TheoremCard>
            )}
          </div>
        )}
      </div>
    </AppLayout>
  );
}
