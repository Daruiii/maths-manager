import { Head } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import { useAuth } from '@/Hooks/useAuth';
import AdminHome from './Partials/AdminHome';
import TeacherHome from './Partials/TeacherHome';
import StudentHome from './Partials/StudentHome';
import GuestHome from './Partials/GuestHome';
import EinsteinQuote from '@/Components/Features/Home/EinsteinQuote';

import { HomeProps } from '@/types';

export default function Home(props: HomeProps) {
  const { user, isAdmin, isTeacher, isStudent } = useAuth();

  const renderContent = () => {
    if (!user) return <GuestHome />;
    if (isAdmin) return <AdminHome pendingTeachersCount={props.pendingTeachersCount} />;
    if (isTeacher) return <TeacherHome />;
    if (isStudent) return <StudentHome />;
    return <GuestHome />; // Fallback
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

      <div className="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12">
        <EinsteinQuote />
        {renderContent()}
      </div>
    </AppLayout>
  );
}
