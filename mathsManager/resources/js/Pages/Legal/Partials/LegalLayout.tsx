import { Link } from '@inertiajs/react';
import PageHeader from '@/Components/Common/UI/PageHeader';

interface LegalSection {
  title: string;
  content: string;
}

interface Props {
  title: string;
  subtitle: string;
  eyebrow: string;
  sections: LegalSection[];
}

export default function LegalLayout({ title, subtitle, eyebrow, sections }: Props) {
  return (
    <div className="mx-auto max-w-4xl space-y-8 px-4 py-10">
      <PageHeader title={title} subtitle={subtitle} breadcrumbs={[{ label: title }]} />

      <div className="mm-card mm-card-style-halo rounded-3xl p-6 sm:p-8">
        <p className="text-[10px] font-comfortaa-bold uppercase tracking-widest text-tertiary-color">
          {eyebrow}
        </p>
        <h1 className="mt-3 font-cmu-serif text-3xl leading-tight text-text-color sm:text-4xl">
          Des règles simples pour un espace de travail sérieux.
        </h1>
        <p className="mt-4 max-w-2xl text-sm leading-relaxed text-text-gray">
          Ces pages sont une version de travail. Elles posent les principes importants de Maths
          Manager en attendant une version juridique plus complète.
        </p>
      </div>

      <div className="grid gap-4">
        {sections.map((section, index) => (
          <section
            key={section.title}
            className="mm-card mm-card-style-raised rounded-2xl p-5 sm:p-6"
          >
            <div className="flex gap-4">
              <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-tertiary-color/10 font-cmu-serif text-sm text-tertiary-color">
                {index + 1}
              </span>
              <div className="space-y-2">
                <h2 className="font-comfortaa-bold text-text-color">{section.title}</h2>
                <p className="text-sm leading-relaxed text-text-gray">{section.content}</p>
              </div>
            </div>
          </section>
        ))}
      </div>

      <div className="rounded-2xl border border-border-color bg-secondary-color p-5 text-sm text-text-gray shadow-warm-xs">
        Une question sur ces règles ?{' '}
        <Link href="/contact" className="font-comfortaa-bold text-tertiary-color hover:underline">
          Contacte Maths Manager
        </Link>
        .
      </div>
    </div>
  );
}
