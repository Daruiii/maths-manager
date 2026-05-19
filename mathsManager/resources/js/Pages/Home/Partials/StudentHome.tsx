import { Link } from '@inertiajs/react';
import { BookOpen, ChevronRight } from 'lucide-react';
import { useAuth } from '@/Hooks/Auth/useAuth';
import StudentSidebar from '@/Pages/Home/Partials/StudentSidebar';
import AssignmentItem, { FlatItem } from '@/Pages/Home/Partials/AssignmentItem';
import type { HomeActiveAssignment } from '@/types';

interface Props {
  activeAssignments?: {
    ds: HomeActiveAssignment[];
    dm: HomeActiveAssignment[];
    td: HomeActiveAssignment[];
  };
  averageGrade?: number | null;
  correctedCount?: number;
}

const STATUS_PRIORITY: Record<string, number> = {
  ongoing: 0,
  paused: 1,
  not_started: 2,
  sent: 3,
  correction_requested: 4,
};

function buildSortedItems(
  ds: HomeActiveAssignment[],
  dm: HomeActiveAssignment[],
  td: HomeActiveAssignment[]
): FlatItem[] {
  return [
    ...ds.map((i) => ({ ...i, type: 'ds' as const, href: route('ds.show', i.id) })),
    ...dm.map((i) => ({ ...i, type: 'dm' as const, href: route('dm.show', i.id) })),
    ...td.map((i) => ({ ...i, type: 'td' as const, href: route('td.show', i.id) })),
  ].sort((a, b) => {
    const statusDiff = (STATUS_PRIORITY[a.status] ?? 9) - (STATUS_PRIORITY[b.status] ?? 9);
    if (statusDiff !== 0) return statusDiff;
    if (a.due_date && b.due_date)
      return (
        new Date(`${a.due_date}T00:00:00`).getTime() - new Date(`${b.due_date}T00:00:00`).getTime()
      );
    if (a.due_date) return -1;
    if (b.due_date) return 1;
    return 0;
  });
}

export default function StudentHome({
  activeAssignments,
  averageGrade,
  correctedCount = 0,
}: Props) {
  const { user } = useAuth();
  const firstName = user?.first_name ?? '';

  const ds = activeAssignments?.ds ?? [];
  const dm = activeAssignments?.dm ?? [];
  const td = activeAssignments?.td ?? [];
  const allItems = buildSortedItems(ds, dm, td);
  const total = allItems.length;
  const ongoing = allItems.filter((i) => i.status === 'ongoing' || i.status === 'paused').length;
  const nextDue = allItems.find((i) => i.due_date);

  const heroMessage =
    total === 0
      ? 'Tout est à jour !'
      : ongoing > 0
        ? 'Continue sur ta lancée.'
        : "Du travail t'attend.";

  return (
    <div className="space-y-6">
      {/* ── Hero ── */}
      <div className="relative mm-card mm-card-style-halo mm-card-accent-student rounded-3xl px-6 py-8 overflow-hidden animate-fadeIn">
        <div
          className="absolute right-7 top-1/2 -translate-y-1/2 pointer-events-none select-none"
          aria-hidden
        >
          <span className="text-[96px] font-cmu-serif text-text-color opacity-[0.04] leading-none">
            ∫
          </span>
        </div>
        <div className="relative space-y-4 max-w-lg">
          <p className="text-[11px] font-comfortaa-bold text-student-color uppercase tracking-widest">
            Bonjour {firstName} 👋
          </p>
          <div>
            <h1 className="text-2xl sm:text-3xl font-comfortaa-bold text-text-color">
              {heroMessage}
            </h1>
            {total > 0 && (
              <p className="text-sm text-text-gray mt-1">
                {ds.length > 0 && `${ds.length} DS`}
                {ds.length > 0 && (dm.length > 0 || td.length > 0) && ', '}
                {dm.length > 0 && `${dm.length} DM`}
                {dm.length > 0 && td.length > 0 && ', '}
                {td.length > 0 && `${td.length} TD`}
                {total > 0 && ' en cours.'}
              </p>
            )}
          </div>
          {total > 0 && (
            <Link
              href={nextDue?.href ?? route('student.ressources')}
              className="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-student-color text-white text-sm font-comfortaa-bold shadow-warm-xs hover:opacity-90 transition-opacity"
            >
              Continuer mon travail
              <ChevronRight size={14} />
            </Link>
          )}
        </div>
      </div>

      {/* ── 2-col grid ── */}
      <div className="grid grid-cols-1 lg:grid-cols-[1fr_260px] gap-6 items-start">
        <div>
          {total === 0 ? (
            <div className="flex flex-col items-center gap-3 py-12 text-center animate-fadeInUp">
              <div className="w-12 h-12 rounded-2xl bg-surface-color flex items-center justify-center shadow-warm-xs">
                <BookOpen size={22} className="text-text-gray opacity-60" />
              </div>
              <p className="text-sm text-text-gray">Aucun devoir en cours pour l&apos;instant.</p>
            </div>
          ) : (
            <div className="mm-card mm-card-style-plain overflow-hidden">
              <div className="flex items-center justify-between px-5 py-4 border-b border-border-color">
                <span className="text-xs font-comfortaa-bold text-text-color uppercase tracking-wider">
                  À faire maintenant
                </span>
                <span className="text-xs text-text-gray bg-surface-color border border-border-color px-2 py-0.5 rounded-full">
                  {total}
                </span>
              </div>
              <div className="p-3 space-y-2">
                {allItems.map((item, i) => (
                  <AssignmentItem key={`${item.type}-${item.id}`} item={item} index={i} />
                ))}
              </div>
            </div>
          )}
        </div>

        <StudentSidebar averageGrade={averageGrade} correctedCount={correctedCount} />
      </div>
    </div>
  );
}
