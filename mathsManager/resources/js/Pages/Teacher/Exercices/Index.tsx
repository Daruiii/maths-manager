import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { BookOpen, Plus, Filter, RotateCcw } from 'lucide-react';
import { PrivateExercise, TeacherTag } from '@/types/models';
import { PaginatedResponse } from '@/types/api';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Pagination from '@/Components/Common/UI/Pagination';
import EmptyState from '@/Components/Common/UI/EmptyState';
import SearchBar from '@/Components/Common/UI/SearchBar';
import ButtonModal from '@/Components/Common/UI/ButtonModal';
import ConfirmationModal from '@/Components/Common/UI/ConfirmationModal';
import Button from '@/Components/Common/UI/Button';
import ExerciseRow from '@/Pages/Teacher/Exercices/Partials/ExerciseRow';

// ─── Types ────────────────────────────────────────────────────────────────────

interface Filters {
  search?: string;
  type?: string;
  difficulty?: string;
  tag_id?: string;
  sort?: string;
}

interface Props {
  exercises: PaginatedResponse<PrivateExercise>;
  tags: TeacherTag[];
  filters: Filters;
}

function iconControlClass(active: boolean) {
  return `h-10 w-10 inline-flex items-center justify-center rounded-xl border transition-colors ${
    active
      ? 'border-teacher-color/50 bg-teacher-color/10 text-teacher-color'
      : 'border-border-color bg-secondary-color text-text-gray hover:text-text-color hover:border-teacher-color/40'
  }`;
}

// ─── Page ─────────────────────────────────────────────────────────────────────

export default function ExercicesIndex({ exercises, tags, filters }: Props) {
  const [isFilterModalOpen, setIsFilterModalOpen] = useState(false);
  const [exerciseToDelete, setExerciseToDelete] = useState<PrivateExercise | null>(null);
  const [draftFilters, setDraftFilters] = useState<Filters>({
    type: filters.type ?? '',
    difficulty: filters.difficulty ?? '',
    tag_id: filters.tag_id ?? '',
    sort: filters.sort ?? 'recent',
  });

  function applyFilter(patch: Partial<Filters & { page?: number }>) {
    router.get(
      route('teacher.exercices.index'),
      { ...filters, page: 1, ...patch },
      { preserveState: true, replace: true }
    );
  }

  function confirmDelete() {
    if (!exerciseToDelete) return;
    router.delete(route('teacher.exercices.destroy', exerciseToDelete.id), {
      preserveScroll: true,
      onFinish: () => setExerciseToDelete(null),
    });
  }

  function openFilterModal() {
    setDraftFilters({
      type: filters.type ?? '',
      difficulty: filters.difficulty ?? '',
      tag_id: filters.tag_id ?? '',
      sort: filters.sort ?? 'recent',
    });
    setIsFilterModalOpen(true);
  }

  function applyDraftFilters() {
    applyFilter({
      type: draftFilters.type || undefined,
      difficulty: draftFilters.difficulty || undefined,
      tag_id: draftFilters.tag_id || undefined,
      sort: draftFilters.sort || undefined,
    });
    setIsFilterModalOpen(false);
  }

  function resetDraftFilters() {
    setDraftFilters({
      type: '',
      difficulty: '',
      tag_id: '',
      sort: 'recent',
    });
  }

  const hasActiveFilters = Boolean(
    filters.type || filters.difficulty || filters.tag_id || (filters.sort ?? 'recent') === 'old'
  );

  return (
    <AppLayout>
      <Head title="Mes exercices" />

      <div className="max-w-4xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title="Mes exercices"
          subtitle={`${exercises.total} exercice${exercises.total !== 1 ? 's' : ''}`}
          breadcrumbs={[
            { label: 'Mon Bureau', href: route('teacher.bureau.index') },
            { label: 'Exercices' },
          ]}
          action={
            <Link
              href={route('teacher.exercices.create')}
              className="flex items-center gap-2 px-4 py-2 bg-teacher-color text-white text-sm font-comfortaa-bold rounded-xl hover:opacity-90 transition-opacity"
            >
              <Plus size={16} /> Nouvel exercice
            </Link>
          }
        />

        {/* Filtres */}
        <SearchBar
          value={filters.search ?? ''}
          onChange={(e) => applyFilter({ search: e.target.value || undefined })}
          onClear={() => applyFilter({ search: undefined })}
          placeholder="Rechercher…"
          focusRingClass="focus:border-teacher-color"
          sort={
            <ButtonModal
              isOpen={isFilterModalOpen}
              onOpenChange={(nextOpen) => {
                if (nextOpen) {
                  openFilterModal();
                  return;
                }
                setIsFilterModalOpen(false);
              }}
              trigger={
                <span className={iconControlClass(hasActiveFilters)}>
                  <Filter size={15} />
                </span>
              }
              panelWidthClassName="w-[min(15rem,calc(100vw-2rem))]"
            >
              <div className="space-y-2.5">
                <p className="px-0.5 text-xs font-comfortaa-bold text-text-color">Filtres</p>

                <div className="space-y-1">
                  <label className="px-0.5 text-[11px] font-comfortaa-bold text-text-gray">
                    Type
                  </label>
                  <select
                    value={draftFilters.type ?? ''}
                    onChange={(e) => setDraftFilters((prev) => ({ ...prev, type: e.target.value }))}
                    className="w-full h-9 px-2.5 text-xs rounded-lg border border-border-color bg-secondary-color text-text-color focus:outline-none focus:border-teacher-color"
                  >
                    <option value="">Tous types</option>
                    <option value="basic">Exercice</option>
                    <option value="problem">Problème</option>
                  </select>
                </div>

                <div className="space-y-1">
                  <label className="px-0.5 text-[11px] font-comfortaa-bold text-text-gray">
                    Difficulté
                  </label>
                  <select
                    value={draftFilters.difficulty ?? ''}
                    onChange={(e) =>
                      setDraftFilters((prev) => ({ ...prev, difficulty: e.target.value }))
                    }
                    className="w-full h-9 px-2.5 text-xs rounded-lg border border-border-color bg-secondary-color text-text-color focus:outline-none focus:border-teacher-color"
                  >
                    <option value="">Toute difficulté</option>
                    {[1, 2, 3, 4, 5].map((n) => (
                      <option key={n} value={String(n)}>
                        Diff. {n}
                      </option>
                    ))}
                  </select>
                </div>

                {tags.length > 0 && (
                  <div className="space-y-1">
                    <label className="px-0.5 text-[11px] font-comfortaa-bold text-text-gray">
                      Tag
                    </label>
                    <select
                      value={draftFilters.tag_id ?? ''}
                      onChange={(e) =>
                        setDraftFilters((prev) => ({ ...prev, tag_id: e.target.value }))
                      }
                      className="w-full h-9 px-2.5 text-xs rounded-lg border border-border-color bg-secondary-color text-text-color focus:outline-none focus:border-teacher-color"
                    >
                      <option value="">Tous tags</option>
                      {tags.map((t) => (
                        <option key={t.id} value={String(t.id)}>
                          {t.name}
                        </option>
                      ))}
                    </select>
                  </div>
                )}

                <div className="space-y-1">
                  <label className="px-0.5 text-[11px] font-comfortaa-bold text-text-gray">
                    Tri
                  </label>
                  <select
                    value={draftFilters.sort ?? 'recent'}
                    onChange={(e) => setDraftFilters((prev) => ({ ...prev, sort: e.target.value }))}
                    className="w-full h-9 px-2.5 text-xs rounded-lg border border-border-color bg-secondary-color text-text-color focus:outline-none focus:border-teacher-color"
                  >
                    <option value="recent">Récent</option>
                    <option value="old">Ancien</option>
                  </select>
                </div>

                <div className="flex items-center justify-end gap-1.5 pt-0.5">
                  <Button variant="ghost" size="sm" icon={RotateCcw} onClick={resetDraftFilters}>
                    Reset
                  </Button>
                  <Button variant="primary" size="sm" onClick={applyDraftFilters}>
                    OK
                  </Button>
                </div>
              </div>
            </ButtonModal>
          }
        />

        {/* Liste */}
        {exercises.data.length === 0 ? (
          <EmptyState icon={BookOpen} description="Aucun exercice trouvé." accentColor="teacher" />
        ) : (
          <div className="space-y-2">
            {exercises.data.map((ex: PrivateExercise) => (
              <ExerciseRow key={ex.id} exercise={ex} onDelete={() => setExerciseToDelete(ex)} />
            ))}
          </div>
        )}

        <Pagination
          page={exercises.current_page}
          totalPages={exercises.last_page}
          onPageChange={(page) => applyFilter({ page })}
          info={`${exercises.total} au total`}
        />
      </div>
      <ConfirmationModal
        isOpen={!!exerciseToDelete}
        onClose={() => setExerciseToDelete(null)}
        onConfirm={confirmDelete}
        title="Supprimer l'exercice"
        description={`« ${exerciseToDelete?.name} » sera définitivement supprimé.`}
        confirmText="Supprimer"
        type="danger"
      />
    </AppLayout>
  );
}
