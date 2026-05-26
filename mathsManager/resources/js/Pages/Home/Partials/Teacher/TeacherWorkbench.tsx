import { Link } from '@inertiajs/react';
import { ChevronRight, Users } from 'lucide-react';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
import { BATCH_SHORTCUTS } from '@/Pages/Home/Partials/Teacher/teacherHomeData';

interface Props {
  activeBatches?: { ds: number; dm: number; td: number };
}

export default function TeacherWorkbench({ activeBatches }: Props) {
  return (
    <div className="space-y-4">
      <Link
        href={route('teacher.bureau.devoirs')}
        className="block bg-secondary-color border border-border-color rounded-2xl overflow-hidden hover:shadow-warm-sm transition-all animate-fadeInUp group"
      >
        <div className="flex items-center justify-between px-4 py-3 border-b border-border-color">
          <div>
            <span className="text-xs font-comfortaa-bold text-text-color uppercase tracking-wider">
              Devoirs envoyés
            </span>
            <p className="mt-0.5 text-[11px] text-text-gray">Suivi par batch DS, DM et TD</p>
          </div>
          <ChevronRight
            size={14}
            className="text-text-gray group-hover:text-teacher-color transition-colors"
          />
        </div>
        <div className="grid grid-cols-3 divide-x divide-border-color">
          {BATCH_SHORTCUTS.map(({ type, label }) => (
            <div key={type} className="flex items-center justify-center gap-3 py-4">
              <TypeBadge type={type} size="md" />
              <div>
                <span className="font-cmu-serif text-3xl text-text-color leading-none">
                  {activeBatches?.[type] ?? 0}
                </span>
                <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest">
                  {label}
                </p>
              </div>
            </div>
          ))}
        </div>
      </Link>

      <Link
        href={route('teacher.students.index')}
        className="flex items-center justify-between gap-4 bg-secondary-color border border-border-color rounded-2xl px-4 py-3 hover:shadow-warm-sm transition-all animate-fadeInUp group"
        style={{ animationDelay: '80ms' }}
      >
        <div className="flex items-center gap-3 min-w-0">
          <div className="w-10 h-10 rounded-2xl bg-teacher-color/10 border border-teacher-color/20 flex items-center justify-center shrink-0">
            <Users size={16} className="text-teacher-color" />
          </div>
          <div className="min-w-0">
            <p className="text-sm font-comfortaa-bold text-text-color">Mes élèves</p>
            <p className="text-xs text-text-gray truncate">Groupes, invitations et suivi rapide</p>
          </div>
        </div>
        <ChevronRight
          size={14}
          className="text-text-gray group-hover:text-teacher-color transition-colors shrink-0"
        />
      </Link>
    </div>
  );
}
