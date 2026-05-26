interface Props {
  count: number;
  className?: string;
  variant?: 'compact' | 'toc';
  showSolutions?: boolean;
  onOpenSolution?: (index: number) => void;
}

export default function ExerciseNav({
  count,
  className = '',
  variant = 'compact',
  showSolutions = false,
  onOpenSolution,
}: Props) {
  if (count <= 1) return null;

  if (variant === 'toc') {
    return (
      <nav className={className} aria-label="Sommaire des exercices">
        <p className="text-[9px] font-comfortaa-bold uppercase tracking-[0.14em] text-text-gray">
          Exercices
        </p>
        <div className="mt-2 space-y-1 border-l border-border-color/70 pl-3">
          {Array.from({ length: count }, (_, index) => (
            <div key={index} className="space-y-0.5">
              <a
                href={`#ex-${index + 1}`}
                className="group relative -ml-[16px] flex items-center gap-2 py-0.5 text-[11px] font-comfortaa text-text-gray hover:text-student-color transition-colors"
              >
                <span className="h-1.5 w-1.5 rounded-full border border-border-color bg-secondary-color group-hover:border-student-color group-hover:bg-student-color transition-colors" />
                Ex. {index + 1}
              </a>
              {showSolutions && (
                <a
                  href={`#sol-${index + 1}`}
                  onClick={() => onOpenSolution?.(index)}
                  className="group relative -ml-[13px] flex items-center gap-1.5 py-0.5 text-[10px] font-comfortaa text-success-color/70 hover:text-success-color transition-colors"
                >
                  <span className="h-1 w-1 rounded-full bg-success-color/40 group-hover:bg-success-color transition-colors" />
                  Sol. {index + 1}
                </a>
              )}
            </div>
          ))}
        </div>
      </nav>
    );
  }

  return (
    <nav className={className} aria-label="Navigation des exercices">
      <span className="text-[10px] font-comfortaa-bold uppercase tracking-widest text-text-gray shrink-0">
        Aller à
      </span>
      <div className="flex gap-1.5 flex-wrap">
        {Array.from({ length: count }, (_, index) => (
          <a
            key={`ex-${index}`}
            href={`#ex-${index + 1}`}
            className="inline-flex h-7 min-w-7 items-center justify-center rounded-full border border-border-color bg-secondary-color px-2 text-[10px] font-comfortaa-bold text-text-gray shadow-warm-xs hover:border-student-color/50 hover:bg-student-color/10 hover:text-student-color transition-colors"
          >
            Ex. {index + 1}
          </a>
        ))}
        {showSolutions &&
          Array.from({ length: count }, (_, index) => (
            <a
              key={`sol-${index}`}
              href={`#sol-${index + 1}`}
              onClick={() => onOpenSolution?.(index)}
              className="inline-flex h-7 min-w-7 items-center justify-center rounded-full border border-success-color/30 bg-success-color/5 px-2 text-[10px] font-comfortaa-bold text-success-color shadow-warm-xs hover:bg-success-color/10 transition-colors"
            >
              Sol. {index + 1}
            </a>
          ))}
      </div>
    </nav>
  );
}
