import { Pause, Timer } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import FloatingActionDock from '@/Components/Common/UI/FloatingActionDock';

interface Props {
  remainingFormatted: string;
  urgent: boolean;
  onPause: () => void;
  onFinish: () => void;
}

export default function DsTimerDock({ remainingFormatted, urgent, onPause, onFinish }: Props) {
  const iconClass = urgent ? 'text-warning-color' : 'text-student-color';
  const valueClass = urgent ? 'text-warning-color' : 'text-text-color';
  const iconBg = urgent ? 'bg-warning-color/10' : 'bg-student-color/10';

  return (
    <FloatingActionDock
      leading={
        <div className="flex items-center justify-center gap-2.5 sm:justify-start">
          <span className={`flex h-8 w-8 items-center justify-center rounded-full ${iconBg}`}>
            <Timer size={15} className={iconClass} />
          </span>
          <div className="flex items-baseline gap-2 sm:block">
            <p className="text-[10px] font-comfortaa-bold uppercase tracking-widest text-text-gray">
              Temps restant
            </p>
            <span className={`text-2xl font-cmu-serif tabular-nums leading-none ${valueClass}`}>
              {remainingFormatted}
            </span>
          </div>
        </div>
      }
      actions={
        <div className="grid grid-cols-2 gap-2 sm:flex sm:items-center sm:gap-1.5">
          <Button variant="ghost" icon={Pause} onClick={onPause}>
            Pause
          </Button>
          <Button variant="student" onClick={onFinish}>
            Terminer
          </Button>
        </div>
      }
    />
  );
}
