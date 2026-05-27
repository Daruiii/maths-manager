import { Link } from '@inertiajs/react';
import { ArrowRight, CheckCircle2 } from 'lucide-react';
import GuestReviews from '@/Pages/Home/Partials/GuestReviews';

const TRUST_ITEMS = [
  'Exercices corrigés',
  'DS et DM',
  'Suivi professeur',
  'Fiches de révision',
  'Quiz',
  'Parcours personnalisés',
];

const PREVIEW_ITEMS = [
  {
    type: 'DS',
    title: 'Fonctions',
    meta: 'À rendre vendredi',
    tone: 'text-ds-color bg-ds-color/10',
  },
  {
    type: 'DM',
    title: 'Suites numériques',
    meta: 'Correction reçue',
    tone: 'text-dm-color bg-dm-color/10',
  },
  { type: 'TD', title: 'Probabilités', meta: 'Disponible', tone: 'text-td-color bg-td-color/10' },
];

export default function GuestHome() {
  return (
    <div className="space-y-6 animate-fadeIn">
      <section className="relative mm-card mm-card-style-halo rounded-3xl px-6 sm:px-8 py-10 sm:py-12 overflow-hidden">
        <div
          className="absolute inset-0 flex items-center justify-end pr-8 pointer-events-none select-none"
          aria-hidden
        >
          <span className="text-[150px] font-cmu-serif text-text-color opacity-[0.04] leading-none">
            ∑
          </span>
        </div>

        <div className="relative grid gap-8 lg:grid-cols-[minmax(0,1.15fr)_360px] lg:items-center">
          <div className="space-y-5">
            <p className="text-[11px] font-comfortaa-bold text-tertiary-color uppercase tracking-widest">
              Maths Manager
            </p>
            <div className="space-y-3">
              <h1 className="text-3xl sm:text-4xl lg:text-5xl font-comfortaa-bold text-text-color leading-tight">
                Soutien scolaire en maths, exercices corrigés et suivi personnalisé.
              </h1>
              <p className="text-sm sm:text-base text-text-gray leading-relaxed max-w-2xl">
                Maths Manager aide les élèves à progresser en mathématiques avec des DS, DM, TD,
                fiches, quiz et exercices corrigés, tout en donnant aux professeurs un espace clair
                pour assigner, suivre et corriger le travail.
              </p>
            </div>

            <div className="flex items-center gap-3 flex-wrap">
              <Link
                href={route('login')}
                className="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-tertiary-color text-white text-sm font-comfortaa-bold shadow-warm-xs hover:opacity-90 transition-opacity"
              >
                Commencer maintenant
                <ArrowRight size={14} />
              </Link>
              <Link
                href={route('register')}
                className="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl border border-border-color text-sm font-comfortaa-bold text-text-color hover:bg-surface-color transition-colors"
              >
                Je suis professeur
              </Link>
            </div>

            <p className="text-xs text-text-gray">
              Terminale spé · Maths expertes · ECG · MPSI/PCSI
            </p>
          </div>

          <ProductPreview />
        </div>
      </section>

      <div className="flex flex-wrap gap-2">
        {TRUST_ITEMS.map((item) => (
          <span
            key={item}
            className="rounded-full border border-border-color bg-secondary-color px-3 py-1.5 text-[11px] font-comfortaa-bold text-text-gray"
          >
            {item}
          </span>
        ))}
      </div>

      <section className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <AudienceCard
          eyebrow="Pour les élèves"
          title="Préparer ses contrôles avec plus de méthode"
          text="Retrouvez vos DS, DM, TD, fiches de révision, quiz et corrections dans un espace clair. Entraînez-vous sur les bons exercices et suivez votre progression plus facilement."
          tone="student"
        />
        <AudienceCard
          eyebrow="Pour les professeurs"
          title="Assigner, suivre et corriger sans multiplier les outils"
          text="Créez vos devoirs, utilisez une bibliothèque d'exercices, assignez des travaux à vos élèves et gardez une vision claire du travail de chacun."
          tone="teacher"
        />
      </section>

      <GuestReviews />

      <section className="bg-secondary-color border border-border-color rounded-2xl p-5 sm:p-6 space-y-2">
        <p className="text-[10px] font-comfortaa-bold text-tertiary-color uppercase tracking-widest">
          Suivi & autonomie
        </p>
        <h2 className="text-lg font-comfortaa-bold text-text-color">
          Un parcours de maths qui devient plus intelligent avec le temps.
        </h2>
        <p className="text-sm text-text-gray leading-relaxed max-w-3xl">
          Chaque DS, quiz, correction et chapitre travaillé peut devenir un repère pour mieux
          comprendre les besoins de l'élève. Aujourd'hui, Maths Manager aide les élèves à travailler
          avec un suivi professeur. Demain, des parcours personnalisés pourront proposer des
          exercices adaptés, des étapes à valider et un travail plus ciblé.
        </p>
      </section>
    </div>
  );
}

function ProductPreview() {
  return (
    <div className="relative rounded-3xl border border-border-color bg-secondary-color/90 p-4 shadow-warm-xs">
      <div className="mb-4 flex items-center justify-between">
        <div>
          <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest">
            Cette semaine
          </p>
          <p className="text-sm font-comfortaa-bold text-text-color">À faire maintenant</p>
        </div>
        <span className="rounded-full bg-success-color/10 px-2 py-1 text-[10px] font-comfortaa-bold text-success-color">
          Suivi clair
        </span>
      </div>

      <div className="space-y-2">
        {PREVIEW_ITEMS.map((item) => (
          <div
            key={`${item.type}-${item.title}`}
            className="flex items-center gap-3 rounded-2xl border border-border-color bg-surface-color px-3 py-3"
          >
            <span
              className={`w-10 rounded-xl py-2 text-center text-xs font-comfortaa-bold ${item.tone}`}
            >
              {item.type}
            </span>
            <div className="min-w-0 flex-1">
              <p className="truncate text-sm font-comfortaa-bold text-text-color">{item.title}</p>
              <p className="text-xs text-text-gray">{item.meta}</p>
            </div>
            <ArrowRight size={13} className="text-text-gray" />
          </div>
        ))}
      </div>

      <div className="mt-4 rounded-2xl bg-student-color/10 px-3 py-3">
        <div className="flex items-center gap-2">
          <CheckCircle2 size={15} className="text-student-color" />
          <p className="text-xs font-comfortaa-bold text-text-color">
            Correction disponible sur le DM — Suites numériques
          </p>
        </div>
      </div>
    </div>
  );
}

function AudienceCard({
  eyebrow,
  title,
  text,
  tone,
}: {
  eyebrow: string;
  title: string;
  text: string;
  tone: 'student' | 'teacher';
}) {
  const toneClass = tone === 'student' ? 'text-student-color' : 'text-teacher-color';

  return (
    <div className="bg-secondary-color border border-border-color rounded-2xl p-5 space-y-2">
      <p className={`text-[10px] font-comfortaa-bold uppercase tracking-widest ${toneClass}`}>
        {eyebrow}
      </p>
      <h2 className="text-base font-comfortaa-bold text-text-color">{title}</h2>
      <p className="text-sm text-text-gray leading-relaxed">{text}</p>
    </div>
  );
}
