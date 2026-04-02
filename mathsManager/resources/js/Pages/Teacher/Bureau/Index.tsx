import { Head } from '@inertiajs/react';
import { BookOpen, FileText, FolderOpen } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import RessourceCard from '@/Components/Common/UI/RessourceCard';

// ─── Types ────────────────────────────────────────────────────────────────────

interface Props {
  stats: { exercisesCount: number };
}

// ─── Page ─────────────────────────────────────────────────────────────────────

export default function BureauIndex({ stats }: Props) {
  return (
    <AppLayout>
      <Head title="Mon Bureau" />

      <div className="max-w-4xl mx-auto px-4 py-6 space-y-8">
        <PageHeader
          title="Mon Bureau"
          subtitle="Vos ressources pédagogiques personnelles"
          breadcrumbs={[{ label: 'Mon Bureau' }]}
        />

        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <RessourceCard
            icon={BookOpen}
            title="Exercices privés"
            subtitle="Créez et gérez vos exercices personnels"
            count={stats.exercisesCount}
            href={route('teacher.exercices.index')}
            color="teacher"
          />
          <RessourceCard
            icon={FileText}
            title="DS sauvegardés"
            subtitle="Retrouvez vos devoirs surveillés enregistrés"
            href="#"
            available={false}
          />
          <RessourceCard
            icon={FolderOpen}
            title="TD sauvegardés"
            subtitle="Retrouvez vos fiches de travaux dirigés"
            href="#"
            available={false}
          />
        </div>
      </div>
    </AppLayout>
  );
}
