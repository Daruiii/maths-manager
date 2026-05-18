import { Head, Link } from '@inertiajs/react';
import { ClipboardList, History, BookMarked, Users, LayoutDashboard } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import RessourceCard from '@/Components/Common/UI/RessourceCard';
import Button from '@/Components/Common/UI/Button';
import BatchRow from './Partials/BatchRow';
import { CONTENT_ITEM_META } from '@/Constants/contentTypes';
import type { BatchBrief } from '@/types/api';

// ─── Types ────────────────────────────────────────────────────────────────────

interface Props {
  stats: {
    exercisesCount: number;
    dsTemplatesCount: number;
    tdTemplatesCount: number;
    dmTemplatesCount: number;
    pendingCorrectionsCount: number;
  };
  dsBatches: BatchBrief[];
  tdBatches: BatchBrief[];
  dmBatches: BatchBrief[];
}

// ─── Composant section batches ────────────────────────────────────────────────

function BatchSection({
  title,
  batches,
  type,
}: {
  title: string;
  batches: BatchBrief[];
  type: 'ds' | 'td' | 'dm';
}) {
  if (batches.length === 0) return null;
  return (
    <div className="space-y-3">
      <div className="flex items-center justify-between">
        <h3 className="text-xs font-comfortaa-bold text-text-gray uppercase tracking-wider">
          {title}
        </h3>
        <span className="text-xs text-text-gray bg-surface-color border border-border-color px-2 py-0.5 rounded-full">
          {batches.length} récent{batches.length > 1 ? 's' : ''}
        </span>
      </div>
      <div className="space-y-2">
        {batches.map((batch) => (
          <BatchRow key={batch.id} batch={batch} type={type} />
        ))}
      </div>
    </div>
  );
}

// ─── Page ─────────────────────────────────────────────────────────────────────

export default function BureauIndex({ stats, dsBatches, tdBatches, dmBatches }: Props) {
  const hasBatches = dsBatches.length > 0 || tdBatches.length > 0 || dmBatches.length > 0;

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
          <RessourceCard
            icon={ClipboardList}
            title="Corrections"
            subtitle="Copies élèves en attente de correction"
            count={stats.pendingCorrectionsCount}
            href={route('teacher.corrections.index')}
            color="teacher"
          />
          <RessourceCard
            icon={Users}
            title="Mes élèves"
            subtitle="Gérez vos élèves et vos groupes"
            href={route('teacher.students.index')}
            color="teacher"
          />
        </div>

        {hasBatches && (
          <div className="space-y-6">
            <div className="flex items-center gap-2">
              <LayoutDashboard size={16} className="text-teacher-color" />
              <h2 className="text-sm font-comfortaa-bold text-text-color">Devoirs récents</h2>
            </div>
            <BatchSection title="DS" batches={dsBatches} type="ds" />
            <BatchSection title="DM" batches={dmBatches} type="dm" />
            <BatchSection title="TD" batches={tdBatches} type="td" />
          </div>
        )}
      </div>
    </AppLayout>
  );
}
