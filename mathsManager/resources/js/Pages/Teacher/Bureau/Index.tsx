import { Head, Link } from '@inertiajs/react';
import { ClipboardList, History, BookMarked, Users, Send } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import RessourceCard from '@/Components/Common/UI/RessourceCard';
import Button from '@/Components/Common/UI/Button';
import { CONTENT_ITEM_META } from '@/Constants/contentTypes';

interface Props {
  stats: {
    exercisesCount: number;
    dsTemplatesCount: number;
    tdTemplatesCount: number;
    dmTemplatesCount: number;
    pendingCorrectionsCount: number;
    batchesCount: number;
  };
}

export default function BureauIndex({ stats }: Props) {
  return (
    <AppLayout>
      <Head title="Mon Bureau" />

      <div className="max-w-4xl mx-auto px-4 py-6 space-y-8">
        <PageHeader
          title="Mon Bureau"
          subtitle="Vos ressources pédagogiques personnelles"
          breadcrumbs={[{ label: 'Mon Bureau' }]}
          action={
            <Link href={route('teacher.bureau.history')}>
              <Button variant="ghost" icon={History} size="sm">
                Historique
              </Button>
            </Link>
          }
        />

        <div className="grid grid-cols-2 sm:grid-cols-3 gap-4">
          <RessourceCard
            icon={CONTENT_ITEM_META.private.icon}
            title="Exercices privés"
            subtitle="Créez et gérez vos exercices"
            count={stats.exercisesCount}
            href={route('teacher.exercices.index')}
            color="teacher"
          />
          <RessourceCard
            icon={BookMarked}
            title="Mes modèles"
            subtitle="DS, TD et DM sauvegardés"
            count={stats.dsTemplatesCount + stats.tdTemplatesCount + stats.dmTemplatesCount}
            href={route('teacher.bureau.templates')}
            color="teacher"
          />
          <RessourceCard
            icon={Send}
            title="Devoirs envoyés"
            subtitle="DS, DM et TD assignés"
            count={stats.batchesCount}
            href={route('teacher.bureau.devoirs')}
            color="teacher"
          />
          <RessourceCard
            icon={ClipboardList}
            title="Corrections"
            subtitle="Copies en attente"
            count={stats.pendingCorrectionsCount}
            href={route('teacher.corrections.index')}
            color="teacher"
          />
          <RessourceCard
            icon={Users}
            title="Mes élèves"
            subtitle="Gérez vos élèves et groupes"
            href={route('teacher.students.index')}
            color="teacher"
          />
        </div>
      </div>
    </AppLayout>
  );
}
