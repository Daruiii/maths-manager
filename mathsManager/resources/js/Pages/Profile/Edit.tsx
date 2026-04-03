import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { User, Lock, Trash2, GraduationCap, Shield, ArrowLeft, Sigma } from 'lucide-react';
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from '@headlessui/react';
import type { PageProps } from '@/types';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm';
import UpdateTeacherInformationForm from '@/Pages/Profile/Partials/UpdateTeacherInformationForm';
import UpdateTeacherMacrosForm from '@/Components/Features/Profile/UpdateTeacherMacrosForm';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm';
import Card from '@/Components/Common/UI/Card';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Button from '@/Components/Common/UI/Button';
import { useAuth } from '@/Hooks/Auth/useAuth';

interface ProfileProps extends PageProps {
  mustVerifyEmail?: boolean;
}

export default function Edit({ mustVerifyEmail }: ProfileProps) {
  const { user, canActAsTeacher } = useAuth();

  const getTabClass = (selected: boolean, activeClasses: string) => {
    return `flex items-center gap-3 whitespace-nowrap px-4 py-3.5 rounded-xl text-left text-sm font-comfortaa-bold transition-all outline-none border ${
      selected
        ? activeClasses
        : 'bg-transparent text-text-gray border-transparent hover:bg-surface-color/50'
    }`;
  };

  const tabs = [
    {
      id: 'info',
      label: 'Informations',
      icon: User,
      show: true,
      activeClasses: 'bg-tertiary-color/10 text-tertiary-color border-tertiary-color/30 shadow-sm',
      panel: (
        <Card
          title="Informations personnelles"
          icon={<User className="w-5 h-5" strokeWidth={2.5} />}
        >
          <UpdateProfileInformationForm mustVerifyEmail={mustVerifyEmail} />
        </Card>
      ),
    },
    {
      id: 'teacher',
      label: 'Profil Professeur',
      icon: GraduationCap,
      show: canActAsTeacher,
      activeClasses: 'bg-teacher-color/10 text-teacher-color border-teacher-color/30 shadow-sm',
      panel: (
        <Card
          title="Informations Professeur"
          variant="teacher"
          icon={<GraduationCap className="w-5 h-5" strokeWidth={2.5} />}
        >
          <UpdateTeacherInformationForm />
        </Card>
      ),
    },
    {
      id: 'security',
      label: 'Sécurité',
      icon: Shield,
      show: true,
      activeClasses: 'bg-text-color/5 text-text-color border-border-color shadow-sm',
      panel: !user?.provider ? (
        <Card
          title="Sécurité"
          variant="default"
          icon={<Lock className="w-5 h-5" strokeWidth={2.5} />}
        >
          <UpdatePasswordForm />
        </Card>
      ) : (
        <Card
          title="Sécurité"
          variant="default"
          icon={<Lock className="w-5 h-5" strokeWidth={2.5} />}
          className="opacity-75"
        >
          <div className="p-6 text-center text-text-gray">
            <p className="mb-4">
              Votre mot de passe est géré par{' '}
              <strong>{user?.provider === 'google' ? 'Google' : 'votre fournisseur'}</strong>.
            </p>
            <div className="inline-flex items-center px-4 py-2 bg-surface-color rounded-full text-sm">
              <Lock className="w-4 h-4 mr-2" /> Modification désactivée
            </div>
          </div>
        </Card>
      ),
    },
    {
      id: 'macros',
      label: 'Macros LaTeX',
      icon: Sigma,
      show: canActAsTeacher,
      activeClasses: 'bg-teacher-color/10 text-teacher-color border-teacher-color/30 shadow-sm',
      panel: (
        <Card
          title="Macros LaTeX"
          variant="teacher"
          icon={<Sigma className="w-5 h-5" strokeWidth={2.5} />}
        >
          <UpdateTeacherMacrosForm />
        </Card>
      ),
    },
    {
      id: 'danger',
      label: 'Zone de Danger',
      icon: Trash2,
      show: true,
      activeClasses: 'bg-error-color/10 text-error-color border-error-color/30 shadow-sm',
      panel: (
        <Card
          variant="danger"
          title="Zone de Danger"
          icon={<Trash2 className="w-5 h-5" strokeWidth={2.5} />}
        >
          <DeleteUserForm />
        </Card>
      ),
    },
  ].filter((tab) => tab.show);

  return (
    <AppLayout title="Paramètres">
      <div className="py-12 px-4 sm:px-6 lg:px-8">
        <div className="max-w-5xl mx-auto">
          <PageHeader
            title="Paramètres"
            subtitle="Modifiez vos informations personnelles et sécurisez votre compte."
            breadcrumbs={[
              { label: 'Mon Profil', href: route('profile.show') },
              { label: 'Paramètres' },
            ]}
            action={
              <Link href={route('profile.show')}>
                <Button variant="secondary" icon={ArrowLeft} iconSize={18}>
                  <span className="hidden sm:inline">Retour au profil</span>
                </Button>
              </Link>
            }
          />

          <div className="mt-8 flex flex-col lg:flex-row gap-8">
            <TabGroup vertical className="w-full flex flex-col lg:flex-row gap-8 items-start">
              {/* Tab List */}
              <div className="relative w-full lg:w-64 shrink-0">
                <TabList className="flex lg:flex-col gap-2 overflow-x-auto lg:overflow-visible w-full pb-2 lg:pb-0 custom-scrollbar">
                  {tabs.map((tab) => {
                    const Icon = tab.icon;
                    return (
                      <Tab
                        key={tab.id}
                        className={({ selected }) => getTabClass(selected, tab.activeClasses)}
                      >
                        <Icon size={18} /> {tab.label}
                      </Tab>
                    );
                  })}
                </TabList>
                {/* Indicateur de scroll horizontal sur mobile */}
                <div className="pointer-events-none absolute right-0 top-0 h-full w-8 bg-gradient-to-l from-primary-color to-transparent lg:hidden" />
              </div>

              {/* Tab Panels */}
              <TabPanels className="flex-1 w-full relative">
                {tabs.map((tab) => (
                  <TabPanel key={tab.id} className="focus:outline-none animate-fade-in">
                    {tab.panel}
                  </TabPanel>
                ))}
              </TabPanels>
            </TabGroup>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
