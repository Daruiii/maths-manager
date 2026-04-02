import { Head, Link, router } from '@inertiajs/react';
import { BookOpen, Plus, Search } from 'lucide-react';
import { PrivateExercise, TeacherTag } from '@/types/models';
import { PaginatedResponse } from '@/types/api';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Pagination from '@/Components/Common/UI/Pagination';
import EmptyState from '@/Components/Common/UI/EmptyState';
import DifficultyPicker from '@/Components/Common/Form/DifficultyPicker';

// ─── Types ────────────────────────────────────────────────────────────────────

interface Filters {
  search?: string;
  type?: string;
  difficulty?: string;
  tag_id?: string;
}

interface Props {
  exercises: PaginatedResponse<PrivateExercise>;
  tags: TeacherTag[];
  filters: Filters;
}

// ─── Exercise card ─────────────────────────────────────────────────────────────

function ExerciseRow({ exercise }: { exercise: PrivateExercise }) {
  return (
    <Link
      href={route('teacher.exercices.edit', exercise.id)}
      className="flex items-center gap-3 px-4 py-3 bg-surface-color border border-border-color rounded-xl hover:border-teacher-color/40 transition-colors group"
    >
      <span
        className={`w-1.5 h-1.5 rounded-full flex-shrink-0 ${exercise.type === 'problem' ? 'bg-teacher-color' : 'bg-teacher-color/40'}`}
      />
      <span className="flex-1 text-sm text-text-color truncate">{exercise.name}</span>
      {exercise.difficulty != null && (
        <DifficultyPicker value={String(exercise.difficulty)} onChange={() => {}} readOnly />
      )}
      <span className="text-xxs text-text-gray/60 flex-shrink-0 hidden sm:block">
        {exercise.type === 'problem' ? 'Problème' : 'Exercice'}
      </span>
    </Link>
  );
}

// ─── Page ─────────────────────────────────────────────────────────────────────

export default function ExercicesIndex({ exercises, tags, filters }: Props) {
  function applyFilter(patch: Partial<Filters & { page?: number }>) {
    router.get(
      route('teacher.exercices.index'),
      { ...filters, page: 1, ...patch },
      { preserveState: true, replace: true }
    );
  }

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
        <div className="flex flex-wrap gap-3">
          {/* Search */}
          <div className="relative flex-1 min-w-[180px]">
            <Search size={14} className="absolute left-3 top-1/2 -translate-y-1/2 text-text-gray" />
            <input
              type="search"
              value={filters.search ?? ''}
              onChange={(e) => applyFilter({ search: e.target.value || undefined })}
              placeholder="Rechercher…"
              className="w-full pl-9 pr-3 py-2 text-sm bg-surface-color border border-border-color rounded-xl text-text-color placeholder:text-text-gray/50 outline-none focus:border-teacher-color transition-colors"
            />
          </div>

          {/* Type */}
          <select
            value={filters.type ?? ''}
            onChange={(e) => applyFilter({ type: e.target.value || undefined })}
            className="text-sm px-3 py-2 bg-surface-color border border-border-color rounded-xl text-text-color outline-none focus:border-teacher-color transition-colors cursor-pointer"
          >
            <option value="">Tous types</option>
            <option value="basic">Exercice</option>
            <option value="problem">Problème</option>
          </select>

          {/* Difficulty */}
          <select
            value={filters.difficulty ?? ''}
            onChange={(e) => applyFilter({ difficulty: e.target.value || undefined })}
            className="text-sm px-3 py-2 bg-surface-color border border-border-color rounded-xl text-text-color outline-none focus:border-teacher-color transition-colors cursor-pointer"
          >
            <option value="">Toute difficulté</option>
            {[1, 2, 3, 4, 5].map((n) => (
              <option key={n} value={String(n)}>
                Diff. {n}
              </option>
            ))}
          </select>

          {/* Tag */}
          {tags.length > 0 && (
            <select
              value={filters.tag_id ?? ''}
              onChange={(e) => applyFilter({ tag_id: e.target.value || undefined })}
              className="text-sm px-3 py-2 bg-surface-color border border-border-color rounded-xl text-text-color outline-none focus:border-teacher-color transition-colors cursor-pointer"
            >
              <option value="">Tous tags</option>
              {tags.map((t) => (
                <option key={t.id} value={String(t.id)}>
                  {t.name}
                </option>
              ))}
            </select>
          )}
        </div>

        {/* Liste */}
        {exercises.data.length === 0 ? (
          <EmptyState icon={BookOpen} description="Aucun exercice trouvé." accentColor="teacher" />
        ) : (
          <div className="space-y-2">
            {exercises.data.map((ex: PrivateExercise) => (
              <ExerciseRow key={ex.id} exercise={ex} />
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
    </AppLayout>
  );
}
