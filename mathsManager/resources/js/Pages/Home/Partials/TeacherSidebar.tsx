import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';

interface Props {
  corrCount: number;
  unlockCount: number;
}

export default function TeacherSidebar({ corrCount, unlockCount }: Props) {
  return (
    <aside className="space-y-4 lg:sticky lg:top-6">
      <div className="bg-secondary-color border border-border-color rounded-2xl p-4 space-y-4">
        <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest">
          En attente
        </p>
        <div className="space-y-3">
          <div>
            <span className="text-4xl font-cmu-serif text-text-color leading-none">
              {corrCount}
            </span>
            <p className="text-xs text-text-gray mt-0.5">
              copie{corrCount > 1 ? 's' : ''} à corriger
            </p>
          </div>
          {unlockCount > 0 && (
            <div>
              <span className="text-4xl font-cmu-serif text-text-color leading-none">
                {unlockCount}
              </span>
              <p className="text-xs text-text-gray mt-0.5">déblocage{unlockCount > 1 ? 's' : ''}</p>
            </div>
          )}
        </div>
        <Link
          href={route('teacher.corrections.index')}
          className="flex items-center gap-1 text-xs font-comfortaa-bold text-teacher-color hover:underline"
        >
          Voir les corrections <ChevronRight size={12} />
        </Link>
      </div>
      <div className="bg-secondary-color border border-border-color rounded-2xl p-4">
        <Link
          href={route('teacher.bureau.index')}
          className="flex items-center justify-between group"
        >
          <div>
            <p className="text-sm font-comfortaa-bold text-text-color">Mon Bureau</p>
            <p className="text-xs text-text-gray mt-0.5">Devoirs, modèles, élèves</p>
          </div>
          <ChevronRight
            size={14}
            className="text-text-gray group-hover:text-teacher-color transition-colors"
          />
        </Link>
      </div>
    </aside>
  );
}
