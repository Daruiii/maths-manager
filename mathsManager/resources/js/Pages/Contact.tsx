import { Head } from '@inertiajs/react';
import { Mail, MessageCircle, ShieldCheck } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';

const CONTACT_EMAIL = 'maxime@mathsmanager.fr';

export default function Contact() {
  return (
    <AppLayout>
      <Head title="Contact — Maths Manager" />
      <div className="mx-auto max-w-3xl space-y-8 px-4 py-10">
        <PageHeader
          title="Contact"
          subtitle="Une question sur Maths Manager, un accès professeur, un contenu ou une donnée personnelle ?"
          breadcrumbs={[{ label: 'Contact' }]}
        />

        <div className="mm-card mm-card-style-halo rounded-3xl p-6 sm:p-8">
          <div className="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <p className="text-[10px] font-comfortaa-bold uppercase tracking-widest text-tertiary-color">
                Email principal
              </p>
              <h1 className="mt-2 break-all font-cmu-serif text-3xl text-text-color">
                {CONTACT_EMAIL}
              </h1>
              <p className="mt-3 text-sm leading-relaxed text-text-gray">
                Le plus simple pour l’instant : envoyer un email direct. Une page support plus
                complète viendra plus tard.
              </p>
            </div>
            <a
              href={`mailto:${CONTACT_EMAIL}`}
              className="inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl bg-tertiary-color px-5 py-3 text-sm font-comfortaa-bold text-white shadow-warm-xs transition-opacity hover:opacity-90"
            >
              <Mail size={16} />
              Écrire
            </a>
          </div>
        </div>

        <div className="grid gap-3 sm:grid-cols-3">
          <ContactReason
            icon={MessageCircle}
            title="Question produit"
            text="Accès, devoirs, corrections, comptes élèves ou espace professeur."
          />
          <ContactReason
            icon={ShieldCheck}
            title="Données"
            text="Demande liée à la confidentialité, aux copies ou aux informations de compte."
          />
          <ContactReason
            icon={Mail}
            title="Contenu"
            text="Question sur un exercice, une ressource ou un contenu pédagogique."
          />
        </div>
      </div>
    </AppLayout>
  );
}

function ContactReason({
  icon: Icon,
  title,
  text,
}: {
  icon: typeof Mail;
  title: string;
  text: string;
}) {
  return (
    <div className="mm-card mm-card-style-raised rounded-2xl p-4">
      <Icon size={17} className="text-tertiary-color" />
      <h2 className="mt-3 text-sm font-comfortaa-bold text-text-color">{title}</h2>
      <p className="mt-2 text-xs leading-relaxed text-text-gray">{text}</p>
    </div>
  );
}
