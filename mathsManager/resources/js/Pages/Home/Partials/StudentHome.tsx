import { Link } from '@inertiajs/react';
import { ChevronRight, BookOpen, FileText, ClipboardList, Star } from 'lucide-react';
import type { HomeActiveAssignment } from '@/types';

const STATUS_LABELS: Record<string, string> = {
  not_started: 'Non commencé',
  ongoing: 'En cours',
  paused: 'En pause',
  sent: 'Envoyé',
  correction_requested: 'Correction demandée',
};

const STATUS_COLORS: Record<string, string> = {
  not_started: 'bg-surface-color text-text-gray',
  ongoing: 'bg-info-color/10 text-info-color',
  paused: 'bg-warning-color/10 text-warning-color',
  sent: 'bg-success-color/10 text-success-color',
  correction_requested: 'bg-tertiary-color/10 text-tertiary-color',
};

interface AssignmentRowProps {
  item: HomeActiveAssignment;
  href: string;
}

function AssignmentRow({ item, href }: AssignmentRowProps) {
  const statusLabel = STATUS_LABELS[item.status] ?? item.status;
  const statusColor = STATUS_COLORS[item.status] ?? 'bg-surface-color text-text-gray';

  return (
    <Link
      href={href}
      className="flex items-center gap-3 px-3 py-2.5 hover:bg-surface-color rounded-xl transition-colors group"
    >
      <div className="flex-1 min-w-0">
        <p className="text-sm font-comfortaa-bold text-text-color truncate">{item.title}</p>
      </div>
      <span
        className={`text-[10px] font-comfortaa-bold px-2 py-0.5 rounded-full shrink-0 ${statusColor}`}
      >
        {statusLabel}
      </span>
      <ChevronRight size={14} className="text-text-gray group-hover:text-text-color shrink-0" />
    </Link>
  );
}

interface SectionProps {
  icon: typeof BookOpen;
  label: string;
  items: HomeActiveAssignment[];
  hrefFn: (id: number) => string;
  color: string;
}

function AssignmentSection({ icon: Icon, label, items, hrefFn, color }: SectionProps) {
  if (items.length === 0) return null;
  return (
    <div className="bg-secondary-color border border-border-color rounded-2xl overflow-hidden">
      <div className="flex items-center gap-2 px-4 py-3 border-b border-border-color">
        <Icon size={14} className={color} />
        <span className="text-xs font-comfortaa-bold text-text-color uppercase tracking-wider">
          {label}
        </span>
        <span
          className={`ml-auto text-xs font-comfortaa-bold px-2 py-0.5 rounded-full bg-surface-color text-text-gray`}
        >
          {items.length}
        </span>
      </div>
      <div className="p-2 space-y-0.5">
        {items.map((item) => (
          <AssignmentRow key={item.id} item={item} href={hrefFn(item.id)} />
        ))}
      </div>
    </div>
  );
}

interface Props {
  activeAssignments?: {
    ds: HomeActiveAssignment[];
    dm: HomeActiveAssignment[];
    td: HomeActiveAssignment[];
  };
  averageGrade?: number | null;
}

export default function StudentHome({ activeAssignments, averageGrade }: Props) {
  const ds = activeAssignments?.ds ?? [];
  const dm = activeAssignments?.dm ?? [];
  const td = activeAssignments?.td ?? [];
  const total = ds.length + dm.length + td.length;

  return (
    <div className="space-y-6">
      {averageGrade != null && (
        <div className="flex items-center gap-3 px-4 py-3 bg-surface-color border border-border-color rounded-2xl">
          <div className="p-2 bg-warning-color/10 rounded-xl">
            <Star size={16} className="text-warning-color" />
          </div>
          <div>
            <p className="text-xs text-text-gray font-comfortaa">Moyenne générale</p>
            <p className="text-lg font-comfortaa-bold text-text-color">{averageGrade}/20</p>
          </div>
        </div>
      )}

      {total === 0 ? (
        <div className="flex flex-col items-center gap-3 py-12 text-center">
          <div className="w-12 h-12 rounded-2xl bg-student-color/10 flex items-center justify-center">
            <BookOpen size={22} className="text-student-color/60" />
          </div>
          <p className="text-sm text-text-gray">Aucun devoir en cours pour l&apos;instant.</p>
        </div>
      ) : (
        <div className="space-y-4">
          <AssignmentSection
            icon={ClipboardList}
            label="DS"
            items={ds}
            hrefFn={(id) => route('ds.show', id)}
            color="text-teacher-color"
          />
          <AssignmentSection
            icon={FileText}
            label="DM"
            items={dm}
            hrefFn={(id) => route('dm.show', id)}
            color="text-tertiary-color"
          />
          <AssignmentSection
            icon={BookOpen}
            label="TD"
            items={td}
            hrefFn={(id) => route('td.show', id)}
            color="text-student-color"
          />
        </div>
      )}
    </div>
  );
}
