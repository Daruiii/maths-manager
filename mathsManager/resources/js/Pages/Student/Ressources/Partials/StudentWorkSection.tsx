import { GraduationCap } from 'lucide-react';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import AssignmentRow from '@/Components/Features/Assignments/AssignmentRow';
import type {
  StudentDmResource,
  StudentDsResource,
  StudentTdResource,
} from '@/Pages/Student/Ressources/Partials/types';

interface Props {
  dss: StudentDsResource[];
  dms: StudentDmResource[];
  tds: StudentTdResource[];
}

export default function StudentWorkSection({ dss, dms, tds }: Props) {
  const hasWorks = dss.length > 0 || dms.length > 0 || tds.length > 0;

  if (!hasWorks) {
    return (
      <div className="flex flex-col items-center justify-center py-12 gap-3 text-text-gray bg-secondary-color border border-border-color rounded-3xl">
        <GraduationCap size={28} className="opacity-40" />
        <p className="text-sm">Aucun travail pour l'instant.</p>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      {dss.length > 0 && (
        <WorkList title={`Devoirs Surveillés (${dss.length})`}>
          {dss.map((ds) => (
            <AssignmentRow
              key={ds.id}
              href={route('ds.show', ds.id)}
              title={ds.custom_title ?? 'Devoir Surveillé'}
              teacher={ds.teacher}
              level={ds.custom_level}
              status={ds.status}
              grade={ds.grade}
            />
          ))}
        </WorkList>
      )}

      {dms.length > 0 && (
        <WorkList title={`Devoirs Maison (${dms.length})`}>
          {dms.map((dm) => (
            <AssignmentRow
              key={dm.id}
              href={route('dm.show', dm.id)}
              title={dm.custom_title ?? 'Devoir Maison'}
              teacher={dm.teacher}
              level={dm.custom_level}
              status={dm.status}
              grade={dm.grade}
            />
          ))}
        </WorkList>
      )}

      {tds.length > 0 && (
        <WorkList title={`TD (${tds.length})`}>
          {tds.map((td) => (
            <AssignmentRow
              key={td.id}
              href={route('td.show', td.id)}
              title={td.custom_title ?? "Fiche d'exercices"}
              teacher={td.teacher}
              level={td.custom_level}
              status={td.status}
            />
          ))}
        </WorkList>
      )}
    </div>
  );
}

interface WorkListProps {
  title: string;
  children: React.ReactNode;
}

function WorkList({ title, children }: WorkListProps) {
  return (
    <section className="space-y-2">
      <SectionLabel>{title}</SectionLabel>
      <ul className="space-y-2">
        {Array.isArray(children) ? (
          children.map((child, index) => <li key={index}>{child}</li>)
        ) : (
          <li>{children}</li>
        )}
      </ul>
    </section>
  );
}
