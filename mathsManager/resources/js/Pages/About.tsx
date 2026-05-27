import { Head, Link } from '@inertiajs/react';
import { Star } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import MathAvatar from '@/Components/Common/Avatar/MathAvatar';

const REVIEWS = [
  {
    name: 'Sandra',
    year: '2025',
    quote:
      "Hyper pédagogue et très disponible. Les exercices types m'ont aidé toute l'année et m'ont permis de passer le bac sereinement. J'entre en prépa en septembre.",
  },
  {
    name: 'Julie',
    year: '2024',
    quote:
      "J'ai réussi à faire des progrès significatifs remarqués par ma prof de maths du lycée, en partant d'un niveau − que 0.",
  },
  {
    name: 'Ruben',
    year: '2023',
    quote: "Grâce à lui j'ai eu mon Bac avec mention.",
  },
  {
    name: 'David',
    year: '2023',
    quote: "Professeur pédagogue avec une façon d'expliquer les choses efficacement.",
  },
];

export default function About() {
  return (
    <AppLayout>
      <Head title="À propos — Maths Manager" />
      <div className="max-w-3xl mx-auto px-4 py-10 space-y-10">
        <div className="space-y-3">
          <p className="text-[11px] font-comfortaa-bold text-tertiary-color uppercase tracking-widest">
            À propos
          </p>
          <h1 className="text-3xl font-comfortaa-bold text-text-color">Maths Manager</h1>
          <p className="text-base text-text-gray leading-relaxed max-w-2xl">
            Une plateforme de soutien scolaire en mathématiques — exercices corrigés, devoirs suivis
            et accompagnement personnalisé entre élèves et professeurs.
          </p>
        </div>

        <div className="bg-secondary-color border border-border-color rounded-2xl p-6 space-y-4">
          <p className="text-[10px] font-comfortaa-bold text-teacher-color uppercase tracking-widest">
            Le professeur derrière la plateforme
          </p>
          <div className="space-y-2">
            <h2 className="text-lg font-comfortaa-bold text-text-color">Maxime</h2>
            <p className="text-sm text-text-gray leading-relaxed">
              Maxime enseigne les mathématiques depuis plus de 10 ans. Après un Bac S spécialité
              maths, une classe préparatoire (MPSI / MP*) et une école d'ingénieur, il a développé
              une méthode centrée sur la compréhension, la méthode et la progression réelle. Il a
              créé Maths Manager pour proposer à ses élèves un suivi plus sérieux qu'un simple cours
              ponctuel.
            </p>
          </div>
          <div className="flex items-center gap-6 pt-1">
            <Stat value="10+" label="ans d'enseignement" />
            <Stat value="5/5" label="note Superprof" />
            <Stat value="8" label="élèves accompagnés" />
          </div>
        </div>

        <div className="space-y-4">
          <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest">
            Ce que disent les élèves
          </p>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
            {REVIEWS.map((r) => (
              <div
                key={r.name}
                className="bg-secondary-color border border-border-color rounded-2xl p-4 space-y-3"
              >
                <div className="flex items-center gap-2.5">
                  <MathAvatar name={r.name} size="md" />
                  <div>
                    <p className="text-[11px] font-comfortaa-bold text-text-color">
                      {r.name} · {r.year}
                    </p>
                    <div className="flex gap-0.5 mt-0.5">
                      {Array.from({ length: 5 }).map((_, i) => (
                        <Star key={i} size={10} className="fill-warning-color text-warning-color" />
                      ))}
                    </div>
                  </div>
                </div>
                <p className="text-sm text-text-color leading-relaxed">&ldquo;{r.quote}&rdquo;</p>
              </div>
            ))}
          </div>
          <p className="text-[10px] text-text-gray italic">Avis vérifiés — source : Superprof</p>
        </div>

        <div className="border-t border-border-color pt-8 text-center space-y-3">
          <p className="text-sm text-text-gray">Envie de progresser en maths ?</p>
          <Link
            href={route('login')}
            className="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-tertiary-color text-white text-sm font-comfortaa-bold shadow-warm-xs hover:opacity-90 transition-opacity"
          >
            Commencer maintenant
          </Link>
        </div>
      </div>
    </AppLayout>
  );
}

function Stat({ value, label }: { value: string; label: string }) {
  return (
    <div>
      <p className="text-2xl font-cmu-serif text-text-color leading-none">{value}</p>
      <p className="mt-0.5 text-[10px] font-comfortaa-bold uppercase tracking-widest text-text-gray">
        {label}
      </p>
    </div>
  );
}
