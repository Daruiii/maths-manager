import { ReactElement } from 'react';
import { Head } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import { PageProps } from '@/types';
import Guest from '@/Pages/Home/Guest';
import Student from '@/Pages/Home/Student';
import Admin from '@/Pages/Home/Admin';
import EinsteinQuote from '@/Components/UI/EinsteinQuote';

/**
 * Main Home Page Component
 * Handles role-based dashboard routing following strict Clean Code standards.
 */
export default function Home(props: PageProps) {
  const { auth } = props;

  /**
   * Safe content renderer based on user role.
   * Handles cases where auth might be missing or user is not logged in.
   */
  const renderDashboard = (): ReactElement => {
    const user = auth?.user;

    if (!user) {
      return <Guest introContent={props.introContent} whoamiContent={props.whoamiContent} />;
    }

    const dashboards: Record<string, ReactElement> = {
      admin: <Admin correctionRequests={props.correctionRequests} ds={props.ds} />,
      student: <Student {...props} />,
      teacher: <Student {...props} />,
    };

    return (
      dashboards[user.role] || (
        <div className="p-8 text-center font-comfortaa text-error-color">
          Rôle utilisateur "{user.role}" non reconnu.
        </div>
      )
    );
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

        <section aria-label="Dashboard">{renderDashboard()}</section>
      </div>
    </AppLayout>
  );
}
