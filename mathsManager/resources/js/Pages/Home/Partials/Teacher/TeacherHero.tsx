import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';

interface HeroStat {
  value: number;
  label: string;
}

interface Props {
  firstName: string;
  message: string;
  allClear: boolean;
  stats: HeroStat[];
}

export default function TeacherHero({ firstName, message, allClear, stats }: Props) {
  return (
    <div className="relative mm-card mm-card-style-halo mm-card-accent-teacher rounded-3xl px-8 py-6 overflow-hidden animate-fadeIn">
      <div
        className="absolute inset-0 overflow-hidden rounded-3xl pointer-events-none select-none"
        aria-hidden
      >
        <div className="absolute inset-0 flex items-end justify-end pr-7">
          <span className="text-[140px] font-cmu-serif text-text-color opacity-[0.04] leading-none">
            π
          </span>
        </div>
      </div>
      <div className="relative flex items-center gap-8">
        <div className="flex-1 min-w-0 space-y-3">
          <p className="text-[11px] font-comfortaa-bold text-teacher-color uppercase tracking-widest">
            Bonjour {firstName} 👋
          </p>
          <h1 className="text-2xl sm:text-3xl font-comfortaa-bold text-text-color">{message}</h1>
          {!allClear && (
            <Link
              href={route('teacher.corrections.index')}
              className="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-teacher-color text-white text-sm font-comfortaa-bold shadow-warm-xs hover:opacity-90 transition-opacity"
            >
              Traiter les corrections <ChevronRight size={14} />
            </Link>
          )}
        </div>
        <div className="hidden sm:flex flex-col items-end gap-3 shrink-0 pr-5">
          {stats.map((stat) => (
            <div key={stat.label} className="text-right">
              <p className="text-2xl font-cmu-serif text-text-color leading-none">{stat.value}</p>
              <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest mt-0.5">
                {stat.label}
              </p>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
