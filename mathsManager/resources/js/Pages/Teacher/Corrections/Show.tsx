import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { CheckCircle, Pencil, Save, Send, X } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Button from '@/Components/Common/UI/Button';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
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

  const [isEditing, setIsEditing] = useState(false);
  const [editGrade, setEditGrade] = useState(
    correctionRequest.grade !== null && correctionRequest.grade !== undefined
      ? String(correctionRequest.grade)
      : ''
  );
  const [editMessage, setEditMessage] = useState(correctionRequest.correction_message ?? '');
  const [editSessionToken, setEditSessionToken] = useState<string | null>(null);
  const [updating, setUpdating] = useState(false);

  const isCorrected = correctionRequest.status === 'corrected';
  const type = assignmentType(correctionRequest).toLowerCase() as 'ds' | 'dm';
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

  function updateCorrection(e: React.SyntheticEvent) {
    e.preventDefault();
    if (updating) return;
    setUpdating(true);
    router.patch(
      route('teacher.corrections.update', correctionRequest.id),
      {
        ...(editSessionToken ? { upload_session_token: editSessionToken } : {}),
        correction_message: editMessage || null,
        grade: editGrade === '' ? null : Number(editGrade),
      },
      {
        onFinish: () => {
          setUpdating(false);
          setIsEditing(false);
        },
      }
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
            { label: 'Corrections', href: route('teacher.corrections.index') },
            { label: studentName(correctionRequest) },
          ]}
        />

        <div className="grid lg:grid-cols-[0.95fr_1.05fr] gap-5">
          {/* ── Colonne gauche : demande élève ── */}
          <div className="space-y-4">
            <TheoremCard accent="student" dotted>
              <SectionLabel>Demande élève</SectionLabel>
              <div className="mt-3 flex items-start gap-3">
                <TypeBadge type={type} size="md" />
                <div className="space-y-1">
                  <p className="font-comfortaa-bold text-text-color">
                    {studentName(correctionRequest)}
                  </p>
                  <p className="text-sm text-text-gray">
                    {assignmentType(correctionRequest)} — {assignmentTitle(correctionRequest)}
                  </p>
                  <span
                    className={`inline-flex text-[10px] px-2 py-0.5 rounded-full font-comfortaa-bold ${
                      correctionRequest.status === 'pending'
                        ? 'bg-warning-color/10 text-warning-color'
                        : 'bg-success-color/10 text-success-color'
                    }`}
                  >
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

          {/* ── Colonne droite : correction prof ── */}
          <div className="space-y-4">
            {isCorrected ? (
              <>
                <TheoremCard accent="teacher" dotted>
                  <div className="flex items-center justify-between gap-3">
                    <div className="flex items-center gap-2">
                      <CheckCircle size={17} className="text-success-color" />
                      <p className="font-comfortaa-bold text-text-color">Correction envoyée</p>
                    </div>
                    {!isEditing && (
                      <Button
                        variant="ghost"
                        size="sm"
                        icon={Pencil}
                        onClick={() => setIsEditing(true)}
                      >
                        Modifier
                      </Button>
                    )}
                  </div>

                  {!isEditing && (
                    <div className="mt-3 flex items-baseline gap-1.5">
                      <span className="text-[11px] text-text-gray uppercase tracking-wider font-comfortaa-bold">
                        Note
                      </span>
                      <span className="text-3xl font-cmu-serif text-text-color leading-none">
                        {correctionRequest.grade === null ? '—' : correctionRequest.grade}
                      </span>
                      {correctionRequest.grade !== null && (
                        <span className="text-sm text-text-gray font-cmu-serif">/20</span>
                      )}
                    </div>
                  )}
                </TheoremCard>

                {isEditing ? (
                  <form onSubmit={updateCorrection} className="space-y-4">
                    <TheoremCard accent="teacher" dotted>
                      <SectionLabel>Ajouter des fichiers</SectionLabel>
                      <div className="mt-3">
                        <UploadSessionWidget
                          purpose="teacher_correction"
                          accentColor="teacher"
                          onTokenChange={setEditSessionToken}
                        />
                      </div>
                    </TheoremCard>

                    <TheoremCard accent="teacher" lined>
                      <SectionLabel>Modifier la correction</SectionLabel>
                      <div className="mt-3 space-y-3">
                        <textarea
                          value={editMessage}
                          onChange={(e) => setEditMessage(e.target.value)}
                          placeholder="Message pour l'élève (optionnel)"
                          rows={4}
                          className="w-full rounded-xl border border-border-color bg-secondary-color px-4 py-3 text-sm text-text-color placeholder:text-text-gray resize-none focus:outline-none focus:border-teacher-color/50"
                        />
                        <div className="flex items-center gap-3">
                          <div className="flex items-baseline gap-1.5">
                            <input
                              type="number"
                              min="0"
                              max="20"
                              step="0.25"
                              value={editGrade}
                              onChange={(e) => setEditGrade(e.target.value)}
                              placeholder="—"
                              className="w-20 rounded-xl border border-border-color bg-secondary-color px-3 py-2 text-2xl font-cmu-serif text-text-color placeholder:text-text-gray text-center focus:outline-none focus:border-teacher-color/50"
                            />
                            <span className="text-sm text-text-gray font-cmu-serif">/20</span>
                          </div>
                          <span className="text-xs text-text-gray">Laisser vide = non noté</span>
                        </div>
                      </div>
                    </TheoremCard>
                    <div className="flex items-center gap-2">
                      <Button
                        type="submit"
                        variant="teacher"
                        icon={Save}
                        isLoading={updating}
                        disabled={updating}
                      >
                        Enregistrer
                      </Button>
                      <Button
                        type="button"
                        variant="ghost"
                        icon={X}
                        onClick={() => setIsEditing(false)}
                        disabled={updating}
                      >
                        Annuler
                      </Button>
                    </div>
                  </form>
                ) : (
                  <>
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
                    <div className="flex items-center gap-3">
                      <div className="flex items-baseline gap-1.5">
                        <input
                          type="number"
                          min="0"
                          max="20"
                          step="0.25"
                          value={grade}
                          onChange={(e) => setGrade(e.target.value)}
                          placeholder="—"
                          className="w-20 rounded-xl border border-border-color bg-secondary-color px-3 py-2 text-2xl font-cmu-serif text-text-color placeholder:text-text-gray text-center focus:outline-none focus:border-teacher-color/50"
                        />
                        <span className="text-sm text-text-gray font-cmu-serif">/20</span>
                      </div>
                      <span className="text-xs text-text-gray">Laisser vide = non noté</span>
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
