import { Link } from '@inertiajs/react';
import { ChevronRight, BookOpen } from 'lucide-react';
import { useAuth } from '@/Hooks/Auth/useAuth';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
import { BATCH_STATUS_META } from '@/Constants/statuses';
import type { HomeActiveAssignment } from '@/types';

// ─── Types ────────────────────────────────────────────────────────────────────

interface Props {
  activeAssignments?: {
    ds: HomeActiveAssignment[];
    dm: HomeActiveAssignment[];
    td: HomeActiveAssignment[];
  };
  averageGrade?: number | null;
  correctedCount?: number;
}

type ItemType = 'ds' | 'dm' | 'td';

interface FlatItem extends HomeActiveAssignment {
  type: ItemType;
  href: string;
}

// ─── Helpers ──────────────────────────────────────────────────────────────────

const STATUS_PRIORITY: Record<string, number> = {
  ongoing: 0,
  paused: 1,
  not_started: 2,
  sent: 3,
  correction_requested: 4,
};

function Stat({ value, label, detail }: { value: number; label: string; detail?: string }) {
  return (
    <div className="flex items-end gap-1.5">
      <span className="text-2xl font-comfortaa-bold text-text-color leading-none">{value}</span>
      <div className="pb-0.5">
        <p className="text-xs font-comfortaa-bold text-text-color leading-none">{label}</p>
        {detail && <p className="text-[10px] text-text-gray leading-none mt-0.5">{detail}</p>}
      </div>
    </div>
  );
}

function AssignmentItem({ item }: { item: FlatItem }) {
  const meta = BATCH_STATUS_META[item.status] ?? BATCH_STATUS_META.not_started;
  return (
    <Link
      href={item.href}
      className="flex items-center gap-3 px-3 py-2.5 hover:bg-surface-color rounded-xl transition-colors group"
    >
      <TypeBadge type={item.type} />
      <p className="flex-1 min-w-0 text-sm font-comfortaa-bold text-text-color truncate">
        {item.title}
      </p>
      <span
        className={`text-[10px] font-comfortaa-bold px-2 py-0.5 rounded-full shrink-0 ${meta.classes}`}
      >
        {meta.label}
      </span>
      <ChevronRight size={14} className="text-text-gray group-hover:text-text-color shrink-0" />
    </Link>
  );
}

// ─── Page ─────────────────────────────────────────────────────────────────────

export default function StudentHome({ activeAssignments, correctedCount = 0 }: Props) {
  const { user } = useAuth();
  const firstName = user?.first_name ?? '';

  const ds = activeAssignments?.ds ?? [];
  const dm = activeAssignments?.dm ?? [];
  const td = activeAssignments?.td ?? [];

  const allItems: FlatItem[] = [
    ...ds.map((i) => ({ ...i, type: 'ds' as ItemType, href: route('ds.show', i.id) })),
    ...dm.map((i) => ({ ...i, type: 'dm' as ItemType, href: route('dm.show', i.id) })),
    ...td.map((i) => ({ ...i, type: 'td' as ItemType, href: route('td.show', i.id) })),
  ].sort((a, b) => (STATUS_PRIORITY[a.status] ?? 9) - (STATUS_PRIORITY[b.status] ?? 9));

  const total = allItems.length;
  const ongoing = allItems.filter((i) => i.status === 'ongoing' || i.status === 'paused').length;

  const heroMessage =
    total === 0
      ? 'Tout est à jour !'
      : ongoing > 0
        ? 'Continue sur ta lancée.'
        : "Du travail t'attend.";

  return (
    <div className="space-y-6">
      {/* ── Hero ── */}
      <div className="relative bg-secondary-color border border-border-color rounded-3xl px-6 py-8 overflow-hidden">
        <div className="absolute inset-0 bg-student-color opacity-[0.03] rounded-3xl pointer-events-none" />
        <div
          className="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none select-none"
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
            <h1 className="text-2xl font-comfortaa-bold text-text-color">{heroMessage}</h1>
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
          <div className="flex items-center gap-6">
            <Stat value={total} label="à faire" detail="DS · DM · TD" />
            {ongoing > 0 && <Stat value={ongoing} label="en cours" />}
            {correctedCount > 0 && <Stat value={correctedCount} label="corrigés" />}
          </div>
          {total > 0 && (
            <Link
              href={route('student.ressources')}
              className="inline-flex items-center gap-1.5 text-sm font-comfortaa-bold text-student-color hover:underline"
            >
              Voir tous mes travaux
              <ChevronRight size={14} />
            </Link>
          )}
        </div>
      </div>

      {/* ── Liste ── */}
      {total === 0 ? (
        <div className="flex flex-col items-center gap-3 py-12 text-center">
          <div className="w-12 h-12 rounded-2xl bg-surface-color flex items-center justify-center">
            <BookOpen size={22} className="text-text-gray opacity-60" />
          </div>
          <p className="text-sm text-text-gray">Aucun devoir en cours pour l&apos;instant.</p>
        </div>
      ) : (
        <div className="bg-secondary-color border border-border-color rounded-2xl overflow-hidden">
          <div className="flex items-center justify-between px-4 py-3 border-b border-border-color">
            <span className="text-xs font-comfortaa-bold text-text-color uppercase tracking-wider">
              À faire maintenant
            </span>
            <span className="text-xs text-text-gray bg-surface-color border border-border-color px-2 py-0.5 rounded-full">
              {total}
            </span>
          </div>
          <div className="p-2 space-y-0.5">
            {allItems.map((item) => (
              <AssignmentItem key={`${item.type}-${item.id}`} item={item} />
            ))}
          </div>
        </div>
      )}
    </div>
  );
}
