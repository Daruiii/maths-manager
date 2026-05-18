import { Link } from '@inertiajs/react';
import { ClipboardCheck, CheckCircle2, Unlock, ChevronRight, BookOpen, Bell } from 'lucide-react';
import type { HomePendingCorrectionItem, HomeUnlockRequestItem } from '@/types';

function timeAgo(dateStr: string): string {
  const diff = Date.now() - new Date(dateStr).getTime();
  const mins = Math.floor(diff / 60000);
  if (mins < 1) return 'maintenant';
  if (mins < 60) return `il y a ${mins} min`;
  const hrs = Math.floor(mins / 60);
  if (hrs < 24) return `il y a ${hrs}h`;
  return `il y a ${Math.floor(hrs / 24)}j`;
}

interface Props {
  pendingCorrections?: { count: number; items: HomePendingCorrectionItem[] };
  unlockRequests?: { count: number; items: HomeUnlockRequestItem[] };
  pendingTeachersCount?: number;
}

function UrgenceCard({
  icon: Icon,
  label,
  count,
  href,
  color,
}: {
  icon: typeof ClipboardCheck;
  label: string;
  count: number;
  href: string;
  color: 'teacher' | 'student';
}) {
  const colorMap = {
    teacher: { bg: 'bg-teacher-color/10', text: 'text-teacher-color', badge: 'bg-teacher-color' },
    student: { bg: 'bg-student-color/10', text: 'text-student-color', badge: 'bg-student-color' },
  };
  const c = colorMap[color];
  return (
    <Link
      href={href}
      className="flex items-center gap-3 p-4 bg-surface-color border border-border-color rounded-2xl hover:-translate-y-0.5 transition-transform"
    >
      <div className={`p-2.5 rounded-xl ${c.bg}`}>
        <Icon size={18} className={c.text} />
      </div>
      <div className="flex-1 min-w-0">
        <p className="text-sm font-comfortaa-bold text-text-color">{label}</p>
      </div>
      {count > 0 ? (
        <span
          className={`min-w-[22px] h-[22px] px-1.5 ${c.badge} text-white text-xs font-comfortaa-bold rounded-full flex items-center justify-center`}
        >
          {count > 99 ? '99+' : count}
        </span>
      ) : (
        <span className="text-xs text-success-color font-comfortaa-bold">✓</span>
      )}
    </Link>
  );
}

function CorrectionRow({ item }: { item: HomePendingCorrectionItem }) {
  return (
    <Link
      href={route('teacher.corrections.show', item.id)}
      className="flex items-center gap-3 px-3 py-2.5 hover:bg-surface-color rounded-xl transition-colors group"
    >
      <span
        className={`text-[10px] font-comfortaa-bold px-1.5 py-0.5 rounded-full uppercase ${
          item.subject_type === 'ds'
            ? 'bg-teacher-color/10 text-teacher-color'
            : 'bg-tertiary-color/10 text-tertiary-color'
        }`}
      >
        {item.subject_type.toUpperCase()}
      </span>
      <div className="flex-1 min-w-0">
        <p className="text-sm font-comfortaa-bold text-text-color truncate">{item.student_name}</p>
        <p className="text-xs text-text-gray truncate">{item.subject_title}</p>
      </div>
      <span className="text-[10px] text-text-gray/60 shrink-0">{timeAgo(item.created_at)}</span>
      <ChevronRight size={14} className="text-text-gray group-hover:text-text-color shrink-0" />
    </Link>
  );
}

function UnlockRow({ item }: { item: HomeUnlockRequestItem }) {
  return (
    <div className="flex items-center gap-3 px-3 py-2.5 hover:bg-surface-color rounded-xl transition-colors">
      <div className={`p-1.5 rounded-lg bg-student-color/10`}>
        <BookOpen size={13} className="text-student-color" />
      </div>
      <div className="flex-1 min-w-0">
        <p className="text-sm font-comfortaa-bold text-text-color truncate">{item.student_name}</p>
        <p className="text-xs text-text-gray truncate">{item.title}</p>
      </div>
      <span className="text-[10px] text-text-gray/60 shrink-0">{timeAgo(item.updated_at)}</span>
      <Link
        href={route('teacher.corrections.index')}
        className="text-xs font-comfortaa-bold text-student-color hover:underline shrink-0"
      >
        Débloquer
      </Link>
    </div>
  );
}

export default function TeacherHome({
  pendingCorrections,
  unlockRequests,
  pendingTeachersCount,
}: Props) {
  const corrCount = pendingCorrections?.count ?? 0;
  const unlockCount = unlockRequests?.count ?? 0;
  const allClear = corrCount === 0 && unlockCount === 0;

  return (
    <div className="space-y-6">
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
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <UrgenceCard
          icon={ClipboardCheck}
          label="Corrections à envoyer"
          count={corrCount}
          href={route('teacher.corrections.index')}
          color="teacher"
        />
        <UrgenceCard
          icon={Unlock}
          label="Déblocages TD demandés"
          count={unlockCount}
          href={route('teacher.corrections.index')}
          color="student"
        />
      </div>

      {allClear && (
        <div className="flex items-center gap-3 px-4 py-3 bg-success-color/10 border border-success-color/20 rounded-2xl">
          <CheckCircle2 size={16} className="text-success-color shrink-0" />
          <p className="text-sm font-comfortaa-bold text-success-color">
            Tout est à jour — aucune urgence en attente.
          </p>
        </div>
      )}

      {corrCount > 0 && (
        <div className="bg-secondary-color border border-border-color rounded-2xl overflow-hidden">
          <div className="flex items-center justify-between px-4 py-3 border-b border-border-color">
            <span className="text-xs font-comfortaa-bold text-text-color uppercase tracking-wider">
              Copies à corriger
            </span>
            <span className="text-xs font-comfortaa-bold text-teacher-color bg-teacher-color/10 px-2 py-0.5 rounded-full">
              {corrCount}
            </span>
          </div>
          <div className="p-2 space-y-0.5">
            {pendingCorrections!.items.map((item) => (
              <CorrectionRow key={item.id} item={item} />
            ))}
          </div>
          {corrCount > 5 && (
            <div className="px-4 py-2.5 border-t border-border-color">
              <Link
                href={route('teacher.corrections.index')}
                className="text-xs font-comfortaa-bold text-teacher-color hover:underline flex items-center gap-1"
              >
                Voir toutes ({corrCount})
                <ChevronRight size={12} />
              </Link>
            </div>
          )}
        </div>
      )}

      {unlockCount > 0 && (
        <div className="bg-secondary-color border border-border-color rounded-2xl overflow-hidden">
          <div className="flex items-center justify-between px-4 py-3 border-b border-border-color">
            <span className="text-xs font-comfortaa-bold text-text-color uppercase tracking-wider">
              Déblocages demandés
            </span>
            <span className="text-xs font-comfortaa-bold text-student-color bg-student-color/10 px-2 py-0.5 rounded-full">
              {unlockCount}
            </span>
          </div>
          <div className="p-2 space-y-0.5">
            {unlockRequests!.items.map((item) => (
              <UnlockRow key={item.id} item={item} />
            ))}
          </div>
          {unlockCount > 5 && (
            <div className="px-4 py-2.5 border-t border-border-color">
              <Link
                href={route('teacher.corrections.index')}
                className="text-xs font-comfortaa-bold text-student-color hover:underline flex items-center gap-1"
              >
                Voir toutes ({unlockCount})
                <ChevronRight size={12} />
              </Link>
            </div>
          )}
        </div>
      )}
    </div>
  );
}
