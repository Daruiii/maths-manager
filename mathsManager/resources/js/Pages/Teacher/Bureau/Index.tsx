import { Head, Link } from '@inertiajs/react';
import { History, BookMarked, Users, Send, ClipboardList, ChevronRight } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import RessourceCard from '@/Components/Common/UI/RessourceCard';
import Button from '@/Components/Common/UI/Button';
import { CONTENT_ITEM_META } from '@/Constants/contentTypes';

interface Props {
  stats: {
    exercisesCount: number;
    dsTemplatesCount: number;
    tdTemplatesCount: number;
    dmTemplatesCount: number;
    batchesCount: number;
    studentsCount: number;
  };
}

interface NavLinkProps {
  icon: React.ElementType;
  title: string;
  subtitle: string;
  href: string;
  count?: number;
  featured?: boolean;
}

function NavLink({ icon: Icon, title, subtitle, href, count, featured = false }: NavLinkProps) {
  return (
    <Link
      href={href}
      className={`group flex items-center gap-3 px-4 py-3 bg-secondary-color border rounded-2xl hover:bg-surface-color hover:shadow-warm-sm transition-all duration-200 ${
        featured ? 'border-teacher-color/25' : 'border-border-color'
      }`}
    >
      <div
        className={`p-2 border rounded-xl shrink-0 ${
          featured
            ? 'bg-teacher-color/10 border-teacher-color/20'
            : 'bg-surface-color border-border-color'
        }`}
      >
        <Icon size={15} className={featured ? 'text-teacher-color' : 'text-text-gray'} />
      </div>
      <div className="flex-1 min-w-0">
        <p className="text-sm font-comfortaa-bold text-text-color">{title}</p>
        <p className="text-[11px] text-text-gray mt-0.5">{subtitle}</p>
      </div>
      {count !== undefined && count > 0 && (
        <span className="text-xs font-comfortaa-bold tabular-nums text-text-gray bg-surface-color border border-border-color px-2 py-0.5 rounded-full shrink-0">
          {count}
        </span>
      )}
      <ChevronRight
        size={14}
        className="text-text-gray/50 group-hover:text-teacher-color transition-colors shrink-0"
      />
    </Link>
  );
}

export default function BureauIndex({ stats }: Props) {
  const templatesCount = stats.dsTemplatesCount + stats.tdTemplatesCount + stats.dmTemplatesCount;

  return (
    <AppLayout>
      <Head title="Mon Bureau" />

      <div className="max-w-4xl mx-auto px-4 py-6 space-y-8">
        <PageHeader
          title="Mon Bureau"
          subtitle="Vos ressources pédagogiques personnelles"
          breadcrumbs={[{ label: 'Mon Bureau' }]}
          action={
            <Link href={route('teacher.bureau.history')}>
              <Button variant="ghost" icon={History} size="sm">
                Historique
              </Button>
            </Link>
          }
        />

        {/* ── Travaux ── */}
        <section className="space-y-3">
          <p className="mm-section-header">Travaux</p>
          <div className="grid sm:grid-cols-2 gap-2">
            <NavLink
              icon={Send}
              title="Devoirs envoyés"
              subtitle="DS, DM et TD — vue par batch et par élève"
              href={route('teacher.bureau.devoirs')}
              count={stats.batchesCount}
              featured
            />
            <NavLink
              icon={ClipboardList}
              title="Corrections"
              subtitle="Copies reçues, notes et retours"
              href={route('teacher.corrections.index')}
              featured
            />
          </div>
        </section>

        {/* ── Ressources ── */}
        <section className="space-y-3">
          <p className="mm-section-header">Ressources</p>
          <div className="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <RessourceCard
              icon={CONTENT_ITEM_META.private.icon}
              title="Exercices privés"
              subtitle="Créez et gérez vos exercices"
              count={stats.exercisesCount}
              href={route('teacher.exercices.index')}
              color="teacher"
            />
            <RessourceCard
              icon={BookMarked}
              title="Mes modèles"
              subtitle="DS, TD et DM sauvegardés"
              count={templatesCount}
              href={route('teacher.bureau.templates')}
              color="teacher"
            />
          </div>
        </section>

        {/* ── Mes élèves ── */}
        <section className="space-y-3">
          <p className="mm-section-header">Classe</p>
          <NavLink
            icon={Users}
            title="Mes élèves"
            subtitle={
              stats.studentsCount > 0
                ? `${stats.studentsCount} élève${stats.studentsCount > 1 ? 's' : ''} actif${stats.studentsCount > 1 ? 's' : ''}`
                : 'Gérez vos élèves et groupes'
            }
            href={route('teacher.students.index')}
          />
        </section>
      </div>
    </AppLayout>
  );
}
