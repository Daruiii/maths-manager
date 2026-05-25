import { Head, Link } from '@inertiajs/react';
import { BookOpen, BrainCircuit, ChevronRight, Heart, Sparkles } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import StudentResourceFeatureCard from '@/Pages/Student/Ressources/Partials/StudentResourceFeatureCard';
import StudentWorkSection from '@/Pages/Student/Ressources/Partials/StudentWorkSection';
import type {
  StudentDmResource,
  StudentDsResource,
  StudentTdResource,
} from '@/Pages/Student/Ressources/Partials/types';

export default function StudentRessourcesIndex({
  dss,
  dms,
  tds,
}: {
  dss: StudentDsResource[];
  dms: StudentDmResource[];
  tds: StudentTdResource[];
}) {
  const totalWorks = dss.length + dms.length + tds.length;
  const correctedCount =
    dss.filter((ds) => ds.grade !== null && ds.grade !== undefined).length +
    dms.filter((dm) => dm.grade !== null && dm.grade !== undefined).length;

  return (
    <AppLayout>
      <Head title="Mes Ressources" />
      <div className="max-w-4xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title="Mes Ressources"
          subtitle="Vos travaux, corrections et futurs outils de révision."
          breadcrumbs={[{ label: 'Mes Ressources' }]}
        />

        <section className="relative overflow-hidden rounded-3xl bg-secondary-color border border-border-color p-5 sm:p-6">
          <div className="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none select-none opacity-[0.04]">
            <BookOpen size={120} />
          </div>
          <div className="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <p className="text-[11px] font-comfortaa-bold text-student-color uppercase tracking-widest">
                Espace élève
              </p>
              <h1 className="mt-2 text-2xl sm:text-3xl font-comfortaa-bold text-text-color">
                On garde tout au même endroit.
              </h1>
              <p className="mt-2 text-sm text-text-gray max-w-xl">
                Pour l'instant, cette page centralise surtout tes travaux. Elle accueillera ensuite
                tes révisions, favoris et parcours personnalisés.
              </p>
            </div>
            <div className="grid grid-cols-2 gap-3 sm:w-44 shrink-0">
              <HeroStat value={totalWorks} label="travaux" />
              <HeroStat value={correctedCount} label="corrigés" />
            </div>
          </div>
        </section>

        <div className="grid lg:grid-cols-[1fr_280px] gap-6">
          <section className="space-y-3">
            <div className="flex items-center justify-between gap-3">
              <p className="mm-section-header">Mes travaux</p>
              <Link
                href={route('student.assignments.index')}
                className="text-xs font-comfortaa-bold text-student-color hover:underline inline-flex items-center gap-1"
              >
                Vue devoirs <ChevronRight size={12} />
              </Link>
            </div>
            <StudentWorkSection dss={dss} dms={dms} tds={tds} />
          </section>

          <aside className="space-y-3">
            <p className="mm-section-header">À venir</p>
            <StudentResourceFeatureCard
              icon={Sparkles}
              title="Révisions"
              description="Fiches, exercices et quiz utiles selon ton niveau et tes chapitres."
            />
            <StudentResourceFeatureCard
              icon={Heart}
              title="Favoris"
              description="Retrouver rapidement les exercices, corrections ou devoirs importants."
            />
            <StudentResourceFeatureCard
              icon={BrainCircuit}
              title="Parcours IA"
              description="Un parcours guidé et progressif pour débloquer les exercices suivants."
            />
          </aside>
        </div>
      </div>
    </AppLayout>
  );
}

function HeroStat({ value, label }: { value: number; label: string }) {
  return (
    <div className="rounded-2xl bg-surface-color border border-border-color px-3 py-2 text-center">
      <p className="font-cmu-serif text-2xl text-text-color leading-none">{value}</p>
      <p className="mt-0.5 text-[10px] font-comfortaa-bold uppercase tracking-widest text-text-gray">
        {label}
      </p>
    </div>
  );
}
