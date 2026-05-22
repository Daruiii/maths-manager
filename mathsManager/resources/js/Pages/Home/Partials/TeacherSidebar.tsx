import { Link } from '@inertiajs/react';
import { ChevronRight, Users } from 'lucide-react';

interface Props {
  assignedThisMonth?: number;
}

export default function TeacherSidebar({ assignedThisMonth }: Props) {
  const count = assignedThisMonth ?? 0;

  return (
    <aside className="space-y-4 lg:sticky lg:top-6">
      <div className="bg-secondary-color border border-border-color rounded-2xl p-4 space-y-4">
        <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest">
          Ce mois
        </p>
        <div>
          <span className="text-4xl font-cmu-serif text-text-color leading-none">{count}</span>
          <p className="mm-stat-label">
            devoir{count > 1 ? 's' : ''} assigné{count > 1 ? 's' : ''}
          </p>
        </div>
        <div className="border-t border-border-color/60 pt-3 space-y-0.5">
          <Link
            href={route('teacher.bureau.index')}
            className="flex items-center justify-between group py-2 px-2 rounded-xl hover:bg-surface-color transition-colors"
          >
            <div>
              <p className="text-sm font-comfortaa-bold text-text-color">Mon Bureau</p>
              <p className="mm-stat-label mt-0.5">Devoirs, ressources, modèles</p>
            </div>
            <ChevronRight
              size={14}
              className="text-text-gray group-hover:text-teacher-color transition-colors shrink-0"
            />
          </Link>
          <Link
            href={route('teacher.students.index')}
            className="flex items-center justify-between group py-2 px-2 rounded-xl hover:bg-surface-color transition-colors"
          >
            <div className="flex items-center gap-2.5">
              <Users size={13} className="text-text-gray shrink-0" />
              <p className="text-sm font-comfortaa-bold text-text-color">Mes élèves</p>
            </div>
            <ChevronRight
              size={14}
              className="text-text-gray group-hover:text-teacher-color transition-colors shrink-0"
            />
          </Link>
        </div>
      </div>
    </aside>
  );
}
