import { Head } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import EinsteinQuote from '@/Components/Features/Home/EinsteinQuote';

export default function Home() {
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
      </div>
    </AppLayout>
  );
}
