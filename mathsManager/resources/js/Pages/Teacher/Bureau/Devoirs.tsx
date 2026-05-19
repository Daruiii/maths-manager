import { Head } from '@inertiajs/react';
import { BookOpen } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import BatchRow from './Partials/BatchRow';
import type { BatchBrief } from '@/types/api';

interface Props {
  dsBatches: BatchBrief[];
  tdBatches: BatchBrief[];
  dmBatches: BatchBrief[];
}

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
    <section className="space-y-3">
      <div className="flex items-center justify-between">
        <h3 className="text-xs font-comfortaa-bold text-text-gray uppercase tracking-wider">
          {title}
        </h3>
        <span className="text-xs text-text-gray bg-surface-color border border-border-color px-2 py-0.5 rounded-full">
          {batches.length} envoi{batches.length > 1 ? 's' : ''}
        </span>
      </div>
      <div className="space-y-2">
        {batches.map((batch) => (
          <BatchRow key={batch.id} batch={batch} type={type} />
        ))}
      </div>
    </section>
  );
}

export default function BureauDevoirs({ dsBatches, tdBatches, dmBatches }: Props) {
  const hasBatches = dsBatches.length > 0 || tdBatches.length > 0 || dmBatches.length > 0;

  return (
    <AppLayout>
      <Head title="Devoirs envoyés" />

      <div className="max-w-4xl mx-auto px-4 py-6 space-y-8">
        <PageHeader
          title="Devoirs envoyés"
          subtitle="Tous vos DS, DM et TD assignés à vos élèves"
          breadcrumbs={[
            { label: 'Mon Bureau', href: route('teacher.bureau.index') },
            { label: 'Devoirs envoyés' },
          ]}
        />

        {!hasBatches ? (
          <div className="flex flex-col items-center justify-center py-16 gap-3 text-text-gray">
            <BookOpen size={32} className="opacity-40" />
            <p className="text-sm">Aucun devoir envoyé pour l'instant.</p>
          </div>
        ) : (
          <div className="space-y-8">
            <BatchSection title="Devoirs Surveillés" batches={dsBatches} type="ds" />
            <BatchSection title="Devoirs Maison" batches={dmBatches} type="dm" />
            <BatchSection title="TD" batches={tdBatches} type="td" />
          </div>
        )}
      </div>
    </AppLayout>
  );
}
