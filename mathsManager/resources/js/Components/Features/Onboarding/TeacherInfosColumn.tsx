import { GraduationCap, ArrowLeft } from 'lucide-react';
import { Link } from '@inertiajs/react';
import { TEACHER_ONBOARDING_STEPS } from '@/Constants/onboarding';

export default function TeacherInfosColumn() {
  return (
    <div className="md:w-5/12 flex flex-col justify-center">
      <div className="mb-8 md:mb-12">
        <Link
          href={route('onboarding.role')}
          className="inline-flex items-center gap-2 text-sm font-comfortaa-bold text-teacher-color hover:text-teacher-color/70 hover:-translate-x-1 transition-all"
        >
          <ArrowLeft size={16} /> Retour au choix du rôle
        </Link>
      </div>

      <div className="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-teacher-color/10 text-teacher-color mb-6 shadow-inner rotate-3">
        <GraduationCap size={32} />
      </div>
      <h1 className="text-3xl lg:text-4xl font-comfortaa-bold text-text-color mb-4 leading-tight">
        Bienvenue du <br />
        <span className="text-transparent bg-clip-text bg-gradient-to-r from-teacher-color to-purple-500">
          côté prof
        </span>
      </h1>
      <p className="text-text-gray font-comfortaa text-sm md:text-base leading-relaxed mb-8">
        Pour garantir la qualité de la plateforme{' '}
        <span className="text-tertiary-color font-bold">Maths Manager</span>, l'accès à l'espace
        professeur est soumis à une brève validation de votre profil.
      </p>
      <ul className="space-y-4 font-comfortaa text-sm md:text-base text-text-color bg-surface-color/50 p-6 rounded-2xl border border-border-color">
        {TEACHER_ONBOARDING_STEPS.map((step, idx) => (
          <li key={idx} className="flex items-center gap-3">
            <div className="h-3 w-3 rounded-full bg-teacher-color shrink-0"></div>
            <span>{step}</span>
          </li>
        ))}
      </ul>
    </div>
  );
}
