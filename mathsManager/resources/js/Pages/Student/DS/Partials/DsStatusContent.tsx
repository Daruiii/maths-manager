import { Link } from '@inertiajs/react';
import { CheckCircle, Timer, AlertTriangle, Pause, Play, ChevronLeft } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import AssignmentMeta from '@/Components/Features/Assignments/AssignmentMeta';
import AwaitingCorrectionCard from '@/Components/Features/Assignments/AwaitingCorrectionCard';
import CorrectionHero from '@/Components/Features/Assignments/CorrectionHero';
import CopySubmitModal from '@/Components/Features/Assignments/CopySubmitModal';
import DsContentList from '@/Pages/Student/DS/Partials/DsContentList';
import DsHiddenSubjectNotice from '@/Pages/Student/DS/Partials/DsHiddenSubjectNotice';
import DsTimerDock from '@/Pages/Student/DS/Partials/DsTimerDock';
import type { DsStatusContentProps } from '@/Pages/Student/DS/Partials/dsStatusContentTypes';

export default function DsStatusContent({
  ds,
  remainingFormatted,
  instructions,
  urgent,
  sessionToken,
  message,
  submitting,
  uploadError,
  onStart,
  onPause,
  onResume,
  onFinish,
  onSubmitCopy,
  onTokenChange,
  onMessageChange,
}: DsStatusContentProps) {
  const cr = ds.correction_request;

  const contentList = (
    <DsContentList ds={ds} instructions={instructions} showSolutions={ds.status === 'corrected'} />
  );

  if (ds.status === 'not_started') {
    return (
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
        <DsHiddenSubjectNotice>
          Le sujet sera révélé uniquement après avoir cliqué sur{' '}
          <span className="font-comfortaa-bold text-text-color">« Commencer le DS »</span>.
          Assurez-vous d&apos;être prêt — le chronomètre démarre immédiatement.
        </DsHiddenSubjectNotice>
        <Button variant="student" icon={CheckCircle} onClick={onStart}>
          Commencer le DS
        </Button>
      </div>
    );
  }

  if (ds.status === 'ongoing') {
    return (
      <div className="space-y-4">
        {contentList}
        <DsTimerDock
          remainingFormatted={remainingFormatted}
          urgent={urgent}
          onPause={onPause}
          onFinish={onFinish}
        />
      </div>
    );
  }

  if (ds.status === 'paused') {
    return (
      <div className="space-y-3">
        <AssignmentMeta teacher={ds.teacher} level={ds.custom_level} />
        <TheoremCard accent="student">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-3">
              <Pause size={16} className="text-student-color" />
              <span className="text-2xl font-cmu-serif tabular-nums leading-none text-text-color">
                {remainingFormatted} — En pause
              </span>
            </div>
            <Button variant="student" icon={Play} onClick={onResume}>
              Reprendre
            </Button>
          </div>
        </TheoremCard>
        <DsHiddenSubjectNotice>
          Le sujet est masqué pendant la pause pour préserver l&apos;intégrité du devoir. Reprends
          le DS pour y accéder.
        </DsHiddenSubjectNotice>
        <Link
          href={route('student.assignments.index')}
          className="inline-flex items-center gap-1.5 text-sm text-text-gray hover:text-student-color transition-colors"
        >
          <ChevronLeft size={14} />
          Retour à mes devoirs
        </Link>
      </div>
    );
  }

  if (ds.status === 'finished' || ds.status === 'finished_late') {
    return (
      <div className="space-y-4">
        {ds.status === 'finished_late' && (
          <TheoremCard accent="teacher">
            <div className="flex items-center gap-2">
              <AlertTriangle size={16} className="text-warning-color" />
              <p className="text-sm font-comfortaa-bold text-warning-color">
                Temps écoulé — envoie ta copie.
              </p>
            </div>
          </TheoremCard>
        )}
        {contentList}
        <CopySubmitModal
          onSubmit={onSubmitCopy}
          sessionToken={sessionToken}
          onTokenChange={onTokenChange}
          message={message}
          onMessageChange={onMessageChange}
          submitting={submitting}
          uploadError={uploadError}
          label="Envoyer le DS"
          description="DS terminé — envoie ta copie pour correction."
        />
      </div>
    );
  }

  if (ds.status === 'sent' && cr) {
    return <AwaitingCorrectionCard cr={cr} />;
  }

  if (ds.status === 'corrected' && cr) {
    return (
      <div className="space-y-4">
        <CorrectionHero cr={cr} solutionsAnchor="assignment-content" />
        <div id="assignment-content" className="border-t border-border-color pt-4">
          {contentList}
        </div>
      </div>
    );
  }

  return null;
}
