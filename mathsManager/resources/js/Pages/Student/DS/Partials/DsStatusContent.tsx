import { Link } from '@inertiajs/react';
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
import Button from '@/Components/Common/UI/Button';
import TheoremCard from '@/Components/Common/UI/TheoremCard';
import AssignmentContentList from '@/Components/Features/Assignments/AssignmentContentList';
import AssignmentMeta from '@/Components/Features/Assignments/AssignmentMeta';
import CopySubmitSection from '@/Components/Features/Assignments/CopySubmitSection';
import CorrectionResultBlock from '@/Components/Features/Assignments/CorrectionResultBlock';
import type { Ds } from '@/types/models';

interface Props {
  ds: Ds;
  remainingFormatted: string;
  instructions: string;
  urgent: boolean;
  sessionToken: string | null;
  message: string;
  submitting: boolean;
  uploadError: string | null;
  onStart: () => void;
  onPause: () => void;
  onResume: () => void;
  onFinish: () => void;
  onSubmitCopy: (e: React.SyntheticEvent) => void;
  onTokenChange: (token: string | null) => void;
  onMessageChange: (msg: string) => void;
}

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
}: Props) {
  const cr = ds.correction_request;

  const contentList = (
    <AssignmentContentList
      problems={ds.problems}
      exercises={ds.exercises}
      privateExercises={ds.private_exercises}
      variant="academic"
      title={ds.custom_title ?? 'Devoir Surveillé'}
      level={ds.custom_level}
      instructions={instructions}
      showSolutions={ds.status === 'corrected'}
    />
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
        <Button variant="student" icon={CheckCircle} onClick={onStart}>
          Commencer le DS
        </Button>
      </div>
    );
  }

  if (ds.status === 'ongoing') {
    return (
      <div className="space-y-4">
        <TheoremCard accent={urgent ? 'teacher' : 'student'}>
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-3">
              <Timer size={16} className={urgent ? 'text-teacher-color' : 'text-student-color'} />
              <span
                className={`text-2xl font-cmu-serif tabular-nums leading-none ${urgent ? 'text-teacher-color' : 'text-text-color'}`}
              >
                {remainingFormatted}
              </span>
            </div>
            <div className="flex items-center gap-2">
              <Button variant="ghost" icon={Pause} onClick={onPause}>
                Pause
              </Button>
              <Button variant="teacher" onClick={onFinish}>
                Terminer
              </Button>
            </div>
          </div>
        </TheoremCard>
        {contentList}
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
        <TheoremCard accent="student" dotted>
          <div className="flex items-start gap-2">
            <EyeOff size={15} className="text-text-gray mt-0.5 shrink-0" />
            <p className="text-sm text-text-gray leading-relaxed">
              Le sujet est masqué pendant la pause pour préserver l&apos;intégrité du devoir.
              Reprends le DS pour y accéder.
            </p>
          </div>
        </TheoremCard>
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
      <form onSubmit={onSubmitCopy} className="space-y-4">
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
          onTokenChange={onTokenChange}
          message={message}
          onMessageChange={onMessageChange}
          submitting={submitting}
          uploadError={uploadError}
        />
      </form>
    );
  }

  if (ds.status === 'sent' && cr) {
    return (
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
    );
  }

  if (ds.status === 'corrected' && cr) {
    return (
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
    );
  }

  return null;
}
