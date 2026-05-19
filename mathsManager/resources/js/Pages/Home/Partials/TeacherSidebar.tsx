import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';

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
        <Link
          href={route('teacher.bureau.index')}
          className="flex items-center justify-between group pt-1"
        >
          <div>
            <p className="text-sm font-comfortaa-bold text-text-color">Mon Bureau</p>
            <p className="mm-stat-label mt-0.5">Devoirs, modèles, élèves</p>
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
