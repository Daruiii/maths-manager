import { Link } from '@inertiajs/react';
import { ChevronRight, CheckCircle2, Bell } from 'lucide-react';
import { useAuth } from '@/Hooks/Auth/useAuth';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
import TeacherSidebar from '@/Pages/Home/Partials/TeacherSidebar';
import type { HomePendingCorrectionItem, HomeUnlockRequestItem } from '@/types';

// ─── Types ────────────────────────────────────────────────────────────────────

interface Props {
  pendingCorrections?: { count: number; items: HomePendingCorrectionItem[] };
  unlockRequests?: { count: number; items: HomeUnlockRequestItem[] };
  pendingTeachersCount?: number;
}

// ─── Helpers ──────────────────────────────────────────────────────────────────

function timeAgo(dateStr: string): string {
  const diff = Date.now() - new Date(dateStr).getTime();
  const mins = Math.floor(diff / 60000);
  if (mins < 1) return 'maintenant';
  if (mins < 60) return `il y a ${mins} min`;
  const hrs = Math.floor(mins / 60);
  if (hrs < 24) return `il y a ${hrs}h`;
  return `il y a ${Math.floor(hrs / 24)}j`;
}

function CorrectionItem({ item }: { item: HomePendingCorrectionItem }) {
  return (
    <Link
      href={route('teacher.corrections.show', item.id)}
      className="flex items-center gap-3 px-3 py-3 hover:bg-surface-color rounded-xl transition-colors group"
    >
      <TypeBadge type={item.subject_type} size="md" />
      <div className="flex-1 min-w-0">
        <p className="text-sm font-comfortaa-bold text-text-color truncate">{item.student_name}</p>
        <p className="text-xs text-text-gray truncate">{item.subject_title}</p>
      </div>
      <span className="text-[10px] text-text-gray/60 shrink-0">{timeAgo(item.created_at)}</span>
      <ChevronRight size={14} className="text-text-gray group-hover:text-text-color shrink-0" />
    </Link>
  );
}

function UnlockItem({ item }: { item: HomeUnlockRequestItem }) {
  return (
    <div className="flex items-center gap-3 px-3 py-3 hover:bg-surface-color rounded-xl transition-colors">
      <TypeBadge type="td" size="md" />
      <div className="flex-1 min-w-0">
        <p className="text-sm font-comfortaa-bold text-text-color truncate">{item.student_name}</p>
        <p className="text-xs text-text-gray truncate">{item.title}</p>
      </div>
      <span className="text-[10px] text-text-gray/60 shrink-0">{timeAgo(item.updated_at)}</span>
      <Link
        href={route('teacher.corrections.index')}
        className="text-xs font-comfortaa-bold text-info-color hover:underline shrink-0"
      >
        Examiner
      </Link>
    </div>
  );
}

// ─── Page ─────────────────────────────────────────────────────────────────────

export default function TeacherHome({
  pendingCorrections,
  unlockRequests,
  pendingTeachersCount,
}: Props) {
  const { user } = useAuth();
  const firstName = user?.first_name ?? '';
  const corrCount = pendingCorrections?.count ?? 0;
  const unlockCount = unlockRequests?.count ?? 0;
  const pendingTotal = corrCount + unlockCount;
  const allClear = pendingTotal === 0;

  const heroMessage = allClear
    ? 'Tout est à jour.'
    : `${pendingTotal} action${pendingTotal > 1 ? 's' : ''} en attente.`;

  return (
    <div className="space-y-6">
      {/* ── Admin banner ── */}
      {!!pendingTeachersCount && pendingTeachersCount > 0 && (
        <Link
          href={route('admin.applications.index')}
          className="flex items-center justify-between gap-4 px-4 py-3 bg-admin-color/10 border border-admin-color/20 rounded-2xl hover:bg-admin-color/15 transition-colors"
        >
          <div className="flex items-center gap-3">
            <Bell size={16} className="text-admin-color shrink-0" />
            <p className="text-sm font-comfortaa-bold text-admin-color">
              {pendingTeachersCount} candidature{pendingTeachersCount > 1 ? 's' : ''} professeur
              {pendingTeachersCount > 1 ? 's' : ''} en attente
            </p>
          </div>
          <ChevronRight size={14} className="text-admin-color shrink-0" />
        </Link>
      )}

      {/* ── Hero ── */}
      <div className="relative bg-secondary-color border border-border-color rounded-3xl px-6 py-8 overflow-hidden">
        <div className="absolute inset-0 bg-teacher-color opacity-[0.03] rounded-3xl pointer-events-none" />
        <div
          className="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none select-none"
          aria-hidden
        >
          <span className="text-[96px] font-cmu-serif text-text-color opacity-[0.04] leading-none">
            π
          </span>
        </div>
        <div className="relative space-y-4 max-w-lg">
          <p className="text-[11px] font-comfortaa-bold text-teacher-color uppercase tracking-widest">
            Bonjour {firstName} 👋
          </p>
          <h1 className="text-2xl sm:text-3xl font-comfortaa-bold text-text-color">
            {heroMessage}
          </h1>
          {!allClear && (
            <p className="text-sm text-text-gray">
              {corrCount > 0 && `${corrCount} copie${corrCount > 1 ? 's' : ''} à corriger`}
              {corrCount > 0 && unlockCount > 0 && ' · '}
              {unlockCount > 0 &&
                `${unlockCount} déblocage${unlockCount > 1 ? 's' : ''} demandé${unlockCount > 1 ? 's' : ''}`}
            </p>
          )}
          <Link
            href={route('teacher.corrections.index')}
            className="inline-flex items-center gap-1.5 text-sm font-comfortaa-bold text-teacher-color hover:underline"
          >
            Voir les corrections
            <ChevronRight size={14} />
          </Link>
        </div>
      </div>

      {/* ── 2-col grid ── */}
      <div className="grid grid-cols-1 lg:grid-cols-[1fr_260px] gap-6 items-start">
        <div className="space-y-4">
          {allClear ? (
            <div className="flex items-center gap-3 px-4 py-3 bg-success-color/10 border border-success-color/20 rounded-2xl">
              <CheckCircle2 size={16} className="text-success-color shrink-0" />
              <p className="text-sm font-comfortaa-bold text-success-color">
                Aucune urgence en attente — beau travail.
              </p>
            </div>
          ) : (
            <div className="bg-secondary-color border border-border-color rounded-2xl overflow-hidden">
              <div className="flex items-center justify-between px-4 py-3 border-b border-border-color">
                <span className="text-xs font-comfortaa-bold text-text-color uppercase tracking-wider">
                  À traiter
                </span>
                <span className="text-xs text-text-gray bg-surface-color border border-border-color px-2 py-0.5 rounded-full">
                  {pendingTotal}
                </span>
              </div>
              <div className="p-2 space-y-0.5">
                {pendingCorrections?.items.map((item) => (
                  <CorrectionItem key={item.id} item={item} />
                ))}
                {unlockRequests?.items.map((item) => (
                  <UnlockItem key={item.id} item={item} />
                ))}
              </div>
              {pendingTotal > 5 && (
                <div className="px-4 py-2.5 border-t border-border-color">
                  <Link
                    href={route('teacher.corrections.index')}
                    className="text-xs font-comfortaa-bold text-teacher-color hover:underline flex items-center gap-1"
                  >
                    Voir tout ({pendingTotal}) <ChevronRight size={12} />
                  </Link>
                </div>
              )}
            </div>
          )}
        </div>

        <TeacherSidebar corrCount={corrCount} unlockCount={unlockCount} />
      </div>
    </div>
  );
}
