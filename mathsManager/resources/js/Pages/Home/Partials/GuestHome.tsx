import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';

export default function GuestHome() {
  return (
    <div className="space-y-6 animate-fadeIn">
      {/* ── Hero ── */}
      <div className="relative bg-secondary-color border border-border-color rounded-3xl px-8 py-12 overflow-hidden">
        <div
          className="absolute right-8 top-1/2 -translate-y-1/2 pointer-events-none select-none"
          aria-hidden
        >
          <span className="text-[120px] font-cmu-serif text-text-color opacity-[0.04] leading-none">
            ∑
          </span>
        </div>
        <div className="relative space-y-5 max-w-lg">
          <p className="text-[11px] font-comfortaa-bold text-tertiary-color uppercase tracking-widest">
            Maths Manager
          </p>
          <h1 className="text-3xl sm:text-4xl font-comfortaa-bold text-text-color leading-tight">
            La plateforme des profs de maths.
          </h1>
          <p className="text-sm text-text-gray leading-relaxed">
            Créez et assignez vos DS, DM et TD en quelques clics. Suivez la progression de vos
            élèves, corrigez leurs copies, débloquez les corrections.
          </p>
          <div className="flex items-center gap-3 flex-wrap pt-1">
            <Link
              href={route('login')}
              className="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-tertiary-color text-white text-sm font-comfortaa-bold shadow-warm-xs hover:opacity-90 transition-opacity"
            >
              Se connecter
              <ChevronRight size={14} />
            </Link>
            <Link
              href={route('register')}
              className="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl border border-border-color text-sm font-comfortaa-bold text-text-color hover:bg-surface-color transition-colors"
            >
              Créer un compte
            </Link>
          </div>
        </div>
      </div>

      {/* ── Roles ── */}
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div className="bg-secondary-color border border-border-color rounded-2xl p-5 space-y-2">
          <p className="text-[10px] font-comfortaa-bold text-teacher-color uppercase tracking-widest">
            Professeur
          </p>
          <p className="text-sm font-comfortaa-bold text-text-color">Gérez vos devoirs</p>
          <p className="text-xs text-text-gray leading-relaxed">
            Construisez vos sujets depuis une bibliothèque d'exercices, assignez-les à vos groupes,
            suivez les rendus et corrigez.
          </p>
        </div>
        <div className="bg-secondary-color border border-border-color rounded-2xl p-5 space-y-2">
          <p className="text-[10px] font-comfortaa-bold text-student-color uppercase tracking-widest">
            Élève
          </p>
          <p className="text-sm font-comfortaa-bold text-text-color">Travaillez à votre rythme</p>
          <p className="text-xs text-text-gray leading-relaxed">
            Accédez à vos devoirs, composez en conditions réelles avec chronomètre, envoyez votre
            copie et recevez vos corrections.
          </p>
        </div>
      </div>
    </div>
  );
}
