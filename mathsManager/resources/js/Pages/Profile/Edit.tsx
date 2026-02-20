import AppLayout from '@/Layouts/AppLayout';
import { usePage, Link } from '@inertiajs/react';
import { User, Lock, Trash2, GraduationCap, Shield, ArrowLeft } from 'lucide-react';
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from '@headlessui/react';
import type { PageProps } from '@/types';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm';
import UpdateTeacherInformationForm from '@/Pages/Profile/Partials/UpdateTeacherInformationForm';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm';
import Card from '@/Components/Common/UI/Card';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Button from '@/Components/Common/UI/Button';

interface ProfileProps extends PageProps {
  mustVerifyEmail?: boolean;
}

export default function Edit({ mustVerifyEmail }: ProfileProps) {
  return (
    <AppLayout title="Paramètres">
      <div className="py-12 px-4 sm:px-6 lg:px-8">
        <div className="max-w-5xl mx-auto">
          <div className="flex flex-row justify-between items-center gap-4">
            <PageHeader
              title="Paramètres"
              subtitle="Modifiez vos informations personnelles et sécurisez votre compte."
              breadcrumbs={[
                { label: 'Mon Profil', href: route('profile.show') },
                { label: 'Paramètres' },
              ]}
            />
            <Link href={route('profile.show')}>
              <Button variant="secondary" className="flex items-center">
                <ArrowLeft size={18} className="sm:mr-2" />
                <span className="hidden sm:inline">Retour au profil</span>
              </Button>
            </Link>
          </div>

          <div className="mt-8 flex flex-col lg:flex-row gap-8">
            <TabGroup vertical className="w-full flex flex-col lg:flex-row gap-8 items-start">
              {/* Tab List (Sidebar on Desktop, Horizontal Scroll on Mobile) */}
              <TabList className="flex lg:flex-col gap-2 overflow-x-auto lg:overflow-visible w-full lg:w-64 shrink-0 pb-2 lg:pb-0 scrollbar-hide">
                <Tab
                  className={({ selected }) =>
                    `flex items-center gap-3 whitespace-nowrap px-4 py-3.5 rounded-xl text-left text-sm font-comfortaa-bold transition-all outline-none border ${
                      selected
                        ? 'bg-tertiary-color/10 text-tertiary-color border-tertiary-color/30 shadow-sm'
                        : 'bg-transparent text-text-gray border-transparent hover:bg-surface-color/50'
                    }`
                  }
                >
                  <User size={18} /> Informations
                </Tab>

                {usePage<PageProps>().props.auth.user?.role === 'teacher' && (
                  <Tab
                    className={({ selected }) =>
                      `flex items-center gap-3 whitespace-nowrap px-4 py-3.5 rounded-xl text-left text-sm font-comfortaa-bold transition-all outline-none border ${
                        selected
                          ? 'bg-teacher-color/10 text-teacher-color border-teacher-color/30 shadow-sm'
                          : 'bg-transparent text-text-gray border-transparent hover:bg-surface-color/50'
                      }`
                    }
                  >
                    <GraduationCap size={18} /> Profil Professeur
                  </Tab>
                )}

                <Tab
                  className={({ selected }) =>
                    `flex items-center gap-3 whitespace-nowrap px-4 py-3.5 rounded-xl text-left text-sm font-comfortaa-bold transition-all outline-none border ${
                      selected
                        ? 'bg-text-color/5 text-text-color border-border-color shadow-sm'
                        : 'bg-transparent text-text-gray border-transparent hover:bg-surface-color/50'
                    }`
                  }
                >
                  <Shield size={18} /> Sécurité
                </Tab>

                <Tab
                  className={({ selected }) =>
                    `flex items-center gap-3 whitespace-nowrap px-4 py-3.5 rounded-xl text-left text-sm font-comfortaa-bold transition-all outline-none border ${
                      selected
                        ? 'bg-error-color/10 text-error-color border-error-color/30 shadow-sm'
                        : 'bg-transparent text-text-gray border-transparent hover:bg-surface-color/50'
                    }`
                  }
                >
                  <Trash2 size={18} /> Zone de Danger
                </Tab>
              </TabList>

              {/* Tab Panels */}
              <TabPanels className="flex-1 w-full relative">
                <TabPanel className="focus:outline-none animate-fade-in">
                  <Card
                    title="Informations personnelles"
                    icon={<User className="w-5 h-5" strokeWidth={2.5} />}
                  >
                    <UpdateProfileInformationForm mustVerifyEmail={mustVerifyEmail} className="" />
                  </Card>
                </TabPanel>

                {usePage<PageProps>().props.auth.user?.role === 'teacher' && (
                  <TabPanel className="focus:outline-none animate-fade-in">
                    <Card
                      title="Informations Professeur"
                      variant="teacher"
                      icon={<GraduationCap className="w-5 h-5" strokeWidth={2.5} />}
                    >
                      <UpdateTeacherInformationForm />
                    </Card>
                  </TabPanel>
                )}

                <TabPanel className="focus:outline-none animate-fade-in">
                  {!usePage<PageProps>().props.auth.user?.provider ? (
                    <Card
                      title="Sécurité"
                      variant="default"
                      icon={<Lock className="w-5 h-5" strokeWidth={2.5} />}
                    >
                      <UpdatePasswordForm className="" />
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
                          <strong>
                            {usePage<PageProps>().props.auth.user?.provider === 'google'
                              ? 'Google'
                              : 'votre fournisseur'}
                          </strong>
                          .
                        </p>
                        <div className="inline-flex items-center px-4 py-2 bg-surface-color rounded-full text-sm">
                          <Lock className="w-4 h-4 mr-2" />
                          Modification désactivée
                        </div>
                      </div>
                    </Card>
                  )}
                </TabPanel>

                <TabPanel className="focus:outline-none animate-fade-in">
                  <Card
                    variant="danger"
                    title="Zone de Danger"
                    icon={<Trash2 className="w-5 h-5" strokeWidth={2.5} />}
                  >
                    <DeleteUserForm className="" />
                  </Card>
                </TabPanel>
              </TabPanels>
            </TabGroup>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
