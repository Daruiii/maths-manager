import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import { XCircle, BookOpen } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';

interface Props {
  adminNotes?: string;
  applicationDate?: string;
}

export default function Rejected({ adminNotes, applicationDate }: Props) {
  return (
    <AppLayout>
      <Head title="Candidature refusée" />

      <div className="min-h-[calc(100vh-8rem)] flex flex-col items-center justify-center p-4">
        <div className="max-w-xl w-full text-center">
          <div className="inline-flex h-20 w-20 items-center justify-center rounded-3xl bg-error-color/10 text-error-color mb-8 shadow-inner">
            <XCircle size={40} />
          </div>

          <h1 className="text-3xl md:text-4xl font-comfortaa-bold text-text-color mb-6">
            Candidature non retenue
          </h1>

          <p className="text-text-gray font-comfortaa text-base md:text-lg leading-relaxed mb-10">
            Nous avons examiné avec attention votre demande d'accès à l'espace professeur, mais nous
            ne pouvons malheureusement pas y donner une suite favorable pour le moment.
          </p>

          <div className="flex flex-col items-center gap-4 mb-10 text-sm font-comfortaa w-full">
            <div className="flex flex-wrap items-center justify-center gap-3">
              <div className="flex items-center gap-2 bg-error-color/10 px-4 py-2 rounded-full border border-error-color/20">
                <span className="text-text-gray/70">Statut :</span>
                <span className="text-error-color font-bold">Refusé</span>
              </div>

              {applicationDate && (
                <div className="flex items-center gap-2 bg-surface-color/50 px-4 py-2 rounded-full border border-border-color">
                  <span className="text-text-gray/70">Demande envoyée le :</span>
                  <span className="text-text-color font-medium">{applicationDate}</span>
                </div>
              )}
            </div>

            {adminNotes && (
              <div className="w-full max-w-md mt-2 bg-error-color/5 p-5 rounded-2xl border border-error-color/20 text-center">
                <span className="text-text-gray/70 block mb-2 text-xs uppercase tracking-wider font-comfortaa-bold">
                  Motif de la décision
                </span>
                <p className="text-text-color font-medium text-sm sm:text-base leading-relaxed">
                  {adminNotes}
                </p>
              </div>
            )}
          </div>

          <div className="bg-surface-color/50 rounded-2xl p-6 sm:p-8 border border-border-color mb-10 text-left">
            <h3 className="font-comfortaa-bold text-text-color text-base mb-4">
              Que pouvez-vous faire ?
            </h3>

            <p className="font-comfortaa text-sm sm:text-base text-text-gray mb-6">
              Vous pouvez toujours utiliser Maths Manager en tant qu'élève pour vous entraîner et
              profiter de nos milliers d'exercices.
            </p>

            <Link
              href={route('onboarding.switch-to-student')}
              method="post"
              as="button"
              className="w-full"
            >
              <Button variant="student" icon={BookOpen} className="w-full h-12">
                Passer sur l'espace Élève
              </Button>
            </Link>
          </div>

          <p className="text-sm text-text-gray/70 font-comfortaa">
            Si vous pensez qu'il s'agit d'une erreur, vous pouvez contacter le support à l'adresse{' '}
            <a
              href="mailto:contact@maths-manager.fr"
              className="text-tertiary-color hover:underline"
            >
              contact@maths-manager.fr
            </a>
            .
          </p>
        </div>
      </div>
    </AppLayout>
  );
}
