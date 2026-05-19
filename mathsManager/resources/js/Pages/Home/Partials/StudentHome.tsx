import { useState, useRef } from 'react';
import { createPortal } from 'react-dom';
import { Link } from '@inertiajs/react';
import { ChevronRight, ChevronDown } from 'lucide-react';
import { useAuth } from '@/Hooks/Auth/useAuth';
import StudentSidebar from '@/Pages/Home/Partials/StudentSidebar';
import { type FlatItem, formatDueDate } from '@/Pages/Home/Partials/AssignmentItem';
import UrgentAssignmentCard from '@/Pages/Home/Partials/UrgentAssignmentCard';
import type { HomeActiveAssignment } from '@/types';

const TYPE_COLOR: Record<string, string> = {
  ds: 'text-tertiary-color',
  dm: 'text-admin-color',
  td: 'text-info-color',
};

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
    const s = (STATUS_PRIORITY[a.status] ?? 9) - (STATUS_PRIORITY[b.status] ?? 9);
    if (s !== 0) return s;
    if (a.due_date && b.due_date)
      return (
        new Date(`${a.due_date}T00:00:00`).getTime() - new Date(`${b.due_date}T00:00:00`).getTime()
      );
    return a.due_date ? -1 : b.due_date ? 1 : 0;
  });
}

interface Props {
  activeAssignments?: {
    ds: HomeActiveAssignment[];
    dm: HomeActiveAssignment[];
    td: HomeActiveAssignment[];
  };
  averageGrade?: number | null;
  correctedCount?: number;
}

export default function StudentHome({
  activeAssignments,
  averageGrade,
  correctedCount = 0,
}: Props) {
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const [dropdownPos, setDropdownPos] = useState<{ top: number; left: number } | null>(null);
  const dropdownTimer = useRef<ReturnType<typeof setTimeout> | null>(null);
  const ctaRef = useRef<HTMLDivElement>(null);
  const { user } = useAuth();
  const firstName = user?.first_name ?? '';

  const ds = activeAssignments?.ds ?? [];
  const dm = activeAssignments?.dm ?? [];
  const td = activeAssignments?.td ?? [];
  const allItems = buildSortedItems(ds, dm, td);
  const total = allItems.length;
  const ongoingCount = allItems.filter((i) => ['ongoing', 'paused'].includes(i.status)).length;
  const toDoCount = allItems.filter(
    (i) => !['sent', 'correction_requested'].includes(i.status)
  ).length;

  const dropdownItems = allItems
    .filter((i) => !['sent', 'correction_requested'].includes(i.status))
    .slice(0, 5);
  const showDropdown = dropdownItems.length > 1;
  const firstAction = dropdownItems[0];
  const ctaHref = firstAction?.href ?? route('student.assignments.index');
  const ctaLabel = !firstAction
    ? 'Voir mes devoirs'
    : ['ongoing', 'paused'].includes(firstAction.status)
      ? 'Reprendre'
      : 'Commencer';

  const displayItems = allItems.slice(0, 4);
  const remainingCount = Math.max(0, total - 4);

  const nextDueItem = allItems.find(
    (i) => i.due_date && !['sent', 'correction_requested'].includes(i.status)
  );
  const dueFmt = nextDueItem ? formatDueDate(nextDueItem.due_date) : null;
  const nextDue =
    nextDueItem && dueFmt
      ? {
          label: dueFmt.label,
          title: nextDueItem.title,
          type: nextDueItem.type,
          href: nextDueItem.href,
          urgent: dueFmt.urgent,
        }
      : null;

  const heroMessage =
    total === 0
      ? 'Tout est à jour !'
      : ongoingCount > 0
        ? 'Continue sur ta lancée.'
        : "Du travail t'attend.";

  const openDd = () => {
    if (ctaRef.current) {
      const rect = ctaRef.current.getBoundingClientRect();
      setDropdownPos({ top: rect.bottom + 4, left: rect.left });
    }
    if (dropdownTimer.current) clearTimeout(dropdownTimer.current);
    setDropdownOpen(true);
  };
  const closeDd = () => {
    dropdownTimer.current = setTimeout(() => setDropdownOpen(false), 150);
  };

  return (
    <div className="space-y-6">
      {/* ── Hero ── */}
      <div className="relative mm-card mm-card-style-halo mm-card-accent-student rounded-3xl px-8 py-6 animate-fadeIn overflow-hidden">
        <div
          className="absolute inset-0 overflow-hidden rounded-3xl pointer-events-none select-none"
          aria-hidden
        >
          <div className="absolute inset-0 flex items-end justify-end pr-7">
            <span className="text-[140px] font-cmu-serif text-text-color opacity-[0.04] leading-none">
              ∫
            </span>
          </div>
        </div>

        <div className="relative flex items-center gap-8">
          {/* Left */}
          <div className="flex-1 min-w-0 space-y-3">
            <p className="text-[11px] font-comfortaa-bold text-student-color uppercase tracking-widest">
              Bonjour {firstName} 👋
            </p>
            <h1 className="text-2xl sm:text-3xl font-comfortaa-bold text-text-color">
              {heroMessage}
            </h1>
            {total > 0 && (
              <div
                ref={ctaRef}
                className="inline-block"
                onMouseEnter={openDd}
                onMouseLeave={closeDd}
              >
                <Link
                  href={ctaHref}
                  className="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-student-color text-white text-sm font-comfortaa-bold shadow-warm-xs hover:opacity-90 transition-opacity"
                >
                  {ctaLabel} {showDropdown ? <ChevronDown size={14} /> : <ChevronRight size={14} />}
                </Link>
              </div>
            )}
          </div>

          {/* Right: stats */}
          {total > 0 && (
            <div className="hidden sm:flex flex-col items-end gap-3 shrink-0 pr-5">
              <div className="text-right">
                <p className="text-2xl font-cmu-serif text-text-color leading-none">{toDoCount}</p>
                <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest mt-0.5">
                  à faire
                </p>
              </div>
              <div className="text-right">
                <p className="text-2xl font-cmu-serif text-text-color leading-none">
                  {ongoingCount}
                </p>
                <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest mt-0.5">
                  en cours
                </p>
              </div>
              {correctedCount > 0 && (
                <div className="text-right">
                  <p className="text-2xl font-cmu-serif text-text-color leading-none">
                    {correctedCount}
                  </p>
                  <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest mt-0.5">
                    corrigé{correctedCount > 1 ? 's' : ''}
                  </p>
                </div>
              )}
            </div>
          )}
        </div>
      </div>

      {/* Portal dropdown — rendered at body level to escape any overflow constraint */}
      {dropdownOpen &&
        showDropdown &&
        dropdownPos &&
        createPortal(
          <div
            style={{
              position: 'fixed',
              top: dropdownPos.top,
              left: dropdownPos.left,
              zIndex: 9999,
              width: 256,
            }}
            className="bg-secondary-color border border-border-color rounded-xl shadow-warm-sm py-1 animate-fadeInUp"
            onMouseEnter={openDd}
            onMouseLeave={closeDd}
          >
            {dropdownItems.map((item) => (
              <Link
                key={item.href}
                href={item.href}
                className="flex items-center gap-2 px-3 py-2.5 hover:bg-surface-color transition-colors"
              >
                <span
                  className={`text-[10px] font-comfortaa-bold uppercase tracking-widest shrink-0 ${TYPE_COLOR[item.type]}`}
                >
                  {item.type.toUpperCase()}
                </span>
                <span className="flex-1 text-sm font-comfortaa-bold text-text-color truncate">
                  {item.title}
                </span>
                <ChevronRight size={12} className="text-text-gray shrink-0" />
              </Link>
            ))}
            <div className="border-t border-border-color mt-1 pt-1">
              <Link
                href={route('student.assignments.index')}
                className="flex items-center gap-2 px-3 py-2.5 hover:bg-surface-color transition-colors"
              >
                <span className="flex-1 text-xs font-comfortaa-bold text-text-gray">
                  Voir tous mes travaux
                </span>
                <ChevronRight size={12} className="text-text-gray shrink-0" />
              </Link>
            </div>
          </div>,
          document.body
        )}

      {/* ── Main + Sidebar ── */}
      <div className="grid grid-cols-1 lg:grid-cols-[1fr_260px] gap-6 items-start">
        <div className="space-y-4">
          {total === 0 ? (
            <div className="relative mm-card mm-card-style-halo mm-card-accent-student rounded-2xl py-12 flex flex-col items-center gap-4 text-center overflow-hidden animate-fadeInUp">
              <div
                className="absolute inset-0 flex items-center justify-center pointer-events-none"
                aria-hidden
              >
                <span className="text-[100px] font-cmu-serif text-student-color opacity-[0.04] leading-none">
                  ∞
                </span>
              </div>
              <div className="relative space-y-1">
                <p className="text-sm font-comfortaa-bold text-text-color">Aucun devoir en cours</p>
                <p className="text-xs text-text-gray">
                  Tu es à jour — profite pour explorer les ressources.
                </p>
              </div>
              <Link
                href={route('student.ressources')}
                className="relative inline-flex items-center gap-1.5 text-xs font-comfortaa-bold text-student-color border border-student-color/30 bg-student-color/5 hover:bg-student-color/10 rounded-xl px-3 py-1.5 transition-colors"
              >
                Explorer les ressources <ChevronRight size={12} />
              </Link>
            </div>
          ) : (
            <>
              <div className="grid grid-cols-2 gap-3 items-stretch">
                {displayItems.map((item, i) => (
                  <UrgentAssignmentCard
                    key={`${item.type}-${item.id}`}
                    item={item}
                    index={i}
                    tile
                  />
                ))}
              </div>
              {remainingCount > 0 && (
                <Link
                  href={route('student.assignments.index')}
                  className="flex items-center justify-center gap-1.5 text-xs font-comfortaa-bold text-text-gray hover:text-text-color transition-colors py-1"
                >
                  + {remainingCount} autre{remainingCount > 1 ? 's' : ''} devoir
                  {remainingCount > 1 ? 's' : ''} <ChevronRight size={11} />
                </Link>
              )}
              <div className="relative overflow-hidden mm-card mm-card-style-corner mm-card-accent-student rounded-2xl px-4 py-3.5 space-y-1.5">
                <div
                  className="absolute -right-3 -bottom-3 pointer-events-none select-none"
                  aria-hidden
                >
                  <span className="text-[56px] font-cmu-serif text-student-color opacity-[0.06] leading-none">
                    ∑
                  </span>
                </div>
                <div className="relative flex items-center gap-2">
                  <span className="font-cmu-serif text-student-color text-sm leading-none">✦</span>
                  <p className="text-xs font-comfortaa-bold text-text-color">
                    Parcours IA personnalisés
                  </p>
                  <span className="ml-auto text-[9px] font-comfortaa-bold text-student-color uppercase tracking-widest border border-student-color/30 bg-student-color/5 px-1.5 py-0.5 rounded-md shrink-0">
                    Bientôt
                  </span>
                </div>
                <p className="relative text-[10px] text-text-gray leading-relaxed pr-10">
                  Exercices adaptés à tes résultats DS, progression débloquée étape par étape.
                </p>
              </div>
            </>
          )}
        </div>

        <StudentSidebar averageGrade={averageGrade} nextDue={nextDue} />
      </div>
    </div>
  );
}
