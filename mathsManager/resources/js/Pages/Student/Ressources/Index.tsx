import { Head } from '@inertiajs/react';
import { BookOpen, GraduationCap } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import AssignmentRow from '@/Components/Features/Assignments/AssignmentRow';
import type { DmStatus, DsStatus, TdStatus } from '@/types/models';

interface Brief {
  id: number;
  custom_title: string | null;
  custom_level: string | null;
  teacher: { first_name: string; last_name: string } | null;
}

interface DsBrief extends Brief {
  status: DsStatus;
}
interface DmBrief extends Brief {
  status: DmStatus;
}
interface TdBrief extends Brief {
  status: TdStatus;
}

export default function StudentRessourcesIndex({
  dss,
  dms,
  tds,
}: {
  dss: DsBrief[];
  dms: DmBrief[];
  tds: TdBrief[];
}) {
  const hasContent = dss.length > 0 || dms.length > 0 || tds.length > 0;

  return (
    <AppLayout>
      <Head title="Mes Ressources" />
      <div className="max-w-3xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title="Mes Ressources"
          subtitle="Tous vos devoirs, exercices favoris et récaps de cours"
          breadcrumbs={[{ label: 'Mes Ressources' }]}
        />

        {!hasContent ? (
          <div className="flex flex-col items-center justify-center py-16 gap-3 text-text-gray">
            <BookOpen size={32} className="opacity-40" />
            <p className="text-sm">Aucune ressource pour l'instant.</p>
          </div>
        ) : (
          <div className="space-y-6">
            {dss.length > 0 && (
              <section className="space-y-2">
                <SectionLabel>
                  <span className="inline-flex items-center gap-1.5">
                    <GraduationCap size={13} />
                    Devoirs Surveillés ({dss.length})
                  </span>
                </SectionLabel>
                <ul className="space-y-2">
                  {dss.map((ds) => (
                    <li key={ds.id}>
                      <AssignmentRow
                        href={route('ds.show', ds.id)}
                        title={ds.custom_title ?? 'Devoir Surveillé'}
                        teacher={ds.teacher}
                        level={ds.custom_level}
                        status={ds.status}
                      />
                    </li>
                  ))}
                </ul>
              </section>
            )}

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
