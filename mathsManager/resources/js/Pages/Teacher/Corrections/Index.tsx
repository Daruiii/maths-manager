import { Head, Link, router } from '@inertiajs/react';
import { ClipboardList, ChevronRight, LockOpen } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import Button from '@/Components/Common/UI/Button';
import type { PaginatedResponse } from '@/types/api';
import type { CorrectionRequestStatus, TdStatus } from '@/types/models';

interface CorrectionBrief {
  id: number;
  status: CorrectionRequestStatus;
  grade: number | null;
  created_at: string;
  user: { first_name: string; last_name: string } | null;
  ds: { id: number; custom_title: string | null } | null;
  dm: { id: number; custom_title: string | null } | null;
}

interface Props {
  correctionRequests: PaginatedResponse<CorrectionBrief>;
  tdUnlockRequests: TdUnlockBrief[];
  filters: { status: string };
}

interface TdUnlockBrief {
  id: number;
  status: TdStatus;
  custom_title: string | null;
  custom_level: string | null;
  updated_at: string;
  student: { first_name: string; last_name: string } | null;
}

const STATUS_TABS = [
  { value: 'pending', label: 'À corriger' },
  { value: 'corrected', label: 'Corrigés' },
  { value: 'all', label: 'Tous' },
] as const;

export default function CorrectionsIndex({ correctionRequests, tdUnlockRequests, filters }: Props) {
  const items = correctionRequests.data;
  const showTdUnlocks = filters.status === 'pending' || filters.status === 'all';

  function setStatus(status: string) {
    router.get(
      route('teacher.corrections.index'),
      { status },
      { preserveState: true, replace: true }
    );
  }

  return (
    <AppLayout>
      <Head title="Corrections" />
      <div className="max-w-3xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title="Corrections"
          breadcrumbs={[
            { label: 'Mon Bureau', href: route('teacher.bureau.index') },
            { label: 'Corrections' },
          ]}
        />

        <div className="flex gap-2">
          {STATUS_TABS.map((tab) => (
            <button
              key={tab.value}
              onClick={() => setStatus(tab.value)}
              className={`text-xs px-3 py-1.5 rounded-full font-comfortaa-bold transition-colors ${
                filters.status === tab.value
                  ? 'bg-teacher-color text-white'
                  : 'bg-secondary-color border border-border-color text-text-gray hover:text-text-color'
              }`}
            >
              {tab.label}
            </button>
          ))}
        </div>

        {showTdUnlocks && tdUnlockRequests.length > 0 && (
          <div className="space-y-2">
            <SectionLabel>TD à débloquer ({tdUnlockRequests.length})</SectionLabel>
            <ul className="space-y-2">
              {tdUnlockRequests.map((td) => (
                <li
                  key={td.id}
                  className="flex items-center gap-3 px-4 py-3 rounded-2xl bg-secondary-color border border-warning-color/20"
                >
                  <div className="flex-1 min-w-0">
                    <p className="font-comfortaa-bold text-text-color truncate">
                      {td.custom_title ?? "Fiche d'exercices"}
                    </p>
                    {td.student && (
                      <p className="text-xs text-text-gray mt-0.5">
                        {td.student.first_name} {td.student.last_name}
                        {td.custom_level && (
                          <span className="ml-2 text-student-color">{td.custom_level}</span>
                        )}
                      </p>
                    )}
                  </div>
                  <Button
                    type="button"
                    variant="teacher"
                    size="sm"
                    icon={LockOpen}
                    onClick={() => router.patch(route('teacher.td.unlock', td.id))}
                  >
                    Débloquer
                  </Button>
                </li>
              ))}
            </ul>
          </div>
        )}

        {items.length === 0 && (!showTdUnlocks || tdUnlockRequests.length === 0) ? (
          <div className="flex flex-col items-center justify-center py-16 gap-3 text-text-gray">
            <ClipboardList size={32} className="opacity-40" />
            <p className="text-sm">Aucune correction à traiter.</p>
          </div>
        ) : (
          <div className="space-y-2">
            <SectionLabel>
              {items.length} correction{items.length > 1 ? 's' : ''}
            </SectionLabel>
            <ul className="space-y-2">
              {items.map((cr) => {
                const subject = cr.ds ?? cr.dm;
                const subjectLabel = cr.ds ? 'DS' : 'DM';
                return (
                  <li key={cr.id}>
                    <Link
                      href={route('teacher.corrections.show', cr.id)}
                      className="flex items-center gap-3 px-4 py-3 rounded-2xl bg-secondary-color border border-border-color hover:border-teacher-color/40 transition-colors group"
                    >
                      <div className="flex-1 min-w-0">
                        <p className="font-comfortaa-bold text-text-color truncate">
                          {subject?.custom_title ?? subjectLabel}
                        </p>
                        {cr.user && (
                          <p className="text-xs text-text-gray mt-0.5">
                            {cr.user.first_name} {cr.user.last_name}
                          </p>
                        )}
                      </div>
                      <span
                        className={`text-xs px-2 py-0.5 rounded-full font-comfortaa-bold shrink-0 ${
                          cr.status === 'pending'
                            ? 'bg-warning-color/10 text-warning-color'
                            : 'bg-success-color/10 text-success-color'
                        }`}
                      >
                        {cr.status === 'pending' ? 'À corriger' : 'Corrigé'}
                      </span>
                      <ChevronRight
                        size={16}
                        className="text-text-gray shrink-0 group-hover:text-teacher-color transition-colors"
                      />
                    </Link>
                  </li>
                );
              })}
            </ul>
          </div>
        )}
      </div>
    </AppLayout>
  );
}
