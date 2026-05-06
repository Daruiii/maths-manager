import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { CheckCircle, Send, UserRound } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Button from '@/Components/Common/UI/Button';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import PictureGrid from '@/Components/Features/Corrections/PictureGrid';
import UploadSessionWidget from '@/Components/Features/Uploads/UploadSessionWidget';
import {
  assignmentTitle,
  assignmentType,
  studentName,
} from '@/Components/Features/Corrections/correctionRequestLabels';
import type { CorrectionRequest } from '@/types/models';

export default function CorrectionShow({
  correctionRequest,
}: {
  correctionRequest: CorrectionRequest;
}) {
  const [sessionToken, setSessionToken] = useState<string | null>(null);
  const [correctionMessage, setCorrectionMessage] = useState('');
  const [grade, setGrade] = useState('');
  const [submitting, setSubmitting] = useState(false);

  const isCorrected = correctionRequest.status === 'corrected';
  const title = `Correction ${assignmentType(correctionRequest)} — ${studentName(correctionRequest)}`;

  function submitCorrection(e: React.SyntheticEvent) {
    e.preventDefault();
    if (submitting) return;
    setSubmitting(true);
    router.patch(
      route('teacher.corrections.send', correctionRequest.id),
      {
        ...(sessionToken ? { upload_session_token: sessionToken } : {}),
        correction_message: correctionMessage,
        grade: grade === '' ? null : Number(grade),
      },
      { onFinish: () => setSubmitting(false) }
    );
  }

  return (
    <AppLayout>
      <Head title={title} />
      <div className="max-w-6xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title={title}
          breadcrumbs={[
            { label: 'Mon Bureau', href: route('teacher.bureau.index') },
            { label: 'Correction' },
          ]}
        />

        <div className="grid lg:grid-cols-[0.95fr_1.05fr] gap-5">
          <div className="space-y-4">
            <TheoremCard accent="student" dotted>
              <SectionLabel>Demande élève</SectionLabel>
              <div className="mt-3 flex items-start gap-3">
                <div className="w-10 h-10 rounded-xl bg-student-color/10 text-student-color flex items-center justify-center shrink-0">
                  <UserRound size={18} />
                </div>
                <div className="space-y-1">
                  <p className="font-comfortaa-bold text-text-color">
                    {studentName(correctionRequest)}
                  </p>
                  <p className="text-sm text-text-gray">
                    {assignmentType(correctionRequest)} — {assignmentTitle(correctionRequest)}
                  </p>
                  <span className="inline-flex text-xs px-2 py-0.5 rounded-full bg-student-color/10 text-student-color font-comfortaa-bold">
                    {correctionRequest.status === 'pending' ? 'À corriger' : 'Corrigé'}
                  </span>
                </div>
              </div>
            </TheoremCard>

            <TheoremCard accent="student">
              <SectionLabel>Copie envoyée</SectionLabel>
              <div className="mt-3">
                <PictureGrid paths={correctionRequest.pictures} label="Copie élève" />
              </div>
            </TheoremCard>

            {correctionRequest.message && (
              <TheoremCard accent="student" lined>
                <SectionLabel>Message élève</SectionLabel>
                <p className="mt-2 text-sm text-text-color leading-relaxed">
                  {correctionRequest.message}
                </p>
              </TheoremCard>
            )}
          </div>

          <div className="space-y-4">
            {isCorrected ? (
              <>
                <TheoremCard accent="teacher" dotted>
                  <div className="flex items-center gap-2">
                    <CheckCircle size={17} className="text-success-color" />
                    <p className="font-comfortaa-bold text-text-color">Correction envoyée</p>
                  </div>
                  <p className="mt-2 text-sm text-text-gray">
                    Note :{' '}
                    <span className="font-comfortaa-bold text-text-color">
                      {correctionRequest.grade === null
                        ? 'Non noté'
                        : `${correctionRequest.grade}/20`}
                    </span>
                  </p>
                </TheoremCard>

                {correctionRequest.correction_pictures && (
                  <TheoremCard accent="teacher">
                    <SectionLabel>Correction</SectionLabel>
                    <div className="mt-3">
                      <PictureGrid
                        paths={correctionRequest.correction_pictures}
                        label="Correction"
                      />
                    </div>
                  </TheoremCard>
                )}

                {correctionRequest.correction_message && (
                  <TheoremCard accent="teacher" lined>
                    <SectionLabel>Message professeur</SectionLabel>
                    <p className="mt-2 text-sm text-text-color leading-relaxed">
                      {correctionRequest.correction_message}
                    </p>
                  </TheoremCard>
                )}
              </>
            ) : (
              <form onSubmit={submitCorrection} className="space-y-4">
                <TheoremCard accent="teacher" dotted>
                  <SectionLabel>Envoyer la correction</SectionLabel>
                  <div className="mt-3">
                    <UploadSessionWidget
                      purpose="teacher_correction"
                      accentColor="teacher"
                      onTokenChange={setSessionToken}
                    />
                  </div>
                </TheoremCard>

                <TheoremCard accent="teacher" lined>
                  <SectionLabel>Feedback</SectionLabel>
                  <div className="mt-3 space-y-3">
                    <textarea
                      value={correctionMessage}
                      onChange={(e) => setCorrectionMessage(e.target.value)}
                      placeholder="Message pour l'élève (optionnel)"
                      rows={4}
                      className="w-full rounded-xl border border-border-color bg-secondary-color px-4 py-3 text-sm text-text-color placeholder:text-text-gray resize-none focus:outline-none focus:border-teacher-color/50"
                    />
                    <div className="max-w-[180px]">
                      <label className="block text-xs font-comfortaa-bold text-text-gray mb-1">
                        Note /20
                      </label>
                      <input
                        type="number"
                        min="0"
                        max="20"
                        step="0.25"
                        value={grade}
                        onChange={(e) => setGrade(e.target.value)}
                        placeholder="Non noté"
                        className="w-full rounded-xl border border-border-color bg-secondary-color px-4 py-2 text-sm text-text-color placeholder:text-text-gray focus:outline-none focus:border-teacher-color/50"
                      />
                    </div>
                  </div>
                </TheoremCard>

                <Button
                  type="submit"
                  variant="teacher"
                  icon={Send}
                  isLoading={submitting}
                  disabled={submitting}
                >
                  Envoyer la correction
                </Button>
              </form>
            )}
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
