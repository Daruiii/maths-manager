import { Head, Link } from '@inertiajs/react';
import { BookOpen, ChevronRight } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import { BATCH_STATUS_META } from '@/Constants/statuses';
import type { DmStatus, TdStatus } from '@/types/models';

interface DmBrief {
  id: number;
  status: DmStatus;
  custom_title: string | null;
  custom_level: string | null;
  teacher: { first_name: string; last_name: string } | null;
  created_at: string;
}

interface TdBrief {
  id: number;
  status: TdStatus;
  custom_title: string | null;
  custom_level: string | null;
  teacher: { first_name: string; last_name: string } | null;
  created_at: string;
}

function AssignmentRow({
  href,
  title,
  teacher,
  level,
  status,
}: {
  href: string;
  title: string;
  teacher: { first_name: string; last_name: string } | null;
  level: string | null;
  status: string;
}) {
  const meta = BATCH_STATUS_META[status] ?? BATCH_STATUS_META.not_started;

  return (
    <Link
      href={href}
      className="flex items-center gap-3 px-4 py-3 rounded-2xl bg-secondary-color border border-border-color hover:border-student-color/40 transition-colors group"
    >
      <div className="flex-1 min-w-0">
        <p className="font-comfortaa-bold text-text-color truncate">{title}</p>
        {teacher && (
          <p className="text-xs text-text-gray mt-0.5">
            {teacher.first_name} {teacher.last_name}
            {level && <span className="ml-2 text-student-color">{level}</span>}
          </p>
        )}
      </div>
      <span
        className={`text-xs px-2 py-0.5 rounded-full font-comfortaa-bold shrink-0 ${meta.classes}`}
      >
        {meta.label}
      </span>
      <ChevronRight
        size={16}
        className="text-text-gray shrink-0 group-hover:text-student-color transition-colors"
      />
    </Link>
  );
}

export default function StudentAssignmentsIndex({ dms, tds }: { dms: DmBrief[]; tds: TdBrief[] }) {
  const hasAssignments = dms.length > 0 || tds.length > 0;

  return (
    <AppLayout>
      <Head title="Mes devoirs" />
      <div className="max-w-3xl mx-auto px-4 py-6 space-y-6">
        <PageHeader title="Mes devoirs" breadcrumbs={[{ label: 'Mes devoirs' }]} />

        {!hasAssignments ? (
          <div className="flex flex-col items-center justify-center py-16 gap-3 text-text-gray">
            <BookOpen size={32} className="opacity-40" />
            <p className="text-sm">Aucun devoir assigné pour l'instant.</p>
          </div>
        ) : (
          <div className="space-y-6">
            {dms.length > 0 && (
              <section className="space-y-2">
                <SectionLabel>Devoirs Maison ({dms.length})</SectionLabel>
                <ul className="space-y-2">
                  {dms.map((dm) => (
                    <li key={dm.id}>
                      <AssignmentRow
                        href={route('dm.show', dm.id)}
                        title={dm.custom_title ?? 'Devoir Maison'}
                        teacher={dm.teacher}
                        level={dm.custom_level}
                        status={dm.status}
                      />
                    </li>
                  ))}
                </ul>
              </section>
            )}

            {tds.length > 0 && (
              <section className="space-y-2">
                <SectionLabel>TD ({tds.length})</SectionLabel>
                <ul className="space-y-2">
                  {tds.map((td) => (
                    <li key={td.id}>
                      <AssignmentRow
                        href={route('td.show', td.id)}
                        title={td.custom_title ?? "Fiche d'exercices"}
                        teacher={td.teacher}
                        level={td.custom_level}
                        status={td.status}
                      />
                    </li>
                  ))}
                </ul>
              </section>
            )}
          </div>
        )}
      </div>
    </AppLayout>
  );
}
