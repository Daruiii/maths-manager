import { Head } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import { useAuth } from '@/Hooks/Auth/useAuth';
import TeacherHome from './Partials/TeacherHome';
import StudentHome from './Partials/StudentHome';
import GuestHome from './Partials/GuestHome';
import EinsteinQuote from '@/Components/Features/Home/EinsteinQuote';

import { HomeProps } from '@/types';

export default function Home(props: HomeProps) {
  const { user, canActAsTeacher, isStudent } = useAuth();

  const renderContent = () => {
    if (!user) return <GuestHome />;
    if (canActAsTeacher)
      return (
        <TeacherHome
          pendingCorrections={props.pendingCorrections}
          unlockRequests={props.unlockRequests}
          pendingTeachersCount={props.pendingTeachersCount}
          activeStudentsCount={props.activeStudentsCount}
          assignedThisMonth={props.assignedThisMonth}
          activeBatches={props.activeBatches}
        />
      );
    if (isStudent)
      return (
        <StudentHome
          activeAssignments={props.activeAssignments}
          averageGrade={props.averageGrade}
          correctedCount={props.correctedCount}
        />
      );
    return <GuestHome />;
  };

  return (
    <AppLayout title="Accueil">
      <Head>
        <title>Accueil - Maths Manager</title>
        <meta
          name="description"
          content="Plateforme de gestion de maths : exercices, quizz, fiches, DS."
        />
      </Head>

      <div className="py-8 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-10">
        {renderContent()}
        <EinsteinQuote />
      </div>
    </AppLayout>
  );
}
