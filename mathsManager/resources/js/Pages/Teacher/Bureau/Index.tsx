import { Head, Link } from '@inertiajs/react';
import { History, BookMarked } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import RessourceCard from '@/Components/Common/UI/RessourceCard';
import Button from '@/Components/Common/UI/Button';
import { CONTENT_ITEM_META } from '@/Constants/contentTypes';

// ─── Types ────────────────────────────────────────────────────────────────────

interface Props {
  stats: {
    exercisesCount: number;
    dsTemplatesCount: number;
    tdTemplatesCount: number;
    dmTemplatesCount: number;
  };
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
          action={
            <Link href={route('teacher.bureau.history')}>
              <Button variant="ghost" icon={History} size="sm">
                Historique
              </Button>
            </Link>
          }
        />

        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <RessourceCard
            icon={CONTENT_ITEM_META.private.icon}
            title="Exercices privés"
            subtitle="Créez et gérez vos exercices personnels"
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
        </div>
      </div>
    </AppLayout>
  );
}
