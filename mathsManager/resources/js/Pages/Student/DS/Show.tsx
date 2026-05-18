import { Head, Link, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import {
  CheckCircle,
  Clock,
  Timer,
  BookOpenCheck,
  AlertTriangle,
  Pause,
  Play,
  ChevronLeft,
  EyeOff,
} from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Button from '@/Components/Common/UI/Button';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import AssignmentContentList from '@/Components/Features/Assignments/AssignmentContentList';
import AssignmentMeta from '@/Components/Features/Assignments/AssignmentMeta';
import CopySubmitSection from '@/Components/Features/Assignments/CopySubmitSection';
import CorrectionResultBlock from '@/Components/Features/Assignments/CorrectionResultBlock';
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
  const { remaining } = useDsTimer(ds.id, ds.timer_seconds, ds.status === 'ongoing');
  const [sessionToken, setSessionToken] = useState<string | null>(null);
  const [message, setMessage] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const { errors } = usePage().props;

  const title = ds.custom_title ?? 'Devoir Surveillé';
  const cr = ds.correction_request;
  const uploadError =
    typeof errors.upload_session_token === 'string' ? errors.upload_session_token : null;
  const urgent = remaining <= 600 && ds.status === 'ongoing';

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
    if (!window.confirm('Terminer le DS ? Cette action est irréversible.')) return;
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

  const contentList = (
    <AssignmentContentList
      problems={ds.problems}
      exercises={ds.exercises}
      privateExercises={ds.private_exercises}
      variant="academic"
      title={title}
      level={ds.custom_level}
      instructions={defaultInstructions(ds)}
      showSolutions={ds.status === 'corrected'}
    />
  );

  return (
    <AppLayout>
      <Head title={title} />
      <div className="max-w-3xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title={title}
          breadcrumbs={[{ label: 'Mes devoirs', href: route('home') }, { label: title }]}
        />

        {ds.status === 'not_started' && (
          <div className="space-y-4">
            <AssignmentMeta teacher={ds.teacher} level={ds.custom_level} />
            <TheoremCard accent="student">
              <div className="flex items-center gap-2">
                <Timer size={16} className="text-student-color" />
                <p className="text-sm font-comfortaa-bold text-text-color">
                  Durée : {ds.time_minutes} min
                </p>
              </div>
            </TheoremCard>
            <TheoremCard accent="student" dotted>
              <div className="flex items-start gap-2">
                <EyeOff size={15} className="text-text-gray mt-0.5 shrink-0" />
                <p className="text-sm text-text-gray leading-relaxed">
                  Le sujet sera révélé uniquement après avoir cliqué sur{' '}
                  <span className="font-comfortaa-bold text-text-color">« Commencer le DS »</span>.
                  Assurez-vous d&apos;être prêt — le chronomètre démarre immédiatement.
                </p>
              </div>
            </TheoremCard>
            <Button variant="student" icon={CheckCircle} onClick={startDs}>
              Commencer le DS
            </Button>
          </div>
        )}

        {ds.status === 'ongoing' && (
          <div className="space-y-4">
            <TheoremCard accent={urgent ? 'teacher' : 'student'}>
              <div className="flex items-center justify-between">
                <div className="flex items-center gap-2">
                  <Timer
                    size={16}
                    className={urgent ? 'text-teacher-color' : 'text-student-color'}
                  />
                  <p
                    className={`text-lg font-comfortaa-bold tabular-nums ${urgent ? 'text-teacher-color' : 'text-text-color'}`}
                  >
                    {formatTime(remaining)}
                  </p>
                </div>
                <div className="flex items-center gap-2">
                  <Button variant="ghost" icon={Pause} onClick={pauseDs}>
                    Pause
                  </Button>
                  <Button variant="teacher" onClick={finishDs}>
                    Terminer
                  </Button>
                </div>
              </div>
            </TheoremCard>
            {contentList}
          </div>
        )}

        {ds.status === 'paused' && (
          <div className="space-y-3">
            <AssignmentMeta teacher={ds.teacher} level={ds.custom_level} />
            <TheoremCard accent="student">
              <div className="flex items-center justify-between">
                <div className="flex items-center gap-2">
                  <Pause size={16} className="text-student-color" />
                  <p className="text-lg font-comfortaa-bold tabular-nums text-text-color">
                    {formatTime(ds.timer_seconds)} — En pause
                  </p>
                </div>
                <Button variant="student" icon={Play} onClick={resumeDs}>
                  Reprendre
                </Button>
              </div>
            </TheoremCard>
            <TheoremCard accent="student" dotted>
              <div className="flex items-start gap-2">
                <EyeOff size={15} className="text-text-gray mt-0.5 shrink-0" />
                <p className="text-sm text-text-gray leading-relaxed">
                  Le sujet est masqué pendant la pause pour préserver l'intégrité du devoir.
                  Reprends le DS pour y accéder.
                </p>
              </div>
            </TheoremCard>
            <Link
              href={route('home')}
              className="inline-flex items-center gap-1.5 text-sm text-text-gray hover:text-student-color transition-colors"
            >
              <ChevronLeft size={14} />
              Retour à mes devoirs
            </Link>
          </div>
        )}

        {(ds.status === 'finished' || ds.status === 'finished_late') && (
          <form onSubmit={submitCopy} className="space-y-4">
            {ds.status === 'finished_late' && (
              <TheoremCard accent="teacher">
                <div className="flex items-center gap-2">
                  <AlertTriangle size={16} className="text-warning-color" />
                  <p className="text-sm font-comfortaa-bold text-warning-color">
                    Temps écoulé — envoyez votre copie.
                  </p>
                </div>
              </TheoremCard>
            )}
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

        {ds.status === 'sent' && cr && (
          <div className="space-y-4">
            <TheoremCard accent="teacher">
              <div className="flex items-center gap-2">
                <Clock size={16} className="text-teacher-color" />
                <p className="text-sm font-comfortaa-bold text-teacher-color">
                  Copie envoyée — en attente de correction
                </p>
              </div>
            </TheoremCard>
          </div>
        )}

        {ds.status === 'corrected' && cr && (
          <div className="space-y-4">
            <TheoremCard accent="student">
              <div className="flex items-center gap-2">
                <BookOpenCheck size={16} className="text-success-color" />
                <p className="text-sm font-comfortaa-bold text-success-color">Corrigé</p>
              </div>
            </TheoremCard>
            {contentList}
            <CorrectionResultBlock cr={cr} />
          </div>
        )}
      </div>
    </AppLayout>
  );
}
