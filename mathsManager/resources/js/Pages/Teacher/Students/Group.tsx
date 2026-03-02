import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import StatusCard from '@/Components/Common/UI/StatusCard';
import { StudentGroup, User } from '@/types/models';
import StudentCard from '@/Pages/Teacher/Students/Partials/StudentCard';
import { BookOpen, FileText, Users } from 'lucide-react';
import { route } from 'ziggy-js';

interface Props {
  group: StudentGroup;
  students: User[];
}

export default function Group({ group, students }: Props) {
  return (
    <AppLayout title={group.name}>
      <div className="py-12 px-4 sm:px-6 lg:px-8">
        <div className="max-w-7xl mx-auto">
          <PageHeader
            title={group.name}
            subtitle={`${students.length} élève${students.length > 1 ? 's' : ''} dans ce groupe.`}
            breadcrumbs={[
              { label: 'Mes Élèves', href: route('teacher.students.index') },
              { label: group.name },
            ]}
            action={
              <div className="flex gap-2">
                <button
                  disabled
                  title="Bientôt disponible"
                  className="flex items-center gap-1.5 text-sm text-text-gray border border-border-color rounded-xl px-3 py-2 opacity-50 cursor-not-allowed"
                >
                  <BookOpen size={16} /> DS groupe
                </button>
                <button
                  disabled
                  title="Bientôt disponible"
                  className="flex items-center gap-1.5 text-sm text-text-gray border border-border-color rounded-xl px-3 py-2 opacity-50 cursor-not-allowed"
                >
                  <FileText size={16} /> Fiche groupe
                </button>
              </div>
            }
          />

          <div className="mt-8">
            {students.length === 0 ? (
              <div className="py-12 bg-secondary-color rounded-2xl border-2 border-border-color border-dashed">
                <StatusCard
                  icon={Users}
                  title="Groupe vide"
                  description="Aucun élève dans ce groupe pour le moment."
                />
              </div>
            ) : (
              <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                {students.map((student) => (
                  <StudentCard key={student.id} student={student} />
                ))}
              </div>
            )}
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
