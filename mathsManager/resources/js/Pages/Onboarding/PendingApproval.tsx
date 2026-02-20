import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import { Clock, Settings } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';

interface Props {
  applicationDate?: string;
}

export default function PendingApproval({ applicationDate }: Props) {
  return (
    <AppLayout>
      <Head title="Candidature en cours d'examen" />

      <div className="min-h-[calc(100vh-8rem)] flex flex-col items-center justify-center p-4">
        <div className="max-w-xl w-full text-center">
          <div className="inline-flex h-20 w-20 items-center justify-center rounded-3xl bg-teacher-color/10 text-teacher-color mb-8 shadow-inner animate-pulse">
            <Clock size={40} />
          </div>

          <h1 className="text-3xl md:text-4xl font-comfortaa-bold text-text-color mb-6">
            Candidature en cours d'examen
          </h1>

          <p className="text-text-gray font-comfortaa text-base md:text-lg leading-relaxed mb-10">
            Merci pour votre intérêt ! Notre équipe examine actuellement votre profil professeur.
            Cette étape nous permet de garantir la qualité et la sécurité de l'expérience sur{' '}
            <span className="text-tertiary-color font-bold">Maths Manager</span>.
          </p>

          <div className="flex flex-wrap items-center justify-center gap-3 mb-10 text-sm font-comfortaa">
            <div className="flex items-center gap-2 bg-surface-color/50 px-4 py-2 rounded-full border border-border-color">
              <span className="text-text-gray/70">Statut :</span>
              <span className="flex items-center gap-2 text-teacher-color font-bold">
                <span className="relative flex h-2 w-2">
                  <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-teacher-color opacity-75"></span>
                  <span className="relative inline-flex rounded-full h-2 w-2 bg-teacher-color"></span>
                </span>
                En attente
              </span>
            </div>

            {applicationDate && (
              <div className="flex items-center gap-2 bg-surface-color/50 px-4 py-2 rounded-full border border-border-color">
                <span className="text-text-gray/70">Demande envoyée le :</span>
                <span className="text-text-color font-medium">{applicationDate}</span>
              </div>
            )}

            <div className="flex items-center gap-2 bg-surface-color/50 px-4 py-2 rounded-full border border-border-color">
              <span className="text-text-gray/70">Délai estimé :</span>
              <span className="text-text-color font-medium">24 à 48h</span>
            </div>
          </div>

          <div className="flex justify-center">
            <Link href={route('profile.show')}>
              <Button variant="secondary" className="px-8">
                <Settings size={18} className="mr-2" /> Mon profil
              </Button>
            </Link>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
