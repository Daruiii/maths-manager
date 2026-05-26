interface Props {
  totalActive: number;
  totalPending: number;
  totalArchived: number;
}

export default function BureauDevoirsHero({ totalActive, totalPending, totalArchived }: Props) {
  return (
    <div className="relative mm-card mm-card-style-halo mm-card-accent-teacher rounded-3xl px-5 sm:px-8 py-4 overflow-hidden">
      <div
        className="absolute inset-0 overflow-hidden rounded-3xl pointer-events-none select-none"
        aria-hidden
      >
        <div className="absolute inset-0 flex items-center justify-end pr-8">
          <span className="text-[130px] font-cmu-serif text-text-color opacity-[0.04] leading-none">
            ∑
          </span>
        </div>
      </div>
      <div className="relative flex items-center justify-between gap-4">
        <div className="flex-1 min-w-0 space-y-0.5">
          <p className="text-[11px] font-comfortaa-bold text-teacher-color uppercase tracking-widest">
            Vue d'ensemble
          </p>
          <p className="text-sm text-text-gray">Actifs, actions à traiter et archives.</p>
        </div>
        <div className="flex items-center gap-4 shrink-0">
          <HeroStat value={totalActive} label={`Actif${totalActive > 1 ? 's' : ''}`} />
          {totalPending > 0 && <HeroStat value={totalPending} label="À traiter" tone="warning" />}
          {totalArchived > 0 && (
            <HeroStat
              value={totalArchived}
              label="Archivés"
              tone="muted"
              className="hidden sm:block"
            />
          )}
        </div>
      </div>
    </div>
  );
}

interface HeroStatProps {
  value: number;
  label: string;
  tone?: 'default' | 'warning' | 'muted';
  className?: string;
}

function HeroStat({ value, label, tone = 'default', className = '' }: HeroStatProps) {
  const toneClass = {
    default: 'text-text-color',
    warning: 'text-warning-color',
    muted: 'text-text-gray/50',
  }[tone];

  return (
    <div className={`text-right ${className}`}>
      <p className={`text-xl sm:text-2xl font-cmu-serif leading-none ${toneClass}`}>{value}</p>
      <p
        className={`text-[10px] font-comfortaa-bold uppercase tracking-widest mt-0.5 ${toneClass}`}
      >
        {label}
      </p>
    </div>
  );
}
