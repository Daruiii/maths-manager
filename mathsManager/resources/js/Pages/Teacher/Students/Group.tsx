import { Link } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import StatusCard from '@/Components/Common/UI/StatusCard';
import Pagination from '@/Components/Common/UI/Pagination';
import { StudentGroup, User } from '@/types/models';
import StudentCard from '@/Pages/Teacher/Students/Partials/StudentCard';
import StudentsToolbar from '@/Pages/Teacher/Students/Partials/StudentsToolbar';
import { Users } from 'lucide-react';
import { route } from 'ziggy-js';
import { useGroupStudentsFilter } from '@/Hooks/Students/useGroupStudentsFilter';
import { CONTENT_TYPE_META } from '@/Constants/contentTypes';

interface Props {
  group: StudentGroup;
  students: User[];
  groups: StudentGroup[];
}

export default function Group({ group, students, groups }: Props) {
  const {
    search,
    page,
    setPage,
    filteredStudents,
    paginatedStudents,
    totalPages,
    handleSearchChange,
  } = useGroupStudentsFilter(students);

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
                <a
                  href={route('teacher.ds.create', { group: group.id })}
                  className="flex items-center gap-1.5 text-sm text-teacher-color border border-teacher-color/40 bg-teacher-color/5 hover:bg-teacher-color/10 rounded-xl px-3 py-2 transition-colors"
                >
                  <CONTENT_TYPE_META.ds.icon size={16} /> {CONTENT_TYPE_META.ds.groupLabel}
                </a>
                <Link
                  href={route('teacher.td.create', { group: group.id })}
                  className="flex items-center gap-1.5 text-sm text-teacher-color border border-teacher-color/40 bg-teacher-color/5 hover:bg-teacher-color/10 rounded-xl px-3 py-2 transition-colors"
                >
                  <CONTENT_TYPE_META.td.icon size={16} /> {CONTENT_TYPE_META.td.groupLabel}
                </Link>
                <Link
                  href={route('teacher.dm.create', { group: group.id })}
                  className="flex items-center gap-1.5 text-sm text-teacher-color border border-teacher-color/40 bg-teacher-color/5 hover:bg-teacher-color/10 rounded-xl px-3 py-2 transition-colors"
                >
                  <CONTENT_TYPE_META.dm.icon size={16} /> {CONTENT_TYPE_META.dm.groupLabel}
                </Link>
              </div>
            }
          />

          <div className="mt-8 space-y-6">
            <StudentsToolbar search={search} onSearchChange={handleSearchChange} />

            {students.length === 0 ? (
              <div className="py-12 bg-secondary-color rounded-2xl border-2 border-border-color border-dashed">
                <StatusCard
                  icon={Users}
                  title="Groupe vide"
                  description="Aucun élève dans ce groupe pour le moment."
                />
              </div>
            ) : filteredStudents.length === 0 ? (
              <div className="py-12 bg-secondary-color rounded-2xl border-2 border-border-color border-dashed">
                <StatusCard
                  icon={Users}
                  title="Aucun résultat"
                  description={`Aucun élève ne correspond à « ${search} ».`}
                />
              </div>
            ) : (
              <>
                <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                  {paginatedStudents.map((student) => (
                    <StudentCard
                      key={student.id}
                      student={student}
                      groups={groups}
                      showGroupBadge={false}
                    />
                  ))}
                </div>

                <Pagination
                  page={page}
                  totalPages={totalPages}
                  onPageChange={setPage}
                  info={`${filteredStudents.length} élève${filteredStudents.length > 1 ? 's' : ''}`}
                  accentColor="teacher"
                />
              </>
            )}
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
