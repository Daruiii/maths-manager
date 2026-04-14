import { Head, Link, router } from '@inertiajs/react';
import { Activity } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import StatusCard from '@/Components/Common/UI/StatusCard';
import Pagination from '@/Components/Common/UI/Pagination';
import HistoryFilters from '@/Pages/Teacher/Bureau/Partials/HistoryFilters';
import HistoryActivityRow from '@/Pages/Teacher/Bureau/Partials/HistoryActivityRow';
import { BureauActivity, BureauHistoryFilters, PaginatedResponse } from '@/types/api';

interface Props {
  activities: PaginatedResponse<BureauActivity>;
  filters: BureauHistoryFilters;
}

export default function BureauHistory({ activities, filters }: Props) {
  function applyFilter(patch: Partial<BureauHistoryFilters & { page: number }>) {
    router.get(
      route('teacher.bureau.history'),
      {
        ...filters,
        page: 1,
        ...patch,
      },
      { preserveState: true, replace: true, preserveScroll: true }
    );
  }

  return (
    <AppLayout>
      <Head title="Historique global" />

      <div className="max-w-5xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title="Historique global"
          subtitle={`Tous vos événements prof en un seul flux (${activities.total})`}
          breadcrumbs={[
            { label: 'Mon Bureau', href: route('teacher.bureau.index') },
            { label: 'Historique global' },
          ]}
        />

        <HistoryFilters filters={filters} onFilterChange={applyFilter} />

        {activities.data.length === 0 ? (
          <div className="py-12 bg-secondary-color rounded-2xl border-2 border-border-color border-dashed">
            <StatusCard
              icon={Activity}
              title="Aucun événement trouvé"
              description="Ajustez vos filtres ou attendez les prochaines activités de votre espace prof."
            />
          </div>
        ) : (
          <div className="space-y-3">
            {activities.data.map((activity) => (
              <HistoryActivityRow key={activity.id} activity={activity} />
            ))}
          </div>
        )}

        <Pagination
          page={activities.current_page}
          totalPages={activities.last_page}
          onPageChange={(page) => applyFilter({ page })}
          info={`${activities.total} au total`}
          accentColor="teacher"
        />

        <div className="pt-2">
          <Link
            href={route('teacher.bureau.index')}
            className="text-sm text-teacher-color hover:underline"
          >
            ← Retour au Bureau
          </Link>
        </div>
      </div>
    </AppLayout>
  );
}
